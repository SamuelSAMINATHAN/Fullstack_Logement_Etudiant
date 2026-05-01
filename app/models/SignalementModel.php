<?php

namespace App\Models;

use App\Core\Model;

class SignalementModel extends Model
{
    /**
     * Récupère tous les signalements
     * @return array
     */
    public function getAllReports()
    {
        return $this->findAll('signalement');
    }

    /**
     * Récupère un signalement par son ID
     * @param int $idSignalement
     * @return array|null
     */
    public function getReportById($idSignalement)
    {
        return $this->findById('signalement', 'idSignalement', $idSignalement);
    }

    /**
     * Récupère les signalements d'un étudiant
     * @param int $idEtudiant
     * @return array
     */
    public function getReportsByStudent($idEtudiant)
    {
        return $this->findWhere('signalement', 'idEtudiant', $idEtudiant);
    }

    /**
     * Récupère les signalements pour une annonce
     * @param int $idAnnonce
     * @return array
     */
    public function getReportsByAnnouncement($idAnnonce)
    {
        return $this->findWhere('signalement', 'idAnnonce', $idAnnonce);
    }

    /**
     * Récupère les signalements avec un statut spécifique
     * @param string $statut 'En attente' | 'Traité' | 'Rejeté'
     * @return array
     */
    public function getReportsByStatus($statut)
    {
        return $this->findWhere('signalement', 'statut', $statut);
    }

    /**
     * Crée un nouveau signalement
     * @param array $data
     * @return int ID du nouveau signalement
     */
    public function createReport($data)
    {
        return $this->create('signalement', $data);
    }

    /**
     * Met à jour un signalement
     * @param int $idSignalement
     * @param array $data
     * @return bool
     */
    public function updateReport($idSignalement, $data)
    {
        return $this->updateById('signalement', 'idSignalement', $idSignalement, $data);
    }

    /**
     * Change le statut d'un signalement
     * @param int $idSignalement
     * @param string $statut
     * @return bool
     */
    public function updateReportStatus($idSignalement, $statut)
    {
        return $this->updateById('signalement', 'idSignalement', $idSignalement, ['statut' => $statut]);
    }

    /**
     * Marque un signalement comme traité
     * @param int $idSignalement
     * @return bool
     */
    public function markAsProcessed($idSignalement)
    {
        return $this->updateReportStatus($idSignalement, 'Traité');
    }

    /**
     * Rejette un signalement
     * @param int $idSignalement
     * @return bool
     */
    public function rejectReport($idSignalement)
    {
        return $this->updateReportStatus($idSignalement, 'Rejeté');
    }

    /**
     * Supprime un signalement
     * @param int $idSignalement
     * @return bool
     */
    public function deleteReport($idSignalement)
    {
        return $this->deleteById('signalement', 'idSignalement', $idSignalement);
    }

    /**
     * Récupère les signalements en attente avec les détails
     * @return array
     */
    public function getPendingReportsWithDetails()
    {
        $db = $this;
        $query = "
            SELECT s.*, 
                   u.nom, u.prenom, u.email,
                   a.titre as titre_annonce, a.idBailleur
            FROM signalement s
            JOIN etudiant e ON s.idEtudiant = e.idUtilisateur
            JOIN utilisateur u ON e.idUtilisateur = u.idUtilisateur
            JOIN annonce a ON s.idAnnonce = a.idAnnonce
            WHERE s.statut = 'En attente'
            ORDER BY s.dateSignalement ASC
        ";
        $stmt = $db->query($query);
        return $stmt ?? [];
    }

    /**
     * Compte les signalements en attente
     * @return int
     */
    public function countPendingReports()
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM signalement WHERE statut = 'En attente'";
        $stmt = $db->query($query);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Compte les signalements pour une annonce
     * @param int $idAnnonce
     * @return int
     */
    public function countReportsByAnnouncement($idAnnonce)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM signalement WHERE idAnnonce = :idAnnonce";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère les annonces les plus signalées
     * @param int $limit
     * @return array
     */
    public function getMostReportedAnnouncements($limit = 10)
    {
        $db = $this;
        $query = "
            SELECT a.idAnnonce, a.titre, a.localisation, COUNT(s.idSignalement) as report_count
            FROM annonce a
            LEFT JOIN signalement s ON a.idAnnonce = s.idAnnonce
            GROUP BY a.idAnnonce
            ORDER BY report_count DESC
            LIMIT :limit
        ";
        $stmt = $db->query($query, ['limit' => $limit]);
        return $stmt ?? [];
    }
}
