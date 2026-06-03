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
     * Recherche avancée d'annonces avec tous les filtres
     * @param array $filters Filtres : search, price_max, surface_min, types, rooms, meuble, ascenseur, parking, balcony, animaux, pmr
     * @return array
     */
    public function searchAnnouncements($filters = [])
    {
        $sql = "SELECT a.*, u.nom, u.prenom, u.email, b.estVerifie
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
        
        // Note: ascenseur, parking, balcon, animaux, pmr ne sont pas dans la table annonce
        // On les ignore pour éviter les erreurs SQL
        
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
