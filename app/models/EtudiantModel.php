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
        // S'assurer que l'ID utilisateur est présent
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
     * Utilise une transaction
     * @param array $userDataData des données utilisateur
     * @param array $studentData Données étudiantes
     * @return int|false ID de l'étudiant en cas de succès, false sinon
     */
    public function registerStudent($userData, $studentData)
    {
        try {
            $this->beginTransaction();

            // Créer l'utilisateur
            $utilisateurModel = new UtilisateurModel();
            $idUtilisateur = $utilisateurModel->createUser($userData);

            if (!$idUtilisateur) {
                $this->rollBack();
                return false;
            }

            // Créer le profil étudiant
            $studentData['idUtilisateur'] = $idUtilisateur;
            $result = $this->create('etudiant', $studentData);

            if (!$result) {
                $this->rollBack();
                return false;
            }

            $this->commit();
            return $idUtilisateur;
        } catch (\Exception $e) {
            $this->rollBack();
            return false;
        }
    }
}
