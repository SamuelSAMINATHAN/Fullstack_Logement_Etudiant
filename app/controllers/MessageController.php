<?php

namespace App\Controllers;

use App\Core\Controller;

class MessageController extends Controller
{
    private $messageModel;
    private $utilisateurModel;

    public function __construct()
    {
        $this->messageModel = $this->model('MessageModel');
        $this->utilisateurModel = $this->model('UtilisateurModel');
    }

    /**
     * Affiche la boîte de réception
     */
    public function inbox()
    {
        $this->requireAuth();

        $messages = $this->messageModel->getUnreadMessages($_SESSION['user_id']);
        $conversations = $this->messageModel->getConversations($_SESSION['user_id']);

        $data = [
            'unread_messages' => $messages,
            'conversations' => $conversations
        ];

        $this->view('messaging/inbox', $data);
    }

    /**
     * Affiche une conversation
     */
    public function conversation($idInterlocuteur = null)
    {
        $this->requireAuth();

        if ($idInterlocuteur === null) {
            $this->redirect('/message/inbox');
        }

        // Vérifier que l'interlocuteur existe
        $interlocuteur = $this->utilisateurModel->getUserById($idInterlocuteur);

        if (!$interlocuteur) {
            $this->setFlash('error', 'Utilisateur introuvable.');
            $this->redirect('/message/inbox');
        }

        // Récupérer la conversation
        $messages = $this->messageModel->getConversation($_SESSION['user_id'], $idInterlocuteur);

        // Marquer tous les messages comme lus
        $this->messageModel->markConversationAsRead($_SESSION['user_id'], $idInterlocuteur);

        $data = [
            'interlocuteur' => $interlocuteur,
            'messages' => $messages,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('messaging/conversation', $data);
    }

    /**
     * Envoie un message
     */
    public function send($idDestinataire = null)
    {
        $this->requireAuth();

        if ($idDestinataire === null || !$this->isPost()) {
            $this->redirect('/message/inbox');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/message/conversation/' . $idDestinataire);
        }

        // Vérifier que le destinataire existe
        $destinataire = $this->utilisateurModel->getUserById($idDestinataire);

        if (!$destinataire) {
            $this->setFlash('error', 'Utilisateur introuvable.');
            $this->redirect('/message/inbox');
        }

        // Vérifier que l'utilisateur ne s'envoie pas de message à lui-même
        if ($idDestinataire == $_SESSION['user_id']) {
            $this->setFlash('error', 'Vous ne pouvez pas vous envoyer des messages.');
            $this->redirect('/message/inbox');
        }

        $contenu = $post['contenu'] ?? '';

        if (empty($contenu)) {
            $this->setFlash('error', 'Le message ne peut pas être vide.');
            $this->redirect('/message/conversation/' . $idDestinataire);
        }

        if (strlen($contenu) > 5000) {
            $this->setFlash('error', 'Le message est trop long (max 5000 caractères).');
            $this->redirect('/message/conversation/' . $idDestinataire);
        }

        try {
            $data = [
                'idExpediteur' => $_SESSION['user_id'],
                'idDestinataire' => $idDestinataire,
                'contenu' => $contenu,
                'estLu' => 0
            ];

            $idMessage = $this->messageModel->createMessage($data);

            if (!$idMessage) {
                $this->setFlash('error', 'Erreur lors de l\'envoi du message.');
                $this->redirect('/message/conversation/' . $idDestinataire);
            }

            $this->setFlash('success', 'Message envoyé !');
            $this->redirect('/message/conversation/' . $idDestinataire);
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
            $this->redirect('/message/conversation/' . $idDestinataire);
        }
    }

    /**
     * Supprime une conversation
     */
    public function deleteConversation($idInterlocuteur = null)
    {
        $this->requireAuth();

        if ($idInterlocuteur === null) {
            $this->redirect('/message/inbox');
        }

        $interlocuteur = $this->utilisateurModel->getUserById($idInterlocuteur);

        if (!$interlocuteur) {
            $this->setFlash('error', 'Utilisateur introuvable.');
            $this->redirect('/message/inbox');
        }

        try {
            $this->messageModel->deleteConversation($_SESSION['user_id'], $idInterlocuteur);
            $this->setFlash('success', 'Conversation supprimée.');
            $this->redirect('/message/inbox');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/message/inbox');
        }
    }

    /**
     * Récupère le nombre de messages non lus
     */
    public function getUnreadCount()
    {
        if (!$this->isLoggedIn()) {
            echo json_encode(['count' => 0]);
            return;
        }

        $count = $this->messageModel->countUnreadMessages($_SESSION['user_id']);
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
    }
}
