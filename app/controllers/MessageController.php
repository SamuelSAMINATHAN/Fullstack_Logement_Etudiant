<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class MessageController extends Controller
{
    private $messageModel;
    private $utilisateurModel;
    private $annonceModel;

    public function __construct()
    {
        $this->requireAuth();
        $this->messageModel = $this->model('MessageModel');
        $this->utilisateurModel = $this->model('UtilisateurModel');
        $this->annonceModel = $this->model('AnnonceModel');
    }

    /**
     * Envoie un message à un bailleur
     */
    public function send()
    {
        if (!$this->isPost()) {
            $this->redirect('/annonce');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            Session::setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/annonce/detail/' . ($post['idAnnonce'] ?? ''));
        }

        $idDestinataire = $post['idDestinataire'] ?? null;
        $idAnnonce = $post['idAnnonce'] ?? null;
        $contenu = trim($post['contenu'] ?? '');

        if (!$idDestinataire || empty($contenu)) {
            Session::setFlash('error', 'Le message ne peut pas être vide.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        // Préparer les données du message
        $data = [
            'contenu' => $contenu,
            'dateEnvoi' => date('Y-m-d H:i:s'),
            'estLu' => 0,
            'idExpediteur' => $_SESSION['user_id'],
            'idDestinataire' => $idDestinataire
        ];

        try {
            if ($this->messageModel->createMessage($data)) {
                Session::setFlash('success', 'Votre message a été envoyé.');
            } else {
                Session::setFlash('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', 'Erreur technique : ' . $e->getMessage());
        }

        if ($idAnnonce) {
            $this->redirect('/annonce/detail/' . $idAnnonce);
        } else {
            $this->redirect('/message/conversation/' . $idDestinataire);
        }
    }

    /**
     * Affiche la liste des conversations (Inbox)
     */
    public function index()
    {
        $conversations = $this->messageModel->getConversations($_SESSION['user_id']);
        $this->view('user/messages', ['conversations' => $conversations]);
    }

    /**
     * Alias pour index (Inbox)
     */
    public function inbox()
    {
        $this->index();
    }

    /**
     * Affiche une conversation spécifique
     */
/**
     * Affiche une conversation spécifique
     */
    public function conversation($idOther)
    {
        if (!$idOther) {
            $this->redirect('/message/inbox');
        }

        // Marquer comme lu
        $this->messageModel->markConversationAsRead($_SESSION['user_id'], $idOther);

        $messages = $this->messageModel->getConversation($_SESSION['user_id'], $idOther);
        
        // PROPRE & AUTORISÉ : On appelle la nouvelle méthode publique du modèle
        $otherUser = $this->utilisateurModel->getUserById($idOther);

        if (!$otherUser) {
            $this->redirect('/message/inbox');
        }

        $this->view('user/conversation', [
            'messages' => $messages,
            'otherUser' => $otherUser
        ]);
    }
}