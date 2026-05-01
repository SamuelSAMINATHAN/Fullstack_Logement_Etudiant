<?php

class AdminSignalementController extends Controller
{
    private $signalementModel;
    private $annonceModel;

    public function __construct()
    {
        $this->signalementModel = $this->model('SignalementModel');
        $this->annonceModel = $this->model('AnnonceModel');
    }

    /**
     * Affiche les signalements
     */
    public function index()
    {
        $this->requireAdmin();

        $signalements = $this->signalementModel->getAllReports();

        $data = [
            'signalements' => $signalements,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/signalement_list', $data);
    }

    /**
     * Affiche les signalements en attente
     */
    public function pending()
    {
        $this->requireAdmin();

        $signalements = $this->signalementModel->getReportsByStatus('En attente');

        $data = [
            'signalements' => $signalements,
            'status' => 'En attente'
        ];

        $this->view('admin/signalement_list', $data);
    }

    /**
     * Affiche les détails d'un signalement
     */
    public function view($idSignalement = null)
    {
        $this->requireAdmin();

        if ($idSignalement === null) {
            $this->redirect('/admin/signalement');
        }

        $signalement = $this->signalementModel->getReportById($idSignalement);

        if (!$signalement) {
            $this->setFlash('error', 'Signalement introuvable.');
            $this->redirect('/admin/signalement');
        }

        // Récupérer l'annonce
        $signalement['annonce'] = $this->annonceModel->getAnnouncementById($signalement['idAnnonce']);

        $data = [
            'signalement' => $signalement,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/signalement_detail', $data);
    }

    /**
     * Traite un signalement (le marque comme traité)
     */
    public function process($idSignalement = null)
    {
        $this->requireAdmin();

        if ($idSignalement === null || !$this->isPost()) {
            $this->redirect('/admin/signalement');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/signalement');
        }

        $signalement = $this->signalementModel->getReportById($idSignalement);

        if (!$signalement) {
            $this->setFlash('error', 'Signalement introuvable.');
            $this->redirect('/admin/signalement');
        }

        $action = $post['action'] ?? '';

        try {
            if ($action === 'process') {
                $this->signalementModel->markAsProcessed($idSignalement);
                $this->setFlash('success', 'Signalement marqué comme traité.');
            } elseif ($action === 'reject') {
                $this->signalementModel->rejectReport($idSignalement);
                $this->setFlash('success', 'Signalement rejeté.');
            } elseif ($action === 'delete_annonce') {
                // Supprimer l'annonce
                $this->annonceModel->deleteAnnouncement($signalement['idAnnonce']);
                $this->signalementModel->markAsProcessed($idSignalement);
                $this->setFlash('success', 'Annonce supprimée et signalement marqué comme traité.');
            } else {
                $this->setFlash('error', 'Action invalide.');
                $this->redirect('/admin/signalement/view/' . $idSignalement);
            }

            $this->redirect('/admin/signalement');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors du traitement : ' . $e->getMessage());
            $this->redirect('/admin/signalement/view/' . $idSignalement);
        }
    }

    /**
     * Supprime un signalement
     */
    public function delete($idSignalement = null)
    {
        $this->requireAdmin();

        if ($idSignalement === null) {
            $this->redirect('/admin/signalement');
        }

        $signalement = $this->signalementModel->getReportById($idSignalement);

        if (!$signalement) {
            $this->setFlash('error', 'Signalement introuvable.');
            $this->redirect('/admin/signalement');
        }

        try {
            $this->signalementModel->deleteReport($idSignalement);
            $this->setFlash('success', 'Signalement supprimé.');
            $this->redirect('/admin/signalement');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/admin/signalement');
        }
    }
}
