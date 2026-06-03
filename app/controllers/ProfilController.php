<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class ProfilController extends Controller
{
    private $utilisateurModel;
    private $etudiantModel;
    private $bailleurModel;

    public function __construct()
    {
        $this->utilisateurModel = $this->model('UtilisateurModel');
        $this->etudiantModel = $this->model('EtudiantModel');
        $this->bailleurModel = $this->model('BailleurModel');
    }

    /**
     * Affiche le profil utilisateur
     */
    public function profile()
    {
        $this->requireAuth();

        $user = $this->utilisateurModel->getUserById($_SESSION['user_id']);
        if (!$user) {
            Session::destroy();
            $this->redirect('/auth/login');
        }

        $data = [
            'user' => $user
        ];

        if ($user['role'] === 'etudiant') {
            $data['etudiant'] = $this->etudiantModel->getStudentById($_SESSION['user_id']);
        }

        $this->view('user/profile', $data);
    }

    /**
     * Affiche le dashboard
     */
    public function dashboard()
    {
        $this->requireAuth();

        $user = $this->utilisateurModel->getUserById($_SESSION['user_id']);
        if (!$user) {
            Session::destroy();
            $this->redirect('/auth/login');
        }

        $data = [
            'user' => $user
        ];

        if ($user['role'] === 'etudiant') {
            $data['etudiant'] = $this->etudiantModel->getStudentById($_SESSION['user_id']);
        } else {
            $data['bailleur'] = $this->bailleurModel->getLandlordById($_SESSION['user_id']);
        }

        $this->view('user/dashboard', $data);
    }

    /**
     * Met à jour le profil utilisateur
     */
    public function update()
    {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/profil/profile');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/profil/profile');
        }

        $nom = $post['nom'] ?? '';
        $prenom = $post['prenom'] ?? '';

        if (empty($nom) || empty($prenom)) {
            $this->setFlash('error', 'Le nom et le prénom sont obligatoires.');
            $this->redirect('/profil/profile');
        }

        try {
            $userData = [
                'nom' => $nom,
                'prenom' => $prenom
            ];

            if ($_SESSION['user_role'] === 'etudiant') {
                $studentData = [
                    'dateNaissance' => !empty($post['dateNaissance']) ? $post['dateNaissance'] : null,
                    'localisation' => !empty($post['localisation']) ? $post['localisation'] : null
                ];
                $this->etudiantModel->updateStudent($_SESSION['user_id'], $studentData);
            }

            $this->utilisateurModel->updateUser($_SESSION['user_id'], $userData);

            $_SESSION['user_nom'] = $nom;
            $_SESSION['user_prenom'] = $prenom;

            $this->setFlash('success', 'Profil mis à jour avec succès !');
            $this->redirect('/profil/profile');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
            $this->redirect('/profil/profile');
        }
    }

    /**
     * Change le mot de passe
     */
    public function changePassword()
    {
        $this->requireAuth();
        $this->view('user/change_password');
    }

    /**
     * Traite le changement de mot de passe
     */
    public function changePasswordHandler()
    {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/profil/changePassword');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/profil/changePassword');
        }

        $currentPassword = $post['current_password'] ?? '';
        $newPassword = $post['new_password'] ?? '';
        $confirmPassword = $post['password_confirm'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/profil/changePassword');
        }

        $user = $this->utilisateurModel->getUserById($_SESSION['user_id']);

        if (!Security::verifyPassword($currentPassword, $user['mdp'])) {
            $this->setFlash('error', 'Le mot de passe actuel est incorrect.');
            $this->redirect('/profil/changePassword');
        }

        if ($newPassword !== $confirmPassword) {
            $this->setFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
            $this->redirect('/profil/changePassword');
        }

        if (strlen($newPassword) < 8) {
            $this->setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->redirect('/profil/changePassword');
        }

        try {
            $this->utilisateurModel->updateUser($_SESSION['user_id'], ['mdp' => $newPassword]);
            $this->setFlash('success', 'Mot de passe changé avec succès !');
            $this->redirect('/profil/profile');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors du changement : ' . $e->getMessage());
            $this->redirect('/profil/changePassword');
        }
    }

    /**
     * Demande la suppression du compte
     */
    public function requestDeletion()
    {
        $this->requireAuth();
        $this->view('user/request_deletion');
    }

    /**
     * Traite la demande de suppression
     */
    public function requestDeletionHandler()
    {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/profil/requestDeletion');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/profil/requestDeletion');
        }

        try {
            $this->utilisateurModel->requestAccountDeletion($_SESSION['user_id']);
            $this->setFlash('success', 'Demande de suppression enregistrée. Vous allez être déconnecté.');
            Session::destroy();
            $this->redirect('/');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la demande.');
            $this->redirect('/profil/requestDeletion');
        }
    }
}
