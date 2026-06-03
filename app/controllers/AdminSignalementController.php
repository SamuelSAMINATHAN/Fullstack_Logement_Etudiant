<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class AdminsignalementController extends Controller
{
    private $signalementModel;
    private $bailleurModel;
    private $annonceModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->signalementModel = $this->model('SignalementModel');
        $this->bailleurModel = $this->model('BailleurModel');
        $this->annonceModel = $this->model('AnnonceModel');
    }

    /**
     * Liste tous les signalements
     */
    public function index()
    {
        $status = $_GET['status'] ?? null;
        $reports = $this->signalementModel->getAllReportsWithDetails($status);
        
        $this->view('admin/signalements/index', [
            'reports' => $reports,
            'current_status' => $status
        ]);
    }

    /**
     * Action : Marquer comme rejeté
     */
    public function reject($id)
    {
        if ($this->signalementModel->rejectReport($id)) {
            Session::setFlash('success', 'Signalement rejeté.');
        } else {
            Session::setFlash('error', 'Erreur lors du rejet.');
        }
        $this->redirect('/adminsignalement/index');
    }

    /**
     * Action : Marquer comme traité (avec options)
     */
    public function process($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/adminsignalement/index');
        }

        $action = $_POST['action'] ?? 'none';
        $report = $this->signalementModel->getReportById($id);

        if (!$report) {
            Session::setFlash('error', 'Signalement introuvable.');
            $this->redirect('/adminsignalement/index');
        }

        // On marque comme traité
        $this->signalementModel->markAsProcessed($id);

        // Actions supplémentaires
        if ($action === 'delete_annonce') {
            $this->annonceModel->deleteAnnouncement($report['idAnnonce']);
            Session::setFlash('success', 'Signalement traité et annonce supprimée.');
        } elseif ($action === 'shadowban_bailleur') {
            // On récupère le bailleur via l'annonce
            $annonce = $this->annonceModel->getAnnouncementById($report['idAnnonce']);
            if ($annonce) {
                $this->bailleurModel->toggleShadowban($annonce['idBailleur'], 1);
                Session::setFlash('success', 'Signalement traité et bailleur shadowbanni.');
            }
        } else {
            Session::setFlash('success', 'Signalement marqué comme traité.');
        }

        $this->redirect('/adminsignalement/index');
    }
}
