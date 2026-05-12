<?php

namespace App\Controllers;

use App\Core\Controller;

class AlerteController extends Controller
{
    private $alerteModel;
    private $annonceModel;

    public function __construct()
    {
        $this->alerteModel = $this->model('AlerteModel');
        $this->annonceModel = $this->model('AnnonceModel');
    }

    /**
     * Affiche mes alertes
     */
    public function index()
    {
        $this->requireRole('etudiant');

        $alertes = $this->alerteModel->getAlertsByStudent($_SESSION['user_id']);

        $data = [
            'alertes' => $alertes,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('user/alerts', $data);
    }

    /**
     * Crée une nouvelle alerte
     */
    public function create()
    {
        $this->requireRole('etudiant');

        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('user/alerte_create', $data);
    }

    /**
     * Traite la création d'une alerte
     */
    public function createHandler()
    {
        $this->requireRole('etudiant');

        if (!$this->isPost()) {
            $this->redirect('/alerte/create');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/alerte/create');
        }

        $localisation = $post['localisation'] ?? '';
        $budgetMax = $post['budgetMax'] ?? null;
        $colocation = isset($post['colocation']) ? 1 : 0;

        if (empty($localisation)) {
            $this->setFlash('error', 'La localisation est obligatoire.');
            $this->redirect('/alerte/create');
        }

        try {
            $data = [
                'idEtudiant' => $_SESSION['user_id'],
                'localisation' => $localisation,
                'budgetMax' => $budgetMax ?: null,
                'colocation' => $colocation
            ];

            $idAlerte = $this->alerteModel->createAlert($data);

            if (!$idAlerte) {
                $this->setFlash('error', 'Erreur lors de la création de l\'alerte.');
                $this->redirect('/alerte/create');
            }

            $this->setFlash('success', 'Alerte créée avec succès !');
            $this->redirect('/alerte');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la création : ' . $e->getMessage());
            $this->redirect('/alerte/create');
        }
    }

    /**
     * Édite une alerte
     */
    public function edit($idAlerte = null)
    {
        $this->requireRole('etudiant');

        if ($idAlerte === null) {
            $this->redirect('/alerte');
        }

        $alerte = $this->alerteModel->getAlertById($idAlerte);

        if (!$alerte || $alerte['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Alerte introuvable ou accès non autorisé.');
            $this->redirect('/alerte');
        }

        $data = [
            'alerte' => $alerte,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('user/alerte_edit', $data);
    }

    /**
     * Traite la modification d'une alerte
     */
    public function editHandler($idAlerte = null)
    {
        $this->requireRole('etudiant');

        if ($idAlerte === null || !$this->isPost()) {
            $this->redirect('/alerte');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/alerte');
        }

        $alerte = $this->alerteModel->getAlertById($idAlerte);

        if (!$alerte || $alerte['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Alerte introuvable ou accès non autorisé.');
            $this->redirect('/alerte');
        }

        $localisation = $post['localisation'] ?? '';
        $budgetMax = $post['budgetMax'] ?? null;
        $colocation = isset($post['colocation']) ? 1 : 0;

        if (empty($localisation)) {
            $this->setFlash('error', 'La localisation est obligatoire.');
            $this->redirect('/alerte/edit/' . $idAlerte);
        }

        try {
            $data = [
                'localisation' => $localisation,
                'budgetMax' => $budgetMax ?: null,
                'colocation' => $colocation
            ];

            $this->alerteModel->updateAlert($idAlerte, $data);

            $this->setFlash('success', 'Alerte mise à jour avec succès !');
            $this->redirect('/alerte');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
            $this->redirect('/alerte/edit/' . $idAlerte);
        }
    }

    /**
     * Supprime une alerte
     */
    public function delete($idAlerte = null)
    {
        $this->requireRole('etudiant');

        if ($idAlerte === null) {
            $this->redirect('/alerte');
        }

        $alerte = $this->alerteModel->getAlertById($idAlerte);

        if (!$alerte || $alerte['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Alerte introuvable ou accès non autorisé.');
            $this->redirect('/alerte');
        }

        try {
            $this->alerteModel->deleteAlert($idAlerte);
            $this->setFlash('success', 'Alerte supprimée avec succès !');
            $this->redirect('/alerte');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/alerte');
        }
    }

    /**
     * Affiche les annonces correspondant à une alerte
     */
    public function viewMatching($idAlerte = null)
    {
        $this->requireRole('etudiant');

        if ($idAlerte === null) {
            $this->redirect('/alerte');
        }

        $alerte = $this->alerteModel->getAlertById($idAlerte);

        if (!$alerte || $alerte['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Alerte introuvable ou accès non autorisé.');
            $this->redirect('/alerte');
        }

        $annonces = $this->alerteModel->getMatchingAnnouncements($idAlerte);

        $data = [
            'alerte' => $alerte,
            'annonces' => $annonces
        ];

        $this->view('user/alerte_matching', $data);
    }
}
