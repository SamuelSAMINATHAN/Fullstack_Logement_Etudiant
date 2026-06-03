<?php

namespace App\Models;

use App\Core\Model;

class EtudiantModel extends Model
{
    /**
     * Récupère tous les étudiants
     * @return array
     */
    public function getAllStudents()
    {
        $sql = "SELECT u.*, e.*
                FROM utilisateur u
                JOIN etudiant e ON u.idUtilisateur = e.idUtilisateur";
        return $this->select($sql);
    }

    /**
     * Récupère un étudiant par son ID
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getStudentById($idUtilisateur)
    {
        $sql = "SELECT u.*, e.*
                FROM utilisateur u
                JOIN etudiant e ON u.idUtilisateur = e.idUtilisateur
                WHERE u.idUtilisateur = ?";
        $result = $this->select($sql, [$idUtilisateur]);
        return $result ? $result[0] : null;
    }

    /**
     * Récupère un étudiant complet avec ses données utilisateur
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getStudentWithUser($idUtilisateur)
    {
        $sql = "SELECT u.*, e.*
                FROM utilisateur u
                JOIN etudiant e ON u.idUtilisateur = e.idUtilisateur
                WHERE u.idUtilisateur = ?";
        $result = $this->select($sql, [$idUtilisateur]);
        return $result ? $result[0] : null;
    }

    /**
     * Récupère les étudiants par localisation
     * @param string $localisation
     * @return array
     */
    public function getStudentsByLocation($localisation)
    {
        $sql = "SELECT u.*, e.*
                FROM utilisateur u
                JOIN etudiant e ON u.idUtilisateur = e.idUtilisateur
                WHERE e.localisation LIKE ?";
        return $this->select($sql, ['%' . $localisation . '%']);
    }

    /**
     * Crée un nouveau profil étudiant (si l'utilisateur existe déjà)
     * @param int $idUtilisateur
     * @param array $data Données spécifiques à l'étudiant
     * @return bool
     */
    public function createStudent($idUtilisateur, $data)
    {
        $studentData = [
            'idUtilisateur' => $idUtilisateur,
            'dateNaissance' => $data['dateNaissance'] ?? null,
            'localisation' => $data['localisation'] ?? null
        ];
        return $this->create('etudiant', $studentData);
    }

    /**
     * Met à jour un profil étudiant
     * @param int $idUtilisateur
     * @param array $data
     * @return bool
     */
    public function updateStudent($idUtilisateur, $data)
    {
        return $this->updateById('etudiant', 'idUtilisateur', $idUtilisateur, $data);
    }

    /**
     * Supprime un profil étudiant
     * @param int $idUtilisateur
     * @return bool
     */
    public function deleteStudent($idUtilisateur)
    {
        return $this->deleteById('etudiant', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Enregistre un étudiant complet (utilisateur + étudiant)
     * @param array $userData Données de l'utilisateur (nom, prenom, email, mdp, role, date_acceptation_cgu)
     * @param array $studentData Données spécifiques à l'étudiant (dateNaissance, localisation)
     * @return int|false ID de l'utilisateur en cas de succès, false sinon
     */
    public function registerStudent($userData, $studentData)
    {
        // 1. Créer l'utilisateur dans la table `utilisateur`
        $userId = $this->create('utilisateur', [
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email' => $userData['email'],
            'mdp' => password_hash($userData['mdp'], PASSWORD_BCRYPT),
            'role' => $userData['role'],
            'date_acceptation_cgu' => $userData['date_acceptation_cgu'] ?? date('Y-m-d H:i:s')
        ]);

        if (!$userId) {
            return false;
        }

        // 2. Créer le profil étudiant dans la table `etudiant`
        $this->create('etudiant', [
            'idUtilisateur' => $userId,
            'dateNaissance' => $studentData['dateNaissance'],
            'localisation' => $studentData['localisation']
        ]);

        return $userId;
    }

    /**
     * Vérifie si un utilisateur est un étudiant
     * @param int $idUtilisateur
     * @return bool
     */
    public function isStudent($idUtilisateur)
    {
        $sql = "SELECT COUNT(*) as count FROM etudiant WHERE idUtilisateur = ?";
        $result = $this->selectOne($sql, [$idUtilisateur]);
        return $result && $result['count'] > 0;
    }
}