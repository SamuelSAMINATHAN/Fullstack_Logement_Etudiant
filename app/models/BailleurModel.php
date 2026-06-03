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
        $sql = "SELECT u.*, b.*
                FROM utilisateur u
                JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur";
        return $this->select($sql);
    }

    /**
     * Récupère un bailleur par son ID
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getLandlordById($idUtilisateur)
    {
        $sql = "SELECT u.*, b.*
                FROM utilisateur u
                JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur
                WHERE u.idUtilisateur = ?";
        $result = $this->select($sql, [$idUtilisateur]);
        return $result ? $result[0] : null;
    }

    /**
     * Récupère un bailleur complet avec ses données utilisateur
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getLandlordWithUser($idUtilisateur)
    {
        $sql = "SELECT u.*, b.*
                FROM utilisateur u
                JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur
                WHERE u.idUtilisateur = ?";
        $result = $this->select($sql, [$idUtilisateur]);
        return $result ? $result[0] : null;
    }

    /**
     * Récupère les bailleurs vérifiés
     * @return array
     */
    public function getVerifiedLandlords()
    {
        $sql = "SELECT u.*, b.*
                FROM utilisateur u
                JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur
                WHERE b.estVerifie = 1";
        return $this->select($sql);
    }

    /**
     * Récupère les bailleurs non shadowbannés
     * @return array
     */
    public function getActiveLandlords()
    {
        $sql = "SELECT u.*, b.*
                FROM utilisateur u
                JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur
                WHERE b.estShadowban = 0";
        return $this->select($sql);
    }

    /**
     * Enregistre un nouveau bailleur
     * @param array $userData Données de l'utilisateur (nom, prenom, email, mdp, role, date_acceptation_cgu)
     * @return int|false ID du nouvel utilisateur ou false en cas d'erreur
     */
    public function registerLandlord($userData)
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

        // 2. Créer le profil bailleur dans la table `bailleur`
        $bailleurData = [
            'idUtilisateur' => $userId,
            'estVerifie' => 0,
            'estShadowban' => 0
        ];

        $this->create('bailleur', $bailleurData);

        return $userId;
    }

    /**
     * Crée un nouveau profil bailleur (si l'utilisateur existe déjà)
     * @param int $idUtilisateur
     * @param array $data Données spécifiques au bailleur (optionnel)
     * @return bool
     */
    public function createLandlord($idUtilisateur, $data = [])
    {
        $defaultData = [
            'idUtilisateur' => $idUtilisateur,
            'estVerifie' => 0,
            'estShadowban' => 0
        ];

        $bailleurData = array_merge($defaultData, $data);
        return $this->create('bailleur', $bailleurData);
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
     * @param int $status 1 pour shadowban, 0 pour retirer
     * @return bool
     */
    public function toggleShadowban($idUtilisateur, $status = 1)
    {
        return $this->updateById('bailleur', 'idUtilisateur', $idUtilisateur, ['estShadowban' => $status]);
    }

    /**
     * Supprime un bailleur
     * @param int $idUtilisateur
     * @return bool
     */
    public function deleteLandlord($idUtilisateur)
    {
        return $this->deleteById('bailleur', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Vérifie si un utilisateur est un bailleur
     * @param int $idUtilisateur
     * @return bool
     */
    public function isLandlord($idUtilisateur)
    {
        $sql = "SELECT COUNT(*) as count FROM bailleur WHERE idUtilisateur = ?";
        $result = $this->selectOne($sql, [$idUtilisateur]);
        return $result && $result['count'] > 0;
    }
}