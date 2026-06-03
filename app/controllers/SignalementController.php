<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class SignalementController extends Controller
{
    private $signalementModel;

    public function __construct()
    {
        $this->requireRole('etudiant');
        $this->signalementModel = $this->model('SignalementModel');
    }

    /**
     * Traite l'envoi d'un signalement
     */
    public function send()
    {
        if (!$this->isPost()) {
            $this->redirect('/annonce');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            Session::setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/annonce/detail/' . ($post['idAnnonce'] ?? ''));
        }

        $idAnnonce = $post['idAnnonce'] ?? null;
        $motif = trim($post['motif'] ?? '');

        if (!$idAnnonce || empty($motif)) {
            Session::setFlash('error', 'Le motif du signalement est obligatoire.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        $data = [
            'idEtudiant' => $_SESSION['user_id'],
            'idAnnonce' => $idAnnonce,
            'motif' => $motif,
            'statut' => 'En attente',
            'dateSignalement' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->signalementModel->createReport($data)) {
                Session::setFlash('success', 'Votre signalement a été envoyé aux administrateurs.');
            } else {
                Session::setFlash('error', 'Une erreur est survenue lors de l\'envoi du signalement.');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', 'Erreur technique : ' . $e->getMessage());
        }

        $this->redirect('/annonce/detail/' . $idAnnonce);
    }
}
