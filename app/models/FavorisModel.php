<?php

namespace App\Models;

use App\Core\Model;

class FavorisModel extends Model
{
    /**
     * Récupère tous les favoris
     * @return array
     */
    public function getAllFavorites()
    {
        return $this->findAll('favoris');
    }

    /**
     * Récupère les favoris d'un étudiant
     * @param int $idEtudiant
     * @return array
     */
    public function getFavoritesByStudent($idEtudiant)
    {
        return $this->findWhere('favoris', 'idEtudiant', $idEtudiant);
    }

    /**
     * Récupère les favoris d'une annonce
     * @param int $idAnnonce
     * @return array
     */
    public function getFavoritesByAnnouncement($idAnnonce)
    {
        return $this->findWhere('favoris', 'idAnnonce', $idAnnonce);
    }

    /**
     * Vérifie si une annonce est en favori pour un étudiant
     * @param int $idEtudiant
     * @param int $idAnnonce
     * @return bool
     */
    public function isFavorite($idEtudiant, $idAnnonce)
    {
        $db = $this;
        $query = "
            SELECT * FROM favoris
            WHERE idEtudiant = :idEtudiant AND idAnnonce = :idAnnonce
        ";
        $stmt = $db->query($query, ['idEtudiant' => $idEtudiant, 'idAnnonce' => $idAnnonce]);
        return $stmt && count($stmt) > 0;
    }

    /**
     * Ajoute un favori
     * @param int $idEtudiant
     * @param int $idAnnonce
     * @return bool
     */
    public function addFavorite($idEtudiant, $idAnnonce)
    {
        // Vérifier que le favori n'existe pas déjà
        if ($this->isFavorite($idEtudiant, $idAnnonce)) {
            return true; // Déjà en favori
        }

        $data = [
            'idEtudiant' => $idEtudiant,
            'idAnnonce' => $idAnnonce
        ];
        return (bool) $this->create('favoris', $data);
    }

    /**
     * Supprime un favori
     * @param int $idEtudiant
     * @param int $idAnnonce
     * @return bool
     */
    public function removeFavorite($idEtudiant, $idAnnonce)
    {
        $db = $this;
        $query = "
            DELETE FROM favoris
            WHERE idEtudiant = :idEtudiant AND idAnnonce = :idAnnonce
        ";
        return $db->execute($query, ['idEtudiant' => $idEtudiant, 'idAnnonce' => $idAnnonce]);
    }

    /**
     * Récupère les favoris d'un étudiant avec les détails des annonces
     * @param int $idEtudiant
     * @return array
     */
    public function getFavoritesWithAnnouncements($idEtudiant)
    {
        $db = $this;
        $query = "
            SELECT f.*, a.titre, a.description, a.prix, a.localisation, a.type_logement,
                   a.surface, a.nbPieces, a.meuble, a.estColocation
            FROM favoris f
            JOIN annonce a ON f.idAnnonce = a.idAnnonce
            WHERE f.idEtudiant = :idEtudiant
            ORDER BY f.dateAjout DESC
        ";
        $stmt = $db->query($query, ['idEtudiant' => $idEtudiant]);
        return $stmt ?? [];
    }

    /**
     * Compte les favoris d'un étudiant
     * @param int $idEtudiant
     * @return int
     */
    public function countFavoritesByStudent($idEtudiant)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM favoris WHERE idEtudiant = :idEtudiant";
        $stmt = $db->query($query, ['idEtudiant' => $idEtudiant]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Compte les favoris d'une annonce
     * @param int $idAnnonce
     * @return int
     */
    public function countFavoritesByAnnouncement($idAnnonce)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM favoris WHERE idAnnonce = :idAnnonce";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }
}
