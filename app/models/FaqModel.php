<?php

namespace App\Models;

use App\Core\Model;

class FaqModel extends Model
{
    /**
     * Récupère toutes les FAQ
     * @return array
     */
    public function getAllFAQs()
    {
        return $this->findAll('faq');
    }

    /**
     * Récupère une FAQ par son ID
     * @param int $idFAQ
     * @return array|null
     */
    public function getFAQById($idFAQ)
    {
        return $this->findById('faq', 'idFAQ', $idFAQ);
    }

    /**
     * Recherche une FAQ par mot-clé dans la question ou la réponse
     * @param string $keyword
     * @return array
     */
    public function searchFAQs($keyword)
    {
        $db = $this;
        $query = "
            SELECT * FROM faq
            WHERE question LIKE :keyword OR reponse LIKE :keyword
            ORDER BY idFAQ ASC
        ";
        $keyword = '%' . $keyword . '%';
        $stmt = $db->query($query, ['keyword' => $keyword]);
        return $stmt ?? [];
    }

    /**
     * Crée une nouvelle FAQ
     * @param array $data
     * @return int ID de la nouvelle FAQ
     */
    public function createFAQ($data)
    {
        return $this->create('faq', $data);
    }

    /**
     * Met à jour une FAQ
     * @param int $idFAQ
     * @param array $data
     * @return bool
     */
    public function updateFAQ($idFAQ, $data)
    {
        return $this->updateById('faq', 'idFAQ', $idFAQ, $data);
    }

    /**
     * Supprime une FAQ
     * @param int $idFAQ
     * @return bool
     */
    public function deleteFAQ($idFAQ)
    {
        return $this->deleteById('faq', 'idFAQ', $idFAQ);
    }

    /**
     * Récupère les FAQ paginées
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getFAQsPaginated($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $db = $this;
        $query = "
            SELECT * FROM faq
            ORDER BY idFAQ ASC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $db->query($query, ['limit' => $limit, 'offset' => $offset]);
        return $stmt ?? [];
    }

    /**
     * Compte le total des FAQ
     * @return int
     */
    public function countFAQs()
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM faq";
        $stmt = $db->query($query);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère les FAQ pour le frontend (sans gestion d'ordre spéciale)
     * @return array
     */
    public function getFAQsForFrontend()
    {
        return $this->getAllFAQs();
    }
}
