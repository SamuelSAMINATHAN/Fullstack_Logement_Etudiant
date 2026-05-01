<?php

namespace App\Models;

use App\Core\Model;

class CandidatureModel extends Model
{
    /**
     * Récupère toutes les candidatures
     * @return array
     */
    public function getAllApplications()
    {
        return $this->findAll('candidature');
    }

    /**
     * Récupère une candidature par son ID
     * @param int $idCandidature
     * @return array|null
     */
    public function getApplicationById($idCandidature)
    {
        return $this->findById('candidature', 'idCandidature', $idCandidature);
    }

    /**
     * Récupère les candidatures d'un étudiant
     * @param int $idEtudiant
     * @return array
     */
    public function getApplicationsByStudent($idEtudiant)
    {
        return $this->findWhere('candidature', 'idEtudiant', $idEtudiant);
    }

    /**
     * Récupère les candidatures pour une annonce
     * @param int $idAnnonce
     * @return array
     */
    public function getApplicationsByAnnouncement($idAnnonce)
    {
        return $this->findWhere('candidature', 'idAnnonce', $idAnnonce);
    }

    /**
     * Récupère les candidatures avec un statut spécifique
     * @param string $statut 'Envoyée' | 'Vue' | 'Acceptée' | 'Refusée'
     * @return array
     */
    public function getApplicationsByStatus($statut)
    {
        return $this->findWhere('candidature', 'statut', $statut);
    }

    /**
     * Vérifie si un étudiant a déjà postulé à une annonce
     * @param int $idEtudiant
     * @param int $idAnnonce
     * @return array|null
     */
    public function getApplicationByStudentAndAnnouncement($idEtudiant, $idAnnonce)
    {
        $db = $this;
        $query = "
            SELECT * FROM candidature
            WHERE idEtudiant = :idEtudiant AND idAnnonce = :idAnnonce
        ";
        $stmt = $db->query($query, ['idEtudiant' => $idEtudiant, 'idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0] ?? null) : null;
    }

    /**
     * Crée une nouvelle candidature
     * @param array $data
     * @return int ID de la nouvelle candidature
     */
    public function createApplication($data)
    {
        return $this->create('candidature', $data);
    }

    /**
     * Met à jour une candidature
     * @param int $idCandidature
     * @param array $data
     * @return bool
     */
    public function updateApplication($idCandidature, $data)
    {
        return $this->updateById('candidature', 'idCandidature', $idCandidature, $data);
    }

    /**
     * Change le statut d'une candidature
     * @param int $idCandidature
     * @param string $statut
     * @return bool
     */
    public function updateApplicationStatus($idCandidature, $statut)
    {
        return $this->updateById('candidature', 'idCandidature', $idCandidature, ['statut' => $statut]);
    }

    /**
     * Supprime une candidature
     * @param int $idCandidature
     * @return bool
     */
    public function deleteApplication($idCandidature)
    {
        return $this->deleteById('candidature', 'idCandidature', $idCandidature);
    }

    /**
     * Récupère les candidatures pour une annonce avec les données des étudiants
     * @param int $idAnnonce
     * @return array
     */
    public function getApplicationsWithStudents($idAnnonce)
    {
        $db = $this;
        $query = "
            SELECT c.*, u.nom, u.prenom, u.email, e.dateNaissance, e.localisation
            FROM candidature c
            JOIN etudiant e ON c.idEtudiant = e.idUtilisateur
            JOIN utilisateur u ON e.idUtilisateur = u.idUtilisateur
            WHERE c.idAnnonce = :idAnnonce
            ORDER BY c.dateEnvoi DESC
        ";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ?? [];
    }

    /**
     * Compte le nombre de candidatures pour une annonce
     * @param int $idAnnonce
     * @return int
     */
    public function countApplicationsByAnnouncement($idAnnonce)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM candidature WHERE idAnnonce = :idAnnonce";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Compte les candidatures d'un étudiant avec un statut
     * @param int $idEtudiant
     * @param string $statut
     * @return int
     */
    public function countApplicationsByStudentAndStatus($idEtudiant, $statut)
    {
        $db = $this;
        $query = "
            SELECT COUNT(*) as count FROM candidature
            WHERE idEtudiant = :idEtudiant AND statut = :statut
        ";
        $stmt = $db->query($query, ['idEtudiant' => $idEtudiant, 'statut' => $statut]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }
}
