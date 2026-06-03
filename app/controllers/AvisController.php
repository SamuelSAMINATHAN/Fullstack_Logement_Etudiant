<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class AvisController extends Controller
{
    private $avisModel;

    public function __construct()
    {
        $this->requireRole('etudiant');
        $this->avisModel = $this->model('AvisModel');
    }

    /**
     * Traite l'ajout d'un avis
     */
    public function add()
    {
        if (!$this->isPost()) {
            $this->redirect('/annonce');
        }

        $post = $this->sanitizePost();
        if (!Security::verifyCsrf($post['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/annonce/detail/' . ($post['idAnnonce'] ?? ''));
        }

        $idAnnonce = $post['idAnnonce'] ?? null;
        $note = $post['note'] ?? null;
        $commentaire = $post['commentaire'] ?? '';

        if (!$idAnnonce || !$note) {
            Session::setFlash('error', 'La note est obligatoire.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        $data = [
            'idEtudiant' => $_SESSION['user_id'],
            'idAnnonce' => $idAnnonce,
            'note' => $note,
            'commentaire' => $commentaire,
            'dateAvis' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->avisModel->createReview($data)) {
                Session::setFlash('success', 'Votre avis a été publié.');
            } else {
                Session::setFlash('error', 'Erreur lors de la publication de l\'avis.');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', 'Vous avez déjà laissé un avis pour cette annonce.');
        }

        $this->redirect('/annonce/detail/' . $idAnnonce);
    }
}
