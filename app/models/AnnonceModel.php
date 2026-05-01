<?php

namespace App\Models;

use App\Core\Model;

class AnnonceModel extends Model
{
    /**
     * Récupère toutes les annonces
     * @return array
     */
    public function getAllAnnouncements()
    {
        return $this->findAll('annonce');
    }

    /**
     * Récupère une annonce par son ID
     * @param int $idAnnonce
     * @return array|null
     */
    public function getAnnouncementById($idAnnonce)
    {
        return $this->findById('annonce', 'idAnnonce', $idAnnonce);
    }

    /**
     * Récupère les annonces d'un bailleur
     * @param int $idBailleur
     * @return array
     */
    public function getAnnouncementsByLandlord($idBailleur)
    {
        return $this->findWhere('annonce', 'idBailleur', $idBailleur);
    }

    /**
     * Récupère les annonces par localisation
     * @param string $localisation
     * @return array
     */
    public function getAnnouncementsByLocation($localisation)
    {
        return $this->findWhere('annonce', 'localisation', $localisation);
    }

    /**
     * Récupère les annonces par type de logement
     * @param string $type_logement
     * @return array
     */
    public function getAnnouncementsByType($type_logement)
    {
        return $this->findWhere('annonce', 'type_logement', $type_logement);
    }

    /**
     * Récupère les annonces meublées ou non meublées
     * @param int $meuble 0 ou 1
     * @return array
     */
    public function getAnnouncementsByFurnished($meuble)
    {
        return $this->findWhere('annonce', 'meuble', $meuble);
    }

    /**
     * Récupère les annonces en colocation ou non
     * @param int $estColocation 0 ou 1
     * @return array
     */
    public function getAnnouncementsByColocation($estColocation)
    {
        return $this->findWhere('annonce', 'estColocation', $estColocation);
    }

    /**
     * Recherche avancée d'annonces
     * @param array $filters Filtres : localisation, prix_min, prix_max, type_logement, meuble, estColocation
     * @return array
     */
    public function searchAnnouncements($filters = [])
    {
        $db = $this;
        $query = "SELECT * FROM annonce WHERE 1=1";
        $params = [];

        if (!empty($filters['localisation'])) {
            $query .= " AND localisation LIKE :localisation";
            $params['localisation'] = '%' . $filters['localisation'] . '%';
        }

        if (isset($filters['prix_min'])) {
            $query .= " AND prix >= :prix_min";
            $params['prix_min'] = $filters['prix_min'];
        }

        if (isset($filters['prix_max'])) {
            $query .= " AND prix <= :prix_max";
            $params['prix_max'] = $filters['prix_max'];
        }

        if (!empty($filters['type_logement'])) {
            $query .= " AND type_logement = :type_logement";
            $params['type_logement'] = $filters['type_logement'];
        }

        if (isset($filters['meuble'])) {
            $query .= " AND meuble = :meuble";
            $params['meuble'] = $filters['meuble'];
        }

        if (isset($filters['estColocation'])) {
            $query .= " AND estColocation = :estColocation";
            $params['estColocation'] = $filters['estColocation'];
        }

        $query .= " ORDER BY datePublication DESC";

        $stmt = $db->query($query, $params);
        return $stmt ?? [];
    }

    /**
     * Crée une nouvelle annonce
     * @param array $data
     * @return int ID de la nouvelle annonce
     */
    public function createAnnouncement($data)
    {
        return $this->create('annonce', $data);
    }

    /**
     * Met à jour une annonce
     * @param int $idAnnonce
     * @param array $data
     * @return bool
     */
    public function updateAnnouncement($idAnnonce, $data)
    {
        return $this->updateById('annonce', 'idAnnonce', $idAnnonce, $data);
    }

    /**
     * Supprime une annonce
     * @param int $idAnnonce
     * @return bool
     */
    public function deleteAnnouncement($idAnnonce)
    {
        return $this->deleteById('annonce', 'idAnnonce', $idAnnonce);
    }

    /**
     * Récupère les annonces récemment publiées
     * @param int $limit Nombre de résultats
     * @return array
     */
    public function getRecentAnnouncements($limit = 10)
    {
        $db = $this;
        $query = "
            SELECT * FROM annonce
            ORDER BY datePublication DESC
            LIMIT :limit
        ";
        $stmt = $db->query($query, ['limit' => $limit]);
        return $stmt ?? [];
    }

    /**
     * Compte le nombre d'annonces d'un bailleur
     * @param int $idBailleur
     * @return int
     */
    public function countAnnouncementsByLandlord($idBailleur)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM annonce WHERE idBailleur = :idBailleur";
        $stmt = $db->query($query, ['idBailleur' => $idBailleur]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère une annonce avec les données du bailleur
     * @param int $idAnnonce
     * @return array|null
     */
    public function getAnnouncementWithLandlord($idAnnonce)
    {
        $db = $this;
        $query = "
            SELECT a.*, u.nom, u.prenom, u.email, b.estVerifie, b.estShadowban
            FROM annonce a
            JOIN bailleur b ON a.idBailleur = b.idUtilisateur
            JOIN utilisateur u ON b.idUtilisateur = u.idUtilisateur
            WHERE a.idAnnonce = :idAnnonce
        ";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0] ?? null) : null;
    }
}
