<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class AdminauthController extends Controller
{
    private $adminModel;

    public function __construct()
    {
        // Session::start() n'est pas requis ici car session_start() est déjà dans ton index.php
        $this->adminModel = $this->model('AdminModel');
    }

    /**
     * Action par défaut appelée par ton routeur index.php
     */
    public function home()
    {
        $this->login();
    }

    /**
     * Affiche le formulaire de connexion admin
     */
    public function login()
    {
        if ($this->isAdminLoggedIn()) {
            $this->redirect('/admin/dashboard');
        }

        // Utilise la méthode de ton Controller parent pour charger la vue
        $this->view('admin/login');
    }

    /**
     * Gère la soumission du formulaire de connexion
     */
    public function authenticate()
    {
        if (!$this->isPost()) {
            $this->redirect('/adminauth/login');
        }

        // Protection Brute-force basée sur TES sessions
        if ($this->isBruteForce()) {
            $this->setFlash('error', 'Trop de tentatives. Veuillez patienter 5 minutes.');
            $this->redirect('/adminauth/login');
        }

        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            $this->setFlash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('/adminauth/login');
        }

        // Récupération de l'admin
        $admin = $this->adminModel->getOneAdminByLogin($login);
        
        // UTILISATION DE TA CLASSE SECURITY EXISTANTE !
        if ($admin && Security::verifyPassword($password, $admin['motDePasse'])) {
            
            // Connexion réussie : On stocke dans $_SESSION de manière classique
            $_SESSION['admin_id'] = $admin['idAdmin'];
            $_SESSION['admin_name'] = $admin['nom'];
            $_SESSION['admin_login'] = $admin['login'];
            
            // Utilisation de TA méthode de régénération de session
            Session::regenerate();
            $this->resetBruteForce();
            
            $this->setFlash('success', 'Bienvenue dans le backoffice, ' . $admin['nom']);
            $this->redirect('/admin/dashboard');
        } else {
            // Échec de connexion
            $this->registerAttempt();
            $this->setFlash('error', 'Login ou mot de passe incorrect.');
            $this->redirect('/adminauth/login');
        }
    }

    /**
     * Déconnecte l'administrateur
     */
    public function logout()
    {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_login']);
        
        $this->setFlash('success', 'Vous avez été déconnecté.');
        $this->redirect('/adminauth/login');
    }

    /**
     * Gestion du brute-force privée
     */
    private function isBruteForce()
    {
        $maxAttempts = 5;
        $lockTime = 300; // 5 minutes

        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxAttempts) {
            $timeSinceLastAttempt = time() - $_SESSION['last_attempt_time'];
            if ($timeSinceLastAttempt < $lockTime) {
                return true;
            } else {
                $this->resetBruteForce();
            }
        }
        return false;
    }

    private function registerAttempt()
    {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['last_attempt_time'] = time();
    }

    private function resetBruteForce()
    {
        unset($_SESSION['login_attempts']);
        unset($_SESSION['last_attempt_time']);
    }
}