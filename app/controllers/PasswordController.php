<?php

class PasswordController extends Controller
{
    private $utilisateurModel;
    private $resetPasswordModel;

    public function __construct()
    {
        $this->utilisateurModel = $this->model('UtilisateurModel');
        $this->resetPasswordModel = $this->model('ResetPasswordModel');
    }

    /**
     * Affiche la page de demande de réinitialisation
     */
    public function forgot()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('auth/forgot_password', $data);
    }

    /**
     * Traite la demande de réinitialisation
     */
    public function forgotHandler()
    {
        if (!$this->isPost()) {
            $this->redirect('/password/forgot');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/password/forgot');
        }

        $email = $post['email'] ?? '';

        if (empty($email)) {
            $this->setFlash('error', 'Email requis.');
            $this->redirect('/password/forgot');
        }

        $user = $this->utilisateurModel->getUserByEmail($email);

        if (!$user) {
            // Message générique pour ne pas révéler l'existence d'un email
            $this->setFlash('success', 'Si cet email existe, un lien de réinitialisation a été envoyé.');
            $this->redirect('/password/forgot');
        }

        // Générer un token
        $token = Security::generateToken(32);
        $this->resetPasswordModel->createToken($user['idUtilisateur'], $token, 60);

        // TODO: Envoyer l'email avec le lien
        // $resetLink = URLROOT . '/password/reset?token=' . $token;
        // sendEmail($email, 'Réinitialiser votre mot de passe', ...);

        $this->setFlash('success', 'Si cet email existe, un lien de réinitialisation a été envoyé.');
        $this->redirect('/password/forgot');
    }

    /**
     * Affiche la page de réinitialisation
     */
    public function reset()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $this->setFlash('error', 'Token manquant.');
            $this->redirect('/password/forgot');
        }

        if (!$this->resetPasswordModel->isTokenValid($token)) {
            $this->setFlash('error', 'Token invalide ou expiré.');
            $this->redirect('/password/forgot');
        }

        $data = [
            'token' => $token,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('auth/reset_password', $data);
    }

    /**
     * Traite la réinitialisation du mot de passe
     */
    public function resetHandler()
    {
        if (!$this->isPost()) {
            $this->redirect('/password/forgot');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/password/forgot');
        }

        $token = $post['token'] ?? '';
        $password = $post['password'] ?? '';
        $password_confirm = $post['password_confirm'] ?? '';

        if (empty($token)) {
            $this->setFlash('error', 'Token manquant.');
            $this->redirect('/password/forgot');
        }

        if (!$this->resetPasswordModel->isTokenValid($token)) {
            $this->setFlash('error', 'Token invalide ou expiré.');
            $this->redirect('/password/forgot');
        }

        if (empty($password) || empty($password_confirm)) {
            $this->setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/password/reset?token=' . $token);
        }

        if ($password !== $password_confirm) {
            $this->setFlash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('/password/reset?token=' . $token);
        }

        if (strlen($password) < 8) {
            $this->setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->redirect('/password/reset?token=' . $token);
        }

        try {
            $user = $this->resetPasswordModel->getUserByToken($token);

            if (!$user) {
                $this->setFlash('error', 'Utilisateur introuvable.');
                $this->redirect('/password/forgot');
            }

            // Mettre à jour le mot de passe
            $this->utilisateurModel->updateUser($user['idUtilisateur'], ['mdp' => $password]);

            // Marquer le token comme utilisé
            $this->resetPasswordModel->markTokenAsUsed($token);

            $this->setFlash('success', 'Mot de passe réinitialisé avec succès !');
            $this->redirect('/auth/login');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la réinitialisation.');
            $this->redirect('/password/forgot');
        }
    }
}
