<?php

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
        $this->utilisateurModel->updateLastLogin($user['idUtilisateur']);

        // Créer la session
        Session::regenerate();
        Session::set('user_id', $user['idUtilisateur']);
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
    public function register()
    {
        // Rediriger si déjà connecté
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('auth/register', $data);
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
        $nom = $post['nom'] ?? '';
        $prenom = $post['prenom'] ?? '';
        $email = $post['email'] ?? '';
        $password = $post['password'] ?? '';
        $password_confirm = $post['password_confirm'] ?? '';
        $role = $post['role'] ?? '';
        $dateNaissance = $post['dateNaissance'] ?? null;
        $localisation = $post['localisation'] ?? null;

        // Valider les données obligatoires
        if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($role)) {
            $this->setFlash('error', 'Tous les champs obligatoires doivent être remplis.');
            $this->redirect('/auth/register');
        }

        // Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email invalide.');
            $this->redirect('/auth/register');
        }

        // Valider le rôle
        if (!in_array($role, ['etudiant', 'bailleur'])) {
            $this->setFlash('error', 'Rôle invalide.');
            $this->redirect('/auth/register');
        }

        // Vérifier la correspondance des mots de passe
        if ($password !== $password_confirm) {
            $this->setFlash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('/auth/register');
        }

        // Vérifier la longueur du mot de passe
        if (strlen($password) < 8) {
            $this->setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->redirect('/auth/register');
        }

        // Vérifier l'unicité de l'email
        if (!$this->utilisateurModel->isEmailUnique($email)) {
            $this->setFlash('error', 'Cet email est déjà utilisé.');
            $this->redirect('/auth/register');
        }

        // Préparer les données utilisateur
        $userData = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mdp' => $password,
            'role' => $role,
            'date_acceptation_cgu' => date('Y-m-d H:i:s')
        ];

        // Enregistrer selon le rôle
        try {
            if ($role === 'etudiant') {
                $studentData = [
                    'dateNaissance' => $dateNaissance ?: null,
                    'localisation' => $localisation ?: null
                ];
                $userId = $this->etudiantModel->registerStudent($userData, $studentData);
            } else {
                $userId = $this->bailleurModel->registerLandlord($userData);
            }

            if (!$userId) {
                $this->setFlash('error', 'Erreur lors de l\'inscription.');
                $this->redirect('/auth/register');
            }

            $this->setFlash('success', 'Inscription réussie ! Veuillez vous connecter.');
            $this->redirect('/auth/login');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de l\'inscription : ' . $e->getMessage());
            $this->redirect('/auth/register');
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
