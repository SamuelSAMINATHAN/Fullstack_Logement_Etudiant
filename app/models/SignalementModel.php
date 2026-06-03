<?php

namespace App\Models;

use App\Core\Model;

class SignalementModel extends Model
{
    /**
     * Récupère tous les signalements avec détails (étudiant, annonce, bailleur)
     * @param string|null $filterStatut Filtrer par statut ('En attente', 'Traité', 'Rejeté')
     * @return array
     */
    public function getAllReportsWithDetails($filterStatut = null)
    {
        $sql = "SELECT s.*, 
                       u.nom as student_nom, u.prenom as student_prenom,
                       a.titre as annonce_titre, a.idAnnonce,
                       ub.nom as bailleur_nom, ub.idUtilisateur as idBailleur
                FROM signalement s
                JOIN utilisateur u ON s.idEtudiant = u.idUtilisateur
                JOIN annonce a ON s.idAnnonce = a.idAnnonce
                JOIN bailleur b ON a.idBailleur = b.idUtilisateur
                JOIN utilisateur ub ON b.idUtilisateur = ub.idUtilisateur";
        
        $params = [];
        if ($filterStatut) {
            $sql .= " WHERE s.statut = ?";
            $params[] = $filterStatut;
        }
        
        $sql .= " ORDER BY s.dateSignalement DESC";
        
        return $this->select($sql, $params);
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
     * Compte les signalements en attente
     * @return int
     */
    public function countPendingReports()
    {
        $sql = "SELECT COUNT(*) as count FROM signalement WHERE statut = 'En attente'";
        $result = $this->selectOne($sql);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Compte les signalements pour une annonce
     * @param int $idAnnonce
     * @return int
     */
    public function countReportsByAnnouncement($idAnnonce)
    {
        $sql = "SELECT COUNT(*) as count FROM signalement WHERE idAnnonce = ?";
        $result = $this->selectOne($sql, [$idAnnonce]);
        return (int)($result['count'] ?? 0);
    }
}
