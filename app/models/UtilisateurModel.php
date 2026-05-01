<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class UtilisateurModel extends Model
{
    /**
     * Récupère tous les utilisateurs
     * @return array
     */
    public function getAllUsers()
    {
        return $this->findAll('utilisateur');
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
        return $this->findWhere('utilisateur', 'email', $email);
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
     * Supprime un utilisateur
     * @param int $idUtilisateur
     * @return bool
     */
    public function deleteUser($idUtilisateur)
    {
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
        return $user === null;
    }
}
