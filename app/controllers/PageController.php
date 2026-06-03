<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

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

        if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            $this->setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/page/contact');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'L\'email n\'est pas valide.');
            $this->redirect('/page/contact');
        }

        try {
            $data = [
                'nom' => $nom,
                'email' => $email,
                'sujet' => $sujet,
                'message' => $message,
                'dateEnvoi' => date('Y-m-d H:i:s')
            ];

            if ($this->contactModel->createMessage($data)) {
                $this->setFlash('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
            } else {
                $this->setFlash('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }
            $this->redirect('/page/contact');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur : ' . $e->getMessage());
            $this->redirect('/page/contact');
        }
    }
}
