<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class AdminController extends Controller
{
    private $adminModel;
    private $utilisateurModel;
    private $bailleurModel;
    private $annonceModel;
    private $signalementModel;
    private $infoModel;

    public function __construct()
    {
        $this->adminModel = $this->model('AdminModel');
        $this->utilisateurModel = $this->model('UtilisateurModel');
        $this->bailleurModel = $this->model('BailleurModel');
        $this->annonceModel = $this->model('AnnonceModel');
        $this->signalementModel = $this->model('SignalementModel');
        $this->infoModel = $this->model('InformationLegaleModel');
    }

    /**
     * Affiche la page d'accueil du backoffice admin
     */
    public function home()
    {
        $this->redirect('/admin/dashboard');
    }

    /**
     * Affiche la page d'accueil du backoffice admin
     */
    public function dashboard()
    {
        $this->requireAdmin();

        $data = [
            'stats' => [
                'users_count' => count($this->utilisateurModel->getAllUsersWithDetails()),
                'annonces_count' => count($this->annonceModel->getAllAnnouncements()),
                'signalements_count' => $this->signalementModel->countPendingReports(),
            ],
            'recent_users' => $this->utilisateurModel->getAllUsersWithDetails()
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Gestion des utilisateurs
     */
    public function users()
    {
        $this->requireAdmin();
        $users = $this->utilisateurModel->getAllUsersWithDetails();
        $this->view('admin/users/index', ['users' => $users]);
    }

    /**
     * Vérification des bailleurs
     */
    public function verifyBailleurs()
    {
        $this->requireAdmin();
        $bailleurs = $this->utilisateurModel->getBailleursToVerify();
        $this->view('admin/users/verify_bailleurs', ['bailleurs' => $bailleurs]);
    }

    /**
     * Action : Vérifier un bailleur
     */
    public function verify($id)
    {
        $this->requireAdmin();
        if ($this->bailleurModel->verifyLandlord($id)) {
            Session::setFlash('success', 'Bailleur vérifié avec succès.');
        } else {
            Session::setFlash('error', 'Erreur lors de la vérification.');
        }
        $this->redirect('/admin/users');
    }

    /**
     * Action : Shadowban un bailleur
     */
    public function shadowban($id)
    {
        $this->requireAdmin();
        $status = isset($_GET['status']) ? (int)$_GET['status'] : 1;
        if ($this->bailleurModel->toggleShadowban($id, $status)) {
            $msg = $status ? 'Bailleur shadowbanni.' : 'Shadowban retiré.';
            Session::setFlash('success', $msg);
        } else {
            Session::setFlash('error', 'Erreur lors de l\'opération.');
        }
        $this->redirect('/admin/users');
    }

    /**
     * Action : Changer le rôle
     */
    public function changeRole($id)
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect('/admin/users');
        }

        $newRole = $_POST['role'] ?? '';
        if (in_array($newRole, ['etudiant', 'bailleur'])) {
            if ($this->utilisateurModel->updateUser($id, ['role' => $newRole])) {
                Session::setFlash('success', 'Rôle mis à jour.');
            } else {
                Session::setFlash('error', 'Erreur lors de la mise à jour.');
            }
        }
        $this->redirect('/admin/users');
    }

    /**
     * Action : Supprimer un utilisateur
     */
    public function deleteUser($id = null)
    {
        $this->requireAdmin();
        if ($id === null) {
            $this->redirect('/admin/users');
        }

        if ($this->utilisateurModel->deleteUser($id)) {
            Session::setFlash('success', 'Utilisateur supprimé.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }
        $this->redirect('/admin/users');
    }

    /**
     * Gestion du contenu légal
     */
    public function legal()
    {
        $this->requireAdmin();
        $infos = $this->infoModel->getAllInformation();
        $this->view('admin/content/legal', ['infos' => $infos]);
    }

    /**
     * Édition du contenu légal
     */
    public function editLegal($id)
    {
        $this->requireAdmin();
        $info = $this->infoModel->getInformationById($id);
        if (!$info) {
            $this->redirect('/admin/legal');
        }

        if ($this->isPost()) {
            $data = [
                'contenu' => $_POST['contenu'] ?? '',
                'dateMiseAJour' => date('Y-m-d H:i:s')
            ];

            if ($this->infoModel->updateInformation($id, $data)) {
                Session::setFlash('success', 'Information légale mise à jour.');
                $this->redirect('/admin/legal');
            }
        }
        $this->view('admin/content/legal_form', ['info' => $info]);
    }
}
