<?php

namespace App\Models;

use App\Core\Model;

class MessageModel extends Model
{
    /**
     * Récupère tous les messages
     * @return array
     */
    public function getAllMessages()
    {
        return $this->findAll('message');
    }

    /**
     * Récupère un message par son ID
     * @param int $idMessage
     * @return array|null
     */
    public function getMessageById($idMessage)
    {
        return $this->findById('message', 'idMessage', $idMessage);
    }

    /**
     * Récupère les messages envoyés par un utilisateur
     * @param int $idExpediteur
     * @return array
     */
    public function getMessagesBySender($idExpediteur)
    {
        return $this->findWhere('message', 'idExpediteur', $idExpediteur);
    }

    /**
     * Récupère les messages reçus par un utilisateur
     * @param int $idDestinataire
     * @return array
     */
    public function getMessagesByRecipient($idDestinataire)
    {
        return $this->findWhere('message', 'idDestinataire', $idDestinataire);
    }

    /**
     * Récupère une conversation complète entre deux utilisateurs
     * @param int $idUtilisateur1
     * @param int $idUtilisateur2
     * @return array
     */
    public function getConversation($idUtilisateur1, $idUtilisateur2)
    {
        $db = $this;
        $query = "
            SELECT * FROM message
            WHERE (idExpediteur = :id1 AND idDestinataire = :id2)
               OR (idExpediteur = :id2 AND idDestinataire = :id1)
            ORDER BY dateEnvoi ASC
        ";
        $params = ['id1' => $idUtilisateur1, 'id2' => $idUtilisateur2];
        $stmt = $db->query($query, $params);
        return $stmt ?? [];
    }

    /**
     * Récupère les conversations d'un utilisateur (derniers messages)
     * @param int $idUtilisateur
     * @return array
     */
    public function getConversations($idUtilisateur)
    {
        $db = $this;
        $query = "
            SELECT m.*, u1.nom as nom_expediteur, u1.prenom as prenom_expediteur,
                   u2.nom as nom_destinataire, u2.prenom as prenom_destinataire
            FROM message m
            JOIN utilisateur u1 ON m.idExpediteur = u1.idUtilisateur
            JOIN utilisateur u2 ON m.idDestinataire = u2.idUtilisateur
            WHERE m.idExpediteur = :idUtilisateur OR m.idDestinataire = :idUtilisateur
            ORDER BY m.dateEnvoi DESC
        ";
        $stmt = $db->query($query, ['idUtilisateur' => $idUtilisateur]);
        return $stmt ?? [];
    }

    /**
     * Récupère les messages non lus d'un utilisateur
     * @param int $idDestinataire
     * @return array
     */
    public function getUnreadMessages($idDestinataire)
    {
        $db = $this;
        $query = "
            SELECT * FROM message
            WHERE idDestinataire = :idDestinataire AND estLu = 0
            ORDER BY dateEnvoi DESC
        ";
        $stmt = $db->query($query, ['idDestinataire' => $idDestinataire]);
        return $stmt ?? [];
    }

    /**
     * Crée un nouveau message
     * @param array $data
     * @return int ID du nouveau message
     */
    public function createMessage($data)
    {
        return $this->create('message', $data);
    }

    /**
     * Met à jour un message
     * @param int $idMessage
     * @param array $data
     * @return bool
     */
    public function updateMessage($idMessage, $data)
    {
        return $this->updateById('message', 'idMessage', $idMessage, $data);
    }

    /**
     * Marque un message comme lu
     * @param int $idMessage
     * @return bool
     */
    public function markAsRead($idMessage)
    {
        return $this->updateById('message', 'idMessage', $idMessage, ['estLu' => 1]);
    }

    /**
     * Marque tous les messages d'une conversation comme lus
     * @param int $idUtilisateur1
     * @param int $idUtilisateur2
     * @return bool
     */
    public function markConversationAsRead($idUtilisateur1, $idUtilisateur2)
    {
        $db = $this;
        $query = "
            UPDATE message SET estLu = 1
            WHERE idDestinataire = :idUtilisateur1 
              AND idExpediteur = :idUtilisateur2
              AND estLu = 0
        ";
        return $db->execute($query, ['idUtilisateur1' => $idUtilisateur1, 'idUtilisateur2' => $idUtilisateur2]);
    }

    /**
     * Supprime un message
     * @param int $idMessage
     * @return bool
     */
    public function deleteMessage($idMessage)
    {
        return $this->deleteById('message', 'idMessage', $idMessage);
    }

    /**
     * Supprime tous les messages d'une conversation
     * @param int $idUtilisateur1
     * @param int $idUtilisateur2
     * @return bool
     */
    public function deleteConversation($idUtilisateur1, $idUtilisateur2)
    {
        $db = $this;
        $query = "
            DELETE FROM message
            WHERE (idExpediteur = :id1 AND idDestinataire = :id2)
               OR (idExpediteur = :id2 AND idDestinataire = :id1)
        ";
        return $db->execute($query, ['id1' => $idUtilisateur1, 'id2' => $idUtilisateur2]);
    }

    /**
     * Compte les messages non lus d'un utilisateur
     * @param int $idDestinataire
     * @return int
     */
    public function countUnreadMessages($idDestinataire)
    {
        $db = $this;
        $query = "
            SELECT COUNT(*) as count FROM message
            WHERE idDestinataire = :idDestinataire AND estLu = 0
        ";
        $stmt = $db->query($query, ['idDestinataire' => $idDestinataire]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Compte les messages d'une conversation
     * @param int $idUtilisateur1
     * @param int $idUtilisateur2
     * @return int
     */
    public function countConversationMessages($idUtilisateur1, $idUtilisateur2)
    {
        $db = $this;
        $query = "
            SELECT COUNT(*) as count FROM message
            WHERE (idExpediteur = :id1 AND idDestinataire = :id2)
               OR (idExpediteur = :id2 AND idDestinataire = :id1)
        ";
        $stmt = $db->query($query, ['id1' => $idUtilisateur1, 'id2' => $idUtilisateur2]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère le dernier message entre deux utilisateurs
     * @param int $idUtilisateur1
     * @param int $idUtilisateur2
     * @return array|null
     */
    public function getLastMessage($idUtilisateur1, $idUtilisateur2)
    {
        $db = $this;
        $query = "
            SELECT * FROM message
            WHERE (idExpediteur = :id1 AND idDestinataire = :id2)
               OR (idExpediteur = :id2 AND idDestinataire = :id1)
            ORDER BY dateEnvoi DESC
            LIMIT 1
        ";
        $stmt = $db->query($query, ['id1' => $idUtilisateur1, 'id2' => $idUtilisateur2]);
        return $stmt ? ($stmt[0] ?? null) : null;
    }
}
