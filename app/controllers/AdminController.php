<?php

namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller
{
    private $adminModel;
    private $utilisateurModel;
    private $bailleurModel;
    private $annonceModel;
    private $signalementModel;

    public function __construct()
    {
        $this->adminModel = $this->model('AdminModel');
        $this->utilisateurModel = $this->model('UtilisateurModel');
        $this->bailleurModel = $this->model('BailleurModel');
        $this->annonceModel = $this->model('AnnonceModel');
        $this->signalementModel = $this->model('SignalementModel');
    }

    /**
     * Affiche la page d'accueil du backoffice admin
     */
    public function dashboard()
    {
        $this->requireAdmin();

        // Statistiques
        $totalUsers = count($this->utilisateurModel->getAllUsers());
        $totalAnnonces = count($this->annonceModel->getAllAnnouncements());
        $pendingReports = $this->signalementModel->countPendingReports();

        $data = [
            'total_users' => $totalUsers,
            'total_annonces' => $totalAnnonces,
            'pending_reports' => $pendingReports
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Gère les utilisateurs
     */
    public function manageUsers()
    {
        $this->requireAdmin();

        $users = $this->utilisateurModel->getAllUsers();

        $data = [
            'users' => $users,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/users_manage', $data);
    }

    /**
     * Affiche les détails d'un utilisateur
     */
    public function viewUser($idUtilisateur = null)
    {
        $this->requireAdmin();

        if ($idUtilisateur === null) {
            $this->redirect('/admin/manageUsers');
        }

        $user = $this->utilisateurModel->getUserById($idUtilisateur);

        if (!$user) {
            $this->setFlash('error', 'Utilisateur introuvable.');
            $this->redirect('/admin/manageUsers');
        }

        $data = [
            'user' => $user,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/user_detail', $data);
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser($idUtilisateur = null)
    {
        $this->requireAdmin();

        if ($idUtilisateur === null || !$this->isPost()) {
            $this->redirect('/admin/manageUsers');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/manageUsers');
        }

        $user = $this->utilisateurModel->getUserById($idUtilisateur);

        if (!$user) {
            $this->setFlash('error', 'Utilisateur introuvable.');
            $this->redirect('/admin/manageUsers');
        }

        try {
            $this->utilisateurModel->deleteUser($idUtilisateur);
            $this->setFlash('success', 'Utilisateur supprimé.');
            $this->redirect('/admin/manageUsers');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/admin/viewUser/' . $idUtilisateur);
        }
    }

    /**
     * Vérifie un bailleur
     */
    public function verifyLandlord($idUtilisateur = null)
    {
        $this->requireAdmin();

        if ($idUtilisateur === null) {
            $this->redirect('/admin/manageUsers');
        }

        try {
            $this->bailleurModel->verifyLandlord($idUtilisateur);
            $this->setFlash('success', 'Bailleur vérifié.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/admin/manageUsers');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la vérification.');
            $this->redirect('/admin/manageUsers');
        }
    }

    /**
     * Retire la vérification d'un bailleur
     */
    public function unverifyLandlord($idUtilisateur = null)
    {
        $this->requireAdmin();

        if ($idUtilisateur === null) {
            $this->redirect('/admin/manageUsers');
        }

        try {
            $this->bailleurModel->unverifyLandlord($idUtilisateur);
            $this->setFlash('success', 'Vérification retirée.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/admin/manageUsers');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la modification.');
            $this->redirect('/admin/manageUsers');
        }
    }

    /**
     * Shadowban un bailleur
     */
    public function shadowbanLandlord($idUtilisateur = null)
    {
        $this->requireAdmin();

        if ($idUtilisateur === null) {
            $this->redirect('/admin/manageUsers');
        }

        try {
            $this->bailleurModel->shadowbanLandlord($idUtilisateur);
            $this->setFlash('success', 'Bailleur shadowbanni.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/admin/manageUsers');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors du shadowban.');
            $this->redirect('/admin/manageUsers');
        }
    }

    /**
     * Retire le shadowban d'un bailleur
     */
    public function unbanLandlord($idUtilisateur = null)
    {
        $this->requireAdmin();

        if ($idUtilisateur === null) {
            $this->redirect('/admin/manageUsers');
        }

        try {
            $this->bailleurModel->unbanLandlord($idUtilisateur);
            $this->setFlash('success', 'Shadowban retiré.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/admin/manageUsers');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors du retrait du shadowban.');
            $this->redirect('/admin/manageUsers');
        }
    }

    /**
     * Gère l'apparence (contenu du site)
     */
    public function appearance()
    {
        $this->requireAdmin();

        $this->view('admin/appearance');
    }
}
