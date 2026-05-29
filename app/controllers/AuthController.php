<?php

namespace App\Controllers;

use App\Core\Security;
use App\Core\Session;
use App\Core\Controller;

class AuthController extends Controller
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
     * Affiche la page de connexion
     */
    public function login()
    {
        // Rediriger si déjà connecté
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('auth/login', $data);
    }

    /**
     * Traite la soumission du formulaire de connexion
     */
    public function loginHandler()
    {
        if (!$this->isPost()) {
            $this->redirect('/auth/login');
        }

        $post = $this->sanitizePost();

        // Vérifier le token CSRF
        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/auth/login');
        }

        // Valider les données
        $email = $post['email'] ?? '';
        $password = $post['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Email et mot de passe requis.');
            $this->redirect('/auth/login');
        }

        // Récupérer l'utilisateur par email
        $user = $this->utilisateurModel->getUserByEmail($email);
        if (is_array($user) && isset($user[0])) {
        $user = $user[0];
}
        if (!$user || !Security::verifyPassword($password, $user['mdp'])) {
            $this->setFlash('error', 'Email ou mot de passe incorrect.');
            $this->redirect('/auth/login');
        }

        // Vérifier que le compte n'a pas demandé la suppression
        if ($user['demande_suppression']) {
            $this->setFlash('error', 'Ce compte a demandé la suppression.');
            $this->redirect('/auth/login');
        }

        // Mettre à jour la dernière connexion
        $this->utilisateurModel->updateLastLogin($user['id'] ?? $user['idUtilisateur']);

        // Créer la session
        Session::regenerate();
        Session::set('user_id', $user['id'] ?? $user['idUtilisateur']);
        Session::set('user_email', $user['email']);
        Session::set('user_nom', $user['nom']);
        Session::set('user_prenom', $user['prenom']);
        Session::set('user_role', $user['role']);

        $this->setFlash('success', 'Connexion réussie !');
        $this->redirect('/');
    }

    /**
     * Affiche la page d'inscription
     */
    public function register($type = null)
    {
        // 1. Si aucun type n'est choisi, on affiche la page de choix
        if ($type === null) {
            $this->view('auth/select_type');
            return;
        }

        // 2. On prépare les données communes (CSRF)
        $data = ['csrf_token' => Security::csrfToken()];

        // 3. On affiche le formulaire spécifique
        if ($type === 'etudiant') {
            $this->view('auth/register_etudiant', $data);
        } elseif ($type === 'bailleur') {
            $this->view('auth/register_bailleur', $data);
        } else {
            $this->redirect('/auth/register');
        }
    }

    /**
     * Traite la soumission du formulaire d'inscription
     */
    public function registerHandler()
    {
        if (!$this->isPost()) {
            $this->redirect('/auth/register');
        }

        $post = $this->sanitizePost();

        // Vérifier le token CSRF
        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/auth/register');
        }

        // Récupérer les données
        $nom = trim($post['nom'] ?? '');
        $prenom = trim($post['prenom'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';
        $password_confirm = $post['password_confirm'] ?? '';
        $role = $post['role'] ?? '';
        $dateNaissance = $post['dateNaissance'] ?? null;
        $localisation = trim($post['localisation'] ?? '');

        // Valider le rôle
        if (!in_array($role, ['etudiant', 'bailleur'])) {
            $this->setFlash('error', 'Rôle invalide.');
            $this->redirect('/auth/register');
        }

        // Déterminer l'URL de redirection en cas d'erreur
        $redirectUrl = '/auth/register/' . $role;

        // Valider les données obligatoires
        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $this->setFlash('error', 'Tous les champs obligatoires doivent être remplis.');
            $this->redirect($redirectUrl);
        }

        // Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email invalide.');
            $this->redirect($redirectUrl);
        }

        // Vérifier la correspondance des mots de passe
        if ($password !== $password_confirm) {
            $this->setFlash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect($redirectUrl);
        }

        // Vérifier la longueur du mot de passe
        if (strlen($password) < 8) {
            $this->setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->redirect($redirectUrl);
        }

        // Valider les champs spécifiques aux étudiants
        if ($role === 'etudiant') {
            if (empty($dateNaissance)) {
                $this->setFlash('error', 'La date de naissance est obligatoire.');
                $this->redirect($redirectUrl);
            }
            if (empty($localisation)) {
                $this->setFlash('error', 'La localisation est obligatoire.');
                $this->redirect($redirectUrl);
            }
        }

        // Vérifier l'unicité de l'email
        if (!$this->utilisateurModel->isEmailUnique($email)) {
            $this->setFlash('error', 'Cet email est déjà utilisé.');
            $this->redirect($redirectUrl);
        }

        // Préparer les données utilisateur avec les bons noms de colonnes
        $userData = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mdp' => $password,
            'role' => $role,
            'date_acceptation_cgu' => date('Y-m-d H:i:s')
        ];

        // Enregistrer selon le rôle avec gestion d'exception robuste
        try {
            if ($role === 'etudiant') {
                // Valider le format de la date
                $dateObj = \DateTime::createFromFormat('Y-m-d', $dateNaissance);
                if (!$dateObj || $dateObj->format('Y-m-d') !== $dateNaissance) {
                    $this->setFlash('error', 'Format de date de naissance invalide.');
                    $this->redirect($redirectUrl);
                }
                
                $studentData = [
                    'dateNaissance' => $dateNaissance,
                    'localisation' => $localisation
                ];
                $userId = $this->etudiantModel->registerStudent($userData, $studentData);
            } else {
                $userId = $this->bailleurModel->registerLandlord($userData);
            }

            if (!$userId) {
                $this->setFlash('error', 'Erreur lors de l\'inscription. Veuillez réessayer.');
                $this->redirect($redirectUrl);
            }

            $this->setFlash('success', 'Inscription réussie ! Veuillez vous connecter.');
            $this->redirect('/auth/login');
        } catch (\PDOException $e) {
            // Gérer les erreurs SQL spécifiques
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $this->setFlash('error', 'Cet email est déjà utilisé.');
            } elseif (strpos($e->getMessage(), 'NOT NULL') !== false) {
                $this->setFlash('error', 'Un champ obligatoire est manquant.');
            } else {
                $this->setFlash('error', 'Erreur base de données : ' . $e->getMessage());
            }
            $this->redirect($redirectUrl);
        } catch (\Exception $e) {
            error_log('Erreur inscription: ' . $e->getMessage());
            $this->setFlash('error', 'Erreur lors de l\'inscription. Veuillez contacter le support si le problème persiste.');
            $this->redirect($redirectUrl);
        }
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        Session::destroy();
        $this->setFlash('success', 'Vous avez été déconnecté.');
        $this->redirect('/');
    }
}
