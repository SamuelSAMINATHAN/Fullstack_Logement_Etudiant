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
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE b.estShadowban = 0";
        return $this->select($sql);
    }

    /**
     * Récupère une annonce par son ID
     * @param int $idAnnonce
     * @return array|null
     */
    public function getAnnouncementById($idAnnonce)
    {
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE a.idAnnonce = ?";
        $result = $this->select($sql, [$idAnnonce]);
        return $result ? $result[0] : null;
    }

    /**
     * Récupère les annonces d'un bailleur
     * @param int $idBailleur
     * @return array
     */
    public function getAnnouncementsByLandlord($idBailleur)
    {
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE a.idBailleur = ?";
        return $this->select($sql, [$idBailleur]);
    }

    /**
     * Récupère les annonces par localisation
     * @param string $localisation
     * @return array
     */
    public function getAnnouncementsByLocation($localisation)
    {
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE a.localisation LIKE ? AND b.estShadowban = 0";
        return $this->select($sql, ['%' . $localisation . '%']);
    }

    /**
     * Récupère les annonces par type de logement
     * @param string $type_logement
     * @return array
     */
    public function getAnnouncementsByType($type_logement)
    {
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE a.type_logement = ? AND b.estShadowban = 0";
        return $this->select($sql, [$type_logement]);
    }

    /**
     * Récupère les annonces meublées ou non meublées
     * @param int $meuble 0 ou 1
     * @return array
     */
    public function getAnnouncementsByFurnished($meuble)
    {
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE a.meuble = ? AND b.estShadowban = 0";
        return $this->select($sql, [$meuble]);
    }

    /**
     * Récupère les annonces en colocation ou non
     * @param int $estColocation 0 ou 1
     * @return array
     */
    public function getAnnouncementsByColocation($estColocation)
    {
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE a.estColocation = ? AND b.estShadowban = 0";
        return $this->select($sql, [$estColocation]);
    }

    /**
     * Recherche avancée d'annonces avec tous les filtres
     * @param array $filters Filtres : search, price_max, surface_min, types, rooms, meuble, ascenseur, parking, balcony, animaux, pmr
     * @return array
     */
    public function searchAnnouncements($filters = [])
    {
        $sql = "SELECT a.*, u.nom, u.prenom, u.email, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                JOIN utilisateur u ON b.idUtilisateur = u.idUtilisateur
                WHERE b.estShadowban = 0";

        $params = [];

        // Filtre par recherche (titre, description, localisation)
        if (!empty($filters['search'])) {
            $sql .= " AND (a.titre LIKE ? OR a.description LIKE ? OR a.localisation LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtre par localisation (compatibilité ancienne version)
        if (!empty($filters['localisation'])) {
            $sql .= " AND a.localisation LIKE ?";
            $params[] = '%' . $filters['localisation'] . '%';
        }

        // Filtre par prix maximum
        if (isset($filters['prix_max']) || !empty($filters['price_max'])) {
            $priceMax = $filters['price_max'] ?? $filters['prix_max'];
            $sql .= " AND a.prix <= ?";
            $params[] = $priceMax;
        }

        // Filtre par prix minimum
        if (isset($filters['prix_min'])) {
            $sql .= " AND a.prix >= ?";
            $params[] = $filters['prix_min'];
        }

        // Filtre par surface minimum
        if (!empty($filters['surface_min'])) {
            $sql .= " AND a.surface >= ?";
            $params[] = $filters['surface_min'];
        }

        // Filtre par nombre de pièces
        if (!empty($filters['rooms']) && $filters['rooms'] !== '0') {
            $sql .= " AND a.nbPieces = ?";
            $params[] = $filters['rooms'];
        }

        // Filtres par type (individuel, couple, colocation)
        if (!empty($filters['types'])) {
            $types = explode(',', $filters['types']);
            $typeConditions = [];
            foreach ($types as $type) {
                switch ($type) {
                    case 'individuel':
                        $typeConditions[] = "(a.estColocation = 0 AND a.nbPieces = 1)";
                        break;
                    case 'couple':
                        $typeConditions[] = "(a.estColocation = 0 AND a.nbPieces >= 2)";
                        break;
                    case 'colocation':
                        $typeConditions[] = "a.estColocation = 1";
                        break;
                }
            }
            if (!empty($typeConditions)) {
                $sql .= " AND (" . implode(' OR ', $typeConditions) . ")";
            }
        }

        // Filtre par type de logement (compatibilité ancienne version)
        if (!empty($filters['type_logement'])) {
            $sql .= " AND a.type_logement = ?";
            $params[] = $filters['type_logement'];
        }

        // Filtres par équipements (Uniquement ceux présents dans la table annonce)
        if (isset($filters['meuble']) || !empty($filters['meuble'])) {
            $sql .= " AND a.meuble = 1";
        }

        // Filtre par colocation (compatibilité ancienne version)
        if (isset($filters['estColocation'])) {
            $sql .= " AND a.estColocation = ?";
            $params[] = $filters['estColocation'];
        }

        $sql .= " ORDER BY a.datePublication DESC";

        return $this->select($sql, $params);
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
     * Incrémente le compteur de vues d'une annonce
     * @param int $idAnnonce
     * @return bool
     */
    public function incrementViews($idAnnonce)
    {
        $sql = "UPDATE annonce SET vues = vues + 1 WHERE idAnnonce = ?";
        return $this->update($sql, [$idAnnonce]) > 0;
    }

    /**
     * Récupère le nombre total de vues pour toutes les annonces d'un bailleur
     * @param int $idBailleur
     * @return int
     */
    public function getTotalViewsByLandlord($idBailleur)
    {
        $sql = "SELECT SUM(vues) as total FROM annonce WHERE idBailleur = ?";
        $result = $this->selectOne($sql, [$idBailleur]);
        return $result ? (int)($result['total'] ?? 0) : 0;
    }

    /**
     * Compte le nombre d'annonces d'un bailleur
     * @param int $idBailleur
     * @return int
     */
    public function countAnnouncementsByLandlord($idBailleur)
    {
        $sql = "SELECT COUNT(*) as total FROM annonce WHERE idBailleur = ?";
        $result = $this->selectOne($sql, [$idBailleur]);
        return $result ? (int)($result['total'] ?? 0) : 0;
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
        $sql = "SELECT a.*, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                WHERE b.estShadowban = 0
                ORDER BY a.datePublication DESC
                LIMIT ?";
        return $this->select($sql, [$limit]);
    }

    /**
     * Récupère une annonce avec les données du bailleur
     * @param int $idAnnonce
     * @return array|null
     */
    public function getAnnouncementWithLandlord($idAnnonce)
    {
        $sql = "SELECT a.*, u.nom, u.prenom, u.email, b.estVerifie, b.estShadowban
                FROM annonce a
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                JOIN utilisateur u ON b.idUtilisateur = u.idUtilisateur
                WHERE a.idAnnonce = ?";
        $result = $this->select($sql, [$idAnnonce]);
        return $result ? $result[0] : null;
    }
}