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
               OR (idExpediteur = :id2_alt AND idDestinataire = :id1_alt)
            ORDER BY dateEnvoi ASC
        ";
        $params = [
            'id1' => $idUtilisateur1, 
            'id2' => $idUtilisateur2,
            'id1_alt' => $idUtilisateur1,
            'id2_alt' => $idUtilisateur2
        ];
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
        // Cette requête récupère le dernier message de chaque conversation
        // Utilisation de paramètres nommés uniques pour compatibilité avec ATTR_EMULATE_PREPARES => false
        $query = "
            SELECT m1.*, 
                   u_exp.nom as nom_expediteur, u_exp.prenom as prenom_expediteur,
                   u_dest.nom as nom_destinataire, u_dest.prenom as prenom_destinataire
            FROM message m1
            JOIN (
                SELECT 
                    CASE WHEN idExpediteur = :id1 THEN idDestinataire ELSE idExpediteur END AS other_id,
                    MAX(dateEnvoi) as max_date
                FROM message
                WHERE idExpediteur = :id2 OR idDestinataire = :id3
                GROUP BY other_id
            ) m2 ON (CASE WHEN m1.idExpediteur = :id4 THEN m1.idDestinataire ELSE m1.idExpediteur END = m2.other_id 
                    AND m1.dateEnvoi = m2.max_date)
            JOIN utilisateur u_exp ON m1.idExpediteur = u_exp.idUtilisateur
            JOIN utilisateur u_dest ON m1.idDestinataire = u_dest.idUtilisateur
            WHERE m1.idExpediteur = :id5 OR m1.idDestinataire = :id6
            ORDER BY m1.dateEnvoi DESC
        ";
        $params = [
            'id1' => $idUtilisateur,
            'id2' => $idUtilisateur,
            'id3' => $idUtilisateur,
            'id4' => $idUtilisateur,
            'id5' => $idUtilisateur,
            'id6' => $idUtilisateur
        ];
        $stmt = $db->query($query, $params);
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
     * @return int|bool
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
        $sql = "
            UPDATE message SET estLu = 1
            WHERE idDestinataire = :idUtilisateur1 
              AND idExpediteur = :idUtilisateur2
              AND estLu = 0
        ";
        $this->query($sql, ['idUtilisateur1' => $idUtilisateur1, 'idUtilisateur2' => $idUtilisateur2]);
        return true;
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
        $sql = "
            DELETE FROM message
            WHERE (idExpediteur = :id1 AND idDestinataire = :id2)
               OR (idExpediteur = :id2_alt AND idDestinataire = :id1_alt)
        ";
        $this->query($sql, [
            'id1' => $idUtilisateur1, 
            'id2' => $idUtilisateur2,
            'id1_alt' => $idUtilisateur1,
            'id2_alt' => $idUtilisateur2
        ]);
        return true;
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
               OR (idExpediteur = :id2_alt AND idDestinataire = :id1_alt)
        ";
        $stmt = $db->query($query, [
            'id1' => $idUtilisateur1, 
            'id2' => $idUtilisateur2,
            'id1_alt' => $idUtilisateur1,
            'id2_alt' => $idUtilisateur2
        ]);
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
               OR (idExpediteur = :id2_alt AND idDestinataire = :id1_alt)
            ORDER BY dateEnvoi DESC
            LIMIT 1
        ";
        $stmt = $db->query($query, [
            'id1' => $idUtilisateur1, 
            'id2' => $idUtilisateur2,
            'id1_alt' => $idUtilisateur1,
            'id2_alt' => $idUtilisateur2
        ]);
        return $stmt ? ($stmt[0] ?? null) : null;
    }
}
