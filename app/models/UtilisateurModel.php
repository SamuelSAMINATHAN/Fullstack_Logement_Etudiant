<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class UtilisateurModel extends Model
{
    /**
     * Récupère tous les utilisateurs avec les détails (étudiant/bailleur)
     * @return array
     */
    public function getAllUsersWithDetails()
    {
        $sql = "SELECT u.*, 
                       e.dateNaissance, e.localisation,
                       b.estVerifie, b.estShadowban
                FROM utilisateur u
                LEFT JOIN etudiant e ON u.idUtilisateur = e.idUtilisateur
                LEFT JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur
                ORDER BY u.idUtilisateur DESC";
        return $this->select($sql);
    }

    /**
     * Récupère les bailleurs en attente de vérification ou à vérifier
     * @return array
     */
    public function getBailleursToVerify()
    {
        $sql = "SELECT u.*, b.estVerifie, b.estShadowban
                FROM utilisateur u
                JOIN bailleur b ON u.idUtilisateur = b.idUtilisateur
                WHERE b.estVerifie = 0
                ORDER BY u.idUtilisateur DESC";
        return $this->select($sql);
    }

    /**
     * Récupère un utilisateur par son ID
     * @param int $idUtilisateur
     * @return array|null
     */
    public function getUserById($idUtilisateur)
    {
        return $this->findById('utilisateur', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Récupère un utilisateur par son email
     * @param string $email
     * @return array|null
     */
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        return $this->selectOne($sql, [$email]);
    }

    /**
     * Récupère tous les utilisateurs ayant un rôle spécifique
     * @param string $role 'etudiant' ou 'bailleur'
     * @return array
     */
    public function getUsersByRole($role)
    {
        return $this->findWhere('utilisateur', 'role', $role);
    }

    /**
     * Crée un nouvel utilisateur
     * @param array $data Données de l'utilisateur
     * @return int ID du nouvel utilisateur
     */
    public function createUser($data)
    {
        // Hasher le mot de passe
        if (isset($data['mdp'])) {
            $data['mdp'] = Security::hashPassword($data['mdp']);
        }

        // Ajouter la date d'acceptation des CGU si absente
        if (!isset($data['date_acceptation_cgu'])) {
            $data['date_acceptation_cgu'] = date('Y-m-d H:i:s');
        }

        return $this->create('utilisateur', $data);
    }

    /**
     * Met à jour un utilisateur
     * @param int $idUtilisateur
     * @param array $data Données à mettre à jour
     * @return bool
     */
    public function updateUser($idUtilisateur, $data)
    {
        // Hasher le mot de passe s'il est présent
        if (isset($data['mdp'])) {
            $data['mdp'] = Security::hashPassword($data['mdp']);
        }

        return $this->updateById('utilisateur', 'idUtilisateur', $idUtilisateur, $data);
    }

    /**
     * Met à jour la dernière connexion d'un utilisateur
     * @param int $idUtilisateur
     * @return bool
     */
    public function updateLastLogin($idUtilisateur)
    {
        $data = ['derniere_connexion' => date('Y-m-d H:i:s')];
        return $this->updateById('utilisateur', 'idUtilisateur', $idUtilisateur, $data);
    }

    /**
     * Active le 2FA pour un utilisateur
     * @param int $idUtilisateur
     * @param string $secret Clé secrète TOTP
     * @return bool
     */
    public function enableTwoFactor($idUtilisateur, $secret)
    {
        $data = [
            'deux_facteurs_secret' => $secret,
            'est_2fa_active' => 1
        ];
        return $this->updateById('utilisateur', 'idUtilisateur', $idUtilisateur, $data);
    }

    /**
     * Désactive le 2FA pour un utilisateur
     * @param int $idUtilisateur
     * @return bool
     */
    public function disableTwoFactor($idUtilisateur)
    {
        $data = [
            'deux_facteurs_secret' => null,
            'est_2fa_active' => 0
        ];
        return $this->updateById('utilisateur', 'idUtilisateur', $idUtilisateur, $data);
    }

    /**
     * Marque une demande de suppression de compte
     * @param int $idUtilisateur
     * @return bool
     */
    public function requestAccountDeletion($idUtilisateur)
    {
        $data = ['demande_suppression' => 1];
        return $this->updateById('utilisateur', 'idUtilisateur', $idUtilisateur, $data);
    }

    /**
     * Supprime un utilisateur et ses données associées (etudiant/bailleur)
     * @param int $idUtilisateur
     * @return bool
     */
    public function deleteUser($idUtilisateur)
    {
        // La suppression dans 'etudiant' ou 'bailleur' est gérée par ON DELETE CASCADE
        return $this->deleteById('utilisateur', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Vérifie l'unicité d'un email
     * @param string $email
     * @return bool True si l'email n'existe pas encore
     */
    public function isEmailUnique($email)
    {
        $user = $this->getUserByEmail($email);
        return empty($user);
    }
}
