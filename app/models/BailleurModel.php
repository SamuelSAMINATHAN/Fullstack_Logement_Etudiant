<?php

namespace App\Models;

use App\Core\Model;

class BailleurModel extends Model
{
    /**
     * Récupère tous les bailleurs
     * @return array
     */
    public function getAllLandlords()
    {
        return $this->findAll('bailleur');
    }

    /**
     * Récupère un bailleur par son ID
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getLandlordById($idUtilisateur)
    {
        return $this->findById('bailleur', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Récupère un bailleur complet avec ses données utilisateur
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getLandlordWithUser($idUtilisateur)
    {
        $db = $this;
        $query = "
            SELECT u.*, b.*
            FROM utilisateur u
            JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur
            WHERE u.idUtilisateur = :idUtilisateur
        ";
        $stmt = $db->query($query, ['idUtilisateur' => $idUtilisateur]);
        return $stmt ? $stmt[0] ?? null : null;
    }

    /**
     * Récupère les bailleurs vérifiés
     * @return array
     */
    public function getVerifiedLandlords()
    {
        return $this->findWhere('bailleur', 'estVerifie', 1);
    }

    /**
     * Récupère les bailleurs non shadowbannés
     * @return array
     */
    public function getActiveLandlords()
    {
        return $this->findWhere('bailleur', 'estShadowban', 0);
    }

    /**
     * Crée un nouveau profil bailleur
     * @param int $idUtilisateur
     * @param array $data Données spécifiques au bailleur (optionnel)
     * @return bool
     */
    public function createLandlord($idUtilisateur, $data = [])
    {
        // S'assurer que l'ID utilisateur est présent
        $data['idUtilisateur'] = $idUtilisateur;

        return $this->create('bailleur', $data);
    }

    /**
     * Met à jour un profil bailleur
     * @param int $idUtilisateur
     * @param array $data
     * @return bool
     */
    public function updateLandlord($idUtilisateur, $data)
    {
        return $this->updateById('bailleur', 'idUtilisateur', $idUtilisateur, $data);
    }

    /**
     * Vérifie un bailleur (affiche le badge)
     * @param int $idUtilisateur
     * @return bool
     */
    public function verifyLandlord($idUtilisateur)
    {
        return $this->updateById('bailleur', 'idUtilisateur', $idUtilisateur, ['estVerifie' => 1]);
    }

    /**
     * Retire la vérification d'un bailleur
     * @param int $idUtilisateur
     * @return bool
     */
    public function unverifyLandlord($idUtilisateur)
    {
        return $this->updateById('bailleur', 'idUtilisateur', $idUtilisateur, ['estVerifie' => 0]);
    }

    /**
     * Shadowban un bailleur
     * @param int $idUtilisateur
     * @return bool
     */
    public function shadowbanLandlord($idUtilisateur)
    {
        return $this->updateById('bailleur', 'idUtilisateur', $idUtilisateur, ['estShadowban' => 1]);
    }

    /**
     * Retire le shadowban d'un bailleur
     * @param int $idUtilisateur
     * @return bool
     */
    public function unbanLandlord($idUtilisateur)
    {
        return $this->updateById('bailleur', 'idUtilisateur', $idUtilisateur, ['estShadowban' => 0]);
    }

    /**
     * Supprime un profil bailleur
     * @param int $idUtilisateur
     * @return bool
     */
    public function deleteLandlord($idUtilisateur)
    {
        return $this->deleteById('bailleur', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Enregistre un bailleur complet (utilisateur + bailleur)
     * Utilise une transaction
     * @param array $userData Données de l'utilisateur
     * @param array $landlordData Données du bailleur (optionnel)
     * @return int|false ID du bailleur en cas de succès, false sinon
     */
    public function registerLandlord($userData, $landlordData = [])
    {
        try {
            $this->beginTransaction();

            // Créer l'utilisateur avec le rôle bailleur
            $userData['role'] = 'bailleur';
            $utilisateurModel = new UtilisateurModel();
            $idUtilisateur = $utilisateurModel->createUser($userData);

            if (!$idUtilisateur) {
                $this->rollBack();
                return false;
            }

            // Créer le profil bailleur
            $landlordData['idUtilisateur'] = $idUtilisateur;
            $result = $this->create('bailleur', $landlordData);

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
