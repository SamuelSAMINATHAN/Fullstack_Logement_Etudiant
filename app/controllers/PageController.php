<?php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    private $informationLegaleModel;
    private $contactModel;
    private $faqModel;

    public function __construct()
    {
        $this->informationLegaleModel = $this->model('InformationLegaleModel');
        $this->contactModel = $this->model('ContactModel');
        $this->faqModel = $this->model('FaqModel');
    }

    /**
     * Affiche la page d'accueil
     */
    public function home()
    {
        $this->view('home/index');
    }

    /**
     * Affiche les CGU
     */
    public function cgu()
    {
        $cgu = $this->informationLegaleModel->getCGU();

        $data = [
            'information' => $cgu
        ];

        $this->view('info/cgu', $data);
    }

    /**
     * Affiche les mentions légales
     */
    public function mentionsLegales()
    {
        $mentions = $this->informationLegaleModel->getLegalNotice();

        $data = [
            'information' => $mentions
        ];

        $this->view('info/mentions_legales', $data);
    }

    /**
     * Affiche la politique de confidentialité
     */
    public function politiqueConfidentialite()
    {
        $politique = $this->informationLegaleModel->getPrivacyPolicy();

        $data = [
            'information' => $politique
        ];

        $this->view('info/politique_confidentialite', $data);
    }

    /**
     * Affiche la page FAQ
     */
    public function faq()
    {
        $faqs = $this->faqModel->getAllFAQs();

        $data = [
            'faqs' => $faqs
        ];

        $this->view('info/faq', $data);
    }

    /**
     * Affiche le formulaire de contact
     */
    public function contact()
    {
        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('info/contact', $data);
    }

    /**
     * Traite la soumission du formulaire de contact
     */
    public function contactHandler()
    {
        if (!$this->isPost()) {
            $this->redirect('/page/contact');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/page/contact');
        }

        $nom = $post['nom'] ?? '';
        $email = $post['email'] ?? '';
        $sujet = $post['sujet'] ?? '';
        $message = $post['message'] ?? '';

        // Valider les données
        if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            $this->setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/page/contact');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email invalide.');
            $this->redirect('/page/contact');
        }

        if (strlen($message) > 5000) {
            $this->setFlash('error', 'Le message est trop long (max 5000 caractères).');
            $this->redirect('/page/contact');
        }

        try {
            $data = [
                'nom' => $nom,
                'email' => $email,
                'sujet' => $sujet,
                'message' => $message,
                'traite' => 0
            ];

            $idContact = $this->contactModel->createMessage($data);

            if (!$idContact) {
                $this->setFlash('error', 'Erreur lors de l\'envoi du message.');
                $this->redirect('/page/contact');
            }

            // TODO: Envoyer un email de confirmation
            // sendEmail($email, 'Nous avons reçu votre message', ...);

            $this->setFlash('success', 'Votre message a été envoyé. Nous vous répondrons rapidement !');
            $this->redirect('/');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
            $this->redirect('/page/contact');
        }
    }
}
