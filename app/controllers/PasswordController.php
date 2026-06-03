<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;
use App\Core\MailService;

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
            Session::setFlash('success', 'Si cet email existe, un lien de réinitialisation a été envoyé.');
            $this->redirect('/password/forgot');
        }

        // Générer un code à 6 chiffres
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Stocker le code dans la table reset_password (on réutilise le champ token pour stocker le code)
        $this->resetPasswordModel->createToken($user['idUtilisateur'], $code, 60);

        // Préparation de l'envoi de l'email
        if (MailService::sendPasswordResetCode($email, $code)) {
            Session::setFlash('success', 'Un code de vérification a été envoyé à votre adresse email.');
            $this->redirect('/password/verify');
        } else {
            // En cas d'échec d'envoi, on affiche quand même le code en mode DEBUG
            if (DEBUG) {
                Session::setFlash('info', 'Échec d\'envoi. Code DEBUG : <strong>' . $code . '</strong>');
            }
            Session::setFlash('error', 'Une erreur est survenue lors de l\'envoi du code. Veuillez réessayer plus tard.');
            $this->redirect('/password/forgot');
        }
    }

    /**
     * Affiche la page de saisie du code
     */
    public function verify()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('auth/verify_code', $data);
    }

    /**
     * Traite la vérification du code
     */
    public function verifyHandler()
    {
        if (!$this->isPost()) {
            $this->redirect('/password/forgot');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            Session::setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/password/forgot');
        }

        $code = trim($post['code'] ?? '');

        if (empty($code)) {
            Session::setFlash('error', 'Le code est obligatoire.');
            $this->redirect('/password/verify');
        }

        if (!$this->resetPasswordModel->isTokenValid($code)) {
            Session::setFlash('error', 'Code invalide ou expiré.');
            $this->redirect('/password/verify');
        }

        // Le code est valide, on redirige vers la page de changement de MDP avec le code en paramètre
        $this->redirect('/password/reset?token=' . $code);
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
            Session::setFlash('error', 'Token manquant.');
            $this->redirect('/password/forgot');
        }

        if (!$this->resetPasswordModel->isTokenValid($token)) {
            Session::setFlash('error', 'Token invalide ou expiré.');
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
            Session::setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/password/forgot');
        }

        $token = $post['token'] ?? '';
        $password = $post['password'] ?? '';
        $password_confirm = $post['password_confirm'] ?? '';

        if (empty($token)) {
            Session::setFlash('error', 'Token manquant.');
            $this->redirect('/password/forgot');
        }

        if (!$this->resetPasswordModel->isTokenValid($token)) {
            Session::setFlash('error', 'Token invalide ou expiré.');
            $this->redirect('/password/forgot');
        }

        if (empty($password) || empty($password_confirm)) {
            Session::setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/password/reset?token=' . $token);
        }

        if ($password !== $password_confirm) {
            Session::setFlash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('/password/reset?token=' . $token);
        }

        if (strlen($password) < 8) {
            Session::setFlash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->redirect('/password/reset?token=' . $token);
        }

        try {
            $user = $this->resetPasswordModel->getUserByToken($token);

            if (!$user) {
                Session::setFlash('error', 'Utilisateur introuvable.');
                $this->redirect('/password/forgot');
            }

            // Mettre à jour le mot de passe
            $this->utilisateurModel->updateUser($user['idUtilisateur'], ['mdp' => $password]);

            // Marquer le token comme utilisé
            $this->resetPasswordModel->markTokenAsUsed($token);

            Session::setFlash('success', 'Mot de passe réinitialisé avec succès !');
            $this->redirect('/auth/login');
        } catch (\Exception $e) {
            Session::setFlash('error', 'Erreur lors de la réinitialisation.');
            $this->redirect('/password/forgot');
        }
    }
}
