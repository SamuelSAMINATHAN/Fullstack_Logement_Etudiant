<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class AdminfaqController extends Controller
{
    private $faqModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->faqModel = $this->model('FaqModel');
    }

    /**
     * Affiche la liste des FAQ
     */
    public function index()
    {
        $faqs = $this->faqModel->getAllFAQs();
        $this->view('admin/content/faq', ['faqs' => $faqs]);
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $this->view('admin/content/faq_form');
    }

    /**
     * Traite la création
     */
    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('/adminfaq/index');
        }

        $data = [
            'question' => $_POST['question'] ?? '',
            'reponse' => $_POST['reponse'] ?? ''
        ];

        if (empty($data['question']) || empty($data['reponse'])) {
            Session::setFlash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/adminfaq/create');
        }

        if ($this->faqModel->createFAQ($data)) {
            Session::setFlash('success', 'FAQ créée avec succès.');
            $this->redirect('/adminfaq/index');
        } else {
            Session::setFlash('error', 'Erreur lors de la création.');
            $this->redirect('/adminfaq/create');
        }
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $faq = $this->faqModel->getFAQById($id);
        if (!$faq) {
            $this->redirect('/adminfaq/index');
        }
        $this->view('admin/content/faq_form', ['faq' => $faq]);
    }

    /**
     * Traite la mise à jour
     */
    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/adminfaq/index');
        }

        $data = [
            'question' => $_POST['question'] ?? '',
            'reponse' => $_POST['reponse'] ?? ''
        ];

        if ($this->faqModel->updateFAQ($id, $data)) {
            Session::setFlash('success', 'FAQ mise à jour.');
            $this->redirect('/adminfaq/index');
        } else {
            Session::setFlash('error', 'Erreur lors de la mise à jour.');
            $this->redirect('/adminfaq/edit/' . $id);
        }
    }

    /**
     * Suppression
     */
    public function delete($id)
    {
        if ($this->faqModel->deleteFAQ($id)) {
            Session::setFlash('success', 'FAQ supprimée.');
        } else {
            Session::setFlash('error', 'Erreur lors de la suppression.');
        }
        $this->redirect('/adminfaq/index');
    }
}
