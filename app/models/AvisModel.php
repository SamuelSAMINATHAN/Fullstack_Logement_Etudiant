<?php

namespace App\Models;

use App\Core\Model;

class AvisModel extends Model
{
    /**
     * Récupère tous les avis
     * @return array
     */
    public function getAllReviews()
    {
        return $this->findAll('avis');
    }

    /**
     * Récupère un avis par son ID
     * @param int $idAvis
     * @return array|null
     */
    public function getReviewById($idAvis)
    {
        return $this->findById('avis', 'idAvis', $idAvis);
    }

    /**
     * Récupère les avis d'un étudiant
     * @param int $idEtudiant
     * @return array
     */
    public function getReviewsByStudent($idEtudiant)
    {
        return $this->findWhere('avis', 'idEtudiant', $idEtudiant);
    }

    /**
     * Récupère les avis pour une annonce
     * @param int $idAnnonce
     * @return array
     */
    public function getReviewsByAnnouncement($idAnnonce)
    {
        return $this->findWhere('avis', 'idAnnonce', $idAnnonce);
    }

    /**
     * Récupère l'avis d'un étudiant pour une annonce spécifique
     * @param int $idEtudiant
     * @param int $idAnnonce
     * @return array|null
     */
    public function getReviewByStudentAndAnnouncement($idEtudiant, $idAnnonce)
    {
        $db = $this;
        $query = "
            SELECT * FROM avis
            WHERE idEtudiant = :idEtudiant AND idAnnonce = :idAnnonce
        ";
        $stmt = $db->query($query, ['idEtudiant' => $idEtudiant, 'idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0] ?? null) : null;
    }

    /**
     * Vérifie si un étudiant a déjà laissé un avis pour une annonce
     * @param int $idEtudiant
     * @param int $idAnnonce
     * @return bool
     */
    public function hasReviewed($idEtudiant, $idAnnonce)
    {
        return $this->getReviewByStudentAndAnnouncement($idEtudiant, $idAnnonce) !== null;
    }

    /**
     * Crée un nouvel avis
     * @param array $data
     * @return int ID du nouvel avis
     */
    public function createReview($data)
    {
        // Valider que la note est entre 1 et 5
        if (isset($data['note']) && ($data['note'] < 1 || $data['note'] > 5)) {
            throw new \Exception('La note doit être entre 1 et 5');
        }

        return $this->create('avis', $data);
    }

    /**
     * Met à jour un avis
     * @param int $idAvis
     * @param array $data
     * @return bool
     */
    public function updateReview($idAvis, $data)
    {
        // Valider que la note est entre 1 et 5
        if (isset($data['note']) && ($data['note'] < 1 || $data['note'] > 5)) {
            throw new \Exception('La note doit être entre 1 et 5');
        }

        return $this->updateById('avis', 'idAvis', $idAvis, $data);
    }

    /**
     * Supprime un avis
     * @param int $idAvis
     * @return bool
     */
    public function deleteReview($idAvis)
    {
        return $this->deleteById('avis', 'idAvis', $idAvis);
    }

    /**
     * Calcule la note moyenne pour une annonce
     * @param int $idAnnonce
     * @return float
     */
    public function getAverageRatingByAnnouncement($idAnnonce)
    {
        $db = $this;
        $query = "
            SELECT AVG(note) as average FROM avis
            WHERE idAnnonce = :idAnnonce
        ";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ? floatval($stmt[0]['average'] ?? 0) : 0;
    }

    /**
     * Compte les avis pour une annonce
     * @param int $idAnnonce
     * @return int
     */
    public function countReviewsByAnnouncement($idAnnonce)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM avis WHERE idAnnonce = :idAnnonce";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère les avis d'une annonce avec les données des étudiants
     * @param int $idAnnonce
     * @return array
     */
    public function getReviewsWithStudents($idAnnonce)
    {
        $db =   $this;
        $query = "
            SELECT a.*, u.nom, u.prenom, u.email
            FROM avis a
            JOIN etudiant e ON a.idEtudiant = e.idUtilisateur
            JOIN utilisateur u ON e.idUtilisateur = u.idUtilisateur
            WHERE a.idAnnonce = :idAnnonce
            ORDER BY a.dateAvis DESC
        ";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ?? [];
    }

    /**
     * Récupère les avis d'une annonce triés par note décroissante
     * @param int $idAnnonce
     * @return array
     */
    public function getReviewsByAnnouncementSortedByRating($idAnnonce)
    {
        $db = $this;
        $query = "
            SELECT * FROM avis
            WHERE idAnnonce = :idAnnonce
            ORDER BY note DESC, dateAvis DESC
        ";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ?? [];
    }
}
