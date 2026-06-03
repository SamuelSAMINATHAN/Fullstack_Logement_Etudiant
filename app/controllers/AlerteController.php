<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class AlerteController extends Controller
{
    private $alerteModel;

    public function __construct()
    {
        $this->requireRole('etudiant');
        $this->alerteModel = $this->model('AlerteModel');
    }

    /**
     * Liste les alertes de l'étudiant
     */
    public function index()
    {
        $alertes = $this->alerteModel->getAlertsByStudent($_SESSION['user_id']);
        $this->view('user/alerts', ['alertes' => $alertes]);
    }

    /**
     * Crée une nouvelle alerte
     */
    public function create()
    {
        if (!$this->isPost()) {
            $this->redirect('/alerte');
        }

        $post = $this->sanitizePost();
        if (!Security::verifyCsrf($post['csrf_token'] ?? '')) {
            Session::setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/alerte');
        }

        $data = [
            'idEtudiant' => $_SESSION['user_id'],
            'localisation' => $post['localisation'] ?? null,
            'budgetMax' => !empty($post['budgetMax']) ? $post['budgetMax'] : null,
            'colocation' => isset($post['colocation']) ? 1 : 0
        ];

        if ($this->alerteModel->createAlert($data)) {
            Session::setFlash('success', 'Alerte créée avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors de la création de l\'alerte.');
        }

        $this->redirect('/alerte');
    }

    /**
     * Supprime une alerte
     */
    public function delete($id = null)
    {
        if (!$id) $this->redirect('/alerte');

        $alerte = $this->alerteModel->getAlertById($id);
        if ($alerte && $alerte['idEtudiant'] == $_SESSION['user_id']) {
            $this->alerteModel->deleteAlert($id);
            Session::setFlash('success', 'Alerte supprimée.');
        }

        $this->redirect('/alerte');
    }
}
