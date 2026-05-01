<?php

class AdminContactController extends Controller
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');
    }

    /**
     * Affiche la liste des messages de contact
     */
    public function index()
    {
        $this->requireAdmin();

        $messages = $this->contactModel->getAllMessages();

        $data = [
            'messages' => $messages,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/contact_list', $data);
    }

    /**
     * Affiche les messages non traités
     */
    public function untreated()
    {
        $this->requireAdmin();

        $messages = $this->contactModel->getUntreatedMessages();

        $data = [
            'messages' => $messages,
            'status' => 'untreated'
        ];

        $this->view('admin/contact_list', $data);
    }

    /**
     * Affiche un message de contact
     */
    public function view($idContact = null)
    {
        $this->requireAdmin();

        if ($idContact === null) {
            $this->redirect('/admin/contact');
        }

        $message = $this->contactModel->getMessageById($idContact);

        if (!$message) {
            $this->setFlash('error', 'Message introuvable.');
            $this->redirect('/admin/contact');
        }

        // Marquer comme traité si pas déjà fait
        if (!$message['traite']) {
            $this->contactModel->markAsProcessed($idContact);
        }

        $data = [
            'message' => $message,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('admin/contact_view', $data);
    }

    /**
     * Marque un message comme traité
     */
    public function markAsProcessed($idContact = null)
    {
        $this->requireAdmin();

        if ($idContact === null || !$this->isPost()) {
            $this->redirect('/admin/contact');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/admin/contact');
        }

        $message = $this->contactModel->getMessageById($idContact);

        if (!$message) {
            $this->setFlash('error', 'Message introuvable.');
            $this->redirect('/admin/contact');
        }

        try {
            $this->contactModel->markAsProcessed($idContact);
            $this->setFlash('success', 'Message marqué comme traité.');
            $this->redirect('/admin/contact/view/' . $idContact);
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors du traitement.');
            $this->redirect('/admin/contact');
        }
    }

    /**
     * Marque un message comme non traité
     */
    public function markAsUntreated($idContact = null)
    {
        $this->requireAdmin();

        if ($idContact === null) {
            $this->redirect('/admin/contact');
        }

        $message = $this->contactModel->getMessageById($idContact);

        if (!$message) {
            $this->setFlash('error', 'Message introuvable.');
            $this->redirect('/admin/contact');
        }

        try {
            $this->contactModel->markAsUntreated($idContact);
            $this->setFlash('success', 'Message marqué comme non traité.');
            $this->redirect('/admin/contact');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la modification.');
            $this->redirect('/admin/contact');
        }
    }

    /**
     * Supprime un message de contact
     */
    public function delete($idContact = null)
    {
        $this->requireAdmin();

        if ($idContact === null) {
            $this->redirect('/admin/contact');
        }

        $message = $this->contactModel->getMessageById($idContact);

        if (!$message) {
            $this->setFlash('error', 'Message introuvable.');
            $this->redirect('/admin/contact');
        }

        try {
            $this->contactModel->deleteMessage($idContact);
            $this->setFlash('success', 'Message supprimé.');
            $this->redirect('/admin/contact');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/admin/contact');
        }
    }

    /**
     * Affiche les statistiques des messages de contact
     */
    public function stats()
    {
        $this->requireAdmin();

        $totalMessages = $this->contactModel->countTotalMessages();
        $untreatedMessages = $this->contactModel->countUntreatedMessages();
        $treatedMessages = $totalMessages - $untreatedMessages;

        $data = [
            'total' => $totalMessages,
            'untreated' => $untreatedMessages,
            'treated' => $treatedMessages
        ];

        $this->view('admin/contact_stats', $data);
    }
}
