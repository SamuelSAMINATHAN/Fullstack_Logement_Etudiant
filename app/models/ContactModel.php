<?php

namespace App\Models;

use App\Core\Model;

class ContactModel extends Model
{
    /**
     * Récupère tous les messages de contact
     * @return array
     */
    public function getAllMessages()
    {
        return $this->findAll('contact');
    }

    /**
     * Récupère un message de contact par son ID
     * @param int $idContact
     * @return array|null
     */
    public function getMessageById($idContact)
    {
        return $this->findById('contact', 'idContact', $idContact);
    }

    /**
     * Récupère les messages de contact par email
     * @param string $email
     * @return array
     */
    public function getMessagesByEmail($email)
    {
        return $this->findWhere('contact', 'email', $email);
    }

    /**
     * Récupère les messages de contact non traités
     * @return array
     */
    public function getUntreatedMessages()
    {
        return $this->findWhere('contact', 'traite', 0);
    }

    /**
     * Récupère les messages de contact traités
     * @return array
     */
    public function getTreatedMessages()
    {
        return $this->findWhere('contact', 'traite', 1);
    }

    /**
     * Crée un nouveau message de contact
     * @param array $data
     * @return int ID du nouveau message
     */
    public function createMessage($data)
    {
        return $this->create('contact', $data);
    }

    /**
     * Met à jour un message de contact
     * @param int $idContact
     * @param array $data
     * @return bool
     */
    public function updateMessage($idContact, $data)
    {
        return $this->updateById('contact', 'idContact', $idContact, $data);
    }

    /**
     * Marque un message comme traité
     * @param int $idContact
     * @return bool
     */
    public function markAsProcessed($idContact)
    {
        return $this->updateById('contact', 'idContact', $idContact, ['traite' => 1]);
    }

    /**
     * Marque un message comme non traité
     * @param int $idContact
     * @return bool
     */
    public function markAsUntreated($idContact)
    {
        return $this->updateById('contact', 'idContact', $idContact, ['traite' => 0]);
    }

    /**
     * Supprime un message de contact
     * @param int $idContact
     * @return bool
     */
    public function deleteMessage($idContact)
    {
        return $this->deleteById('contact', 'idContact', $idContact);
    }

    /**
     * Compte les messages non traités
     * @return int
     */
    public function countUntreatedMessages()
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM contact WHERE traite = 0";
        $stmt = $db->query($query);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère les messages de contact paginés
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getMessagesPaginated($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $db = $this;
        $query = "
            SELECT * FROM contact
            ORDER BY dateEnvoi DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $db->query($query, ['limit' => $limit, 'offset' => $offset]);
        return $stmt ?? [];
    }

    /**
     * Compte le total des messages
     * @return int
     */
    public function countTotalMessages()
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM contact";
        $stmt = $db->query($query);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère les messages par sujet
     * @param string $sujet
     * @return array
     */
    public function getMessagesBySubject($sujet)
    {
        return $this->findWhere('contact', 'sujet', $sujet);
    }
}
