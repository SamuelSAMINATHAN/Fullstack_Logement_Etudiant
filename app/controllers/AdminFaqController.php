<?php

namespace App\Controllers;

use App\Core\Controller;

class AdminFaqController extends Controller
{
    private $faqModel;

    public function __construct()
    {
        $this->faqModel = $this->model('FaqModel');
    }

    /**
     * Affiche la liste des FAQ
     */
    public function index()
    {
        $this->requireAdmin();

        $faqs = $this->faqModel->getAllFAQs();

        $data = [
            'faqs' => $faqs,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/faq_list', $data);
    }

    /**
     * Affiche le formulaire de création de FAQ
     */
    public function create()
    {
        $this->requireAdmin();

        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/faq_create', $data);
    }

    /**
     * Traite la création d'une FAQ
     */
    public function createHandler()
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('/admin/faq');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/faq/create');
        }

        $question = $post['question'] ?? '';
        $reponse = $post['reponse'] ?? '';

        if (empty($question) || empty($reponse)) {
            $this->setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/admin/faq/create');
        }

        try {
            $data = [
                'question' => $question,
                'reponse' => $reponse
            ];

            $idFAQ = $this->faqModel->createFAQ($data);

            if (!$idFAQ) {
                $this->setFlash('error', 'Erreur lors de la création de la FAQ.');
                $this->redirect('/admin/faq/create');
            }

            $this->setFlash('success', 'FAQ créée avec succès !');
            $this->redirect('/admin/faq');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la création : ' . $e->getMessage());
            $this->redirect('/admin/faq/create');
        }
    }

    /**
     * Affiche le formulaire d'édition de FAQ
     */
    public function edit($idFAQ = null)
    {
        $this->requireAdmin();

        if ($idFAQ === null) {
            $this->redirect('/admin/faq');
        }

        $faq = $this->faqModel->getFAQById($idFAQ);

        if (!$faq) {
            $this->setFlash('error', 'FAQ introuvable.');
            $this->redirect('/admin/faq');
        }

        $data = [
            'faq' => $faq,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/faq_edit', $data);
    }

    /**
     * Traite la modification d'une FAQ
     */
    public function editHandler($idFAQ = null)
    {
        $this->requireAdmin();

        if ($idFAQ === null || !$this->isPost()) {
            $this->redirect('/admin/faq');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/faq');
        }

        $faq = $this->faqModel->getFAQById($idFAQ);

        if (!$faq) {
            $this->setFlash('error', 'FAQ introuvable.');
            $this->redirect('/admin/faq');
        }

        $question = $post['question'] ?? '';
        $reponse = $post['reponse'] ?? '';

        if (empty($question) || empty($reponse)) {
            $this->setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/admin/faq/edit/' . $idFAQ);
        }

        try {
            $data = [
                'question' => $question,
                'reponse' => $reponse
            ];

            $this->faqModel->updateFAQ($idFAQ, $data);

            $this->setFlash('success', 'FAQ mise à jour avec succès !');
            $this->redirect('/admin/faq');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
            $this->redirect('/admin/faq/edit/' . $idFAQ);
        }
    }

    /**
     * Supprime une FAQ
     */
    public function delete($idFAQ = null)
    {
        $this->requireAdmin();

        if ($idFAQ === null) {
            $this->redirect('/admin/faq');
        }

        $faq = $this->faqModel->getFAQById($idFAQ);

        if (!$faq) {
            $this->setFlash('error', 'FAQ introuvable.');
            $this->redirect('/admin/faq');
        }

        try {
            $this->faqModel->deleteFAQ($idFAQ);
            $this->setFlash('success', 'FAQ supprimée.');
            $this->redirect('/admin/faq');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/admin/faq');
        }
    }
}
