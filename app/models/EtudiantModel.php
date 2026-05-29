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
        return $this->findAll('etudiant');
    }

    /**
     * Récupère un étudiant par son ID
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getStudentById($idUtilisateur)
    {
        return $this->findById('etudiant', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Récupère un étudiant complet avec ses données utilisateur
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getStudentWithUser($idUtilisateur)
    {
        $db = $this;
        $query = "
            SELECT u.*, e.*
            FROM utilisateur u
            JOIN etudiant e ON u.idUtilisateur = e.idUtilisateur
            WHERE u.idUtilisateur = :idUtilisateur
        ";
        $stmt = $db->query($query, ['idUtilisateur' => $idUtilisateur]);
        return $stmt ? $stmt[0] ?? null : null;
    }

    /**
     * Récupère les étudiants par localisation
     * @param string $localisation
     * @return array
     */
    public function getStudentsByLocation($localisation)
    {
        return $this->findWhere('etudiant', 'localisation', $localisation);
    }

    /**
     * Crée un nouveau profil étudiant
     * @param int $idUtilisateur
     * @param array $data Données spécifiques à l'étudiant
     * @return bool
     */
    public function createStudent($idUtilisateur, $data)
    {
        $data['idUtilisateur'] = $idUtilisateur;
        return $this->create('etudiant', $data);
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
     * @param array $userData Données utilisateur
     * @param array $studentData Données étudiantes
     * @return int|false ID de l'utilisateur en cas de succès, false sinon
     */
    public function registerStudent($userData, $studentData)
    {
        // 1. Insérer dans la table 'utilisateur'
        $sqlUser = "INSERT INTO utilisateur (nom, prenom, email, mdp, role, date_acceptation_cgu)
                    VALUES (?, ?, ?, ?, ?, ?)";

        $passwordHash = password_hash($userData['mdp'], PASSWORD_BCRYPT);

        $this->insert($sqlUser, [
            $userData['nom'],
            $userData['prenom'],
            $userData['email'],
            $passwordHash,
            $userData['role'],
            $userData['date_acceptation_cgu']
        ]);

        // 2. Récupérer l'ID généré
        $userId = $this->lastInsertId();

        // 3. Insérer dans la table 'etudiant' avec cet ID
        $sqlEtudiant = "INSERT INTO etudiant (idUtilisateur, dateNaissance, localisation) VALUES (?, ?, ?)";
        $this->insert($sqlEtudiant, [
            $userId,
            $studentData['dateNaissance'],
            $studentData['localisation']
        ]);

        return $userId;
    }
}