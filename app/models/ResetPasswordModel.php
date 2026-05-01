<?php

namespace App\Models;

use App\Core\Model;

class ResetPasswordModel extends Model
{
    /**
     * Récupère tous les tokens de réinitialisation
     * @return array
     */
    public function getAllTokens()
    {
        return $this->findAll('reset_password');
    }

    /**
     * Récupère un token par son ID
     * @param int $idReset
     * @return array|null
     */
    public function getTokenById($idReset)
    {
        return $this->findById('reset_password', 'idReset', $idReset);
    }

    /**
     * Récupère un token par sa valeur
     * @param string $token
     * @return array|null
     */
    public function getTokenByValue($token)
    {
        return $this->findWhere('reset_password', 'token', $token);
    }

    /**
     * Récupère les tokens d'un utilisateur
     * @param int $idUtilisateur
     * @return array
     */
    public function getTokensByUser($idUtilisateur)
    {
        return $this->findWhere('reset_password', 'idUtilisateur', $idUtilisateur);
    }

    /**
     * Crée un nouveau token de réinitialisation
     * @param int $idUtilisateur
     * @param string $token
     * @param int $expirationMinutes Durée d'expiration en minutes (par défaut 60)
     * @return int ID du token
     */
    public function createToken($idUtilisateur, $token, $expirationMinutes = 60)
    {
        $data = [
            'idUtilisateur' => $idUtilisateur,
            'token' => $token,
            'expiration' => date('Y-m-d H:i:s', strtotime("+$expirationMinutes minutes")),
            'utilise' => 0
        ];
        return $this->create('reset_password', $data);
    }

    /**
     * Vérifie si un token est valide (non expiré et non utilisé)
     * @param string $token
     * @return bool
     */
    public function isTokenValid($token)
    {
        $resetRecord = $this->getTokenByValue($token);

        if (!$resetRecord) {
            return false;
        }

        // Vérifier que le token n'a pas été utilisé
        if ($resetRecord['utilise'] == 1) {
            return false;
        }

        // Vérifier que le token n'a pas expiré
        $expiration = strtotime($resetRecord['expiration']);
        if ($expiration < time()) {
            return false;
        }

        return true;
    }

    /**
     * Marque un token comme utilisé
     * @param string $token
     * @return bool
     */
    public function markTokenAsUsed($token)
    {
        $resetRecord = $this->getTokenByValue($token);

        if (!$resetRecord) {
            return false;
        }

        return $this->updateById('reset_password', 'idReset', $resetRecord['idReset'], ['utilise' => 1]);
    }

    /**
     * Met à jour un token
     * @param int $idReset
     * @param array $data
     * @return bool
     */
    public function updateToken($idReset, $data)
    {
        return $this->updateById('reset_password', 'idReset', $idReset, $data);
    }

    /**
     * Supprime un token
     * @param int $idReset
     * @return bool
     */
    public function deleteToken($idReset)
    {
        return $this->deleteById('reset_password', 'idReset', $idReset);
    }

    /**
     * Supprime tous les tokens d'un utilisateur
     * @param int $idUtilisateur
     * @return bool
     */
    public function deleteTokensByUser($idUtilisateur)
    {
        $db = $this;
        $query = "DELETE FROM reset_password WHERE idUtilisateur = :idUtilisateur";
        return $db->execute($query, ['idUtilisateur' => $idUtilisateur]);
    }

    /**
     * Supprime tous les tokens expirés
     * @return bool
     */
    public function deleteExpiredTokens()
    {
        $db = $this;
        $query = "DELETE FROM reset_password WHERE expiration < NOW()";
        return $db->execute($query);
    }

    /**
     * Supprime tous les tokens utilisés
     * @return bool
     */
    public function deleteUsedTokens()
    {
        $db = $this;
        $query = "DELETE FROM reset_password WHERE utilise = 1";
        return $db->execute($query);
    }

    /**
     * Récupère l'utilisateur associé à un token
     * @param string $token
     * @return array|null
     */
    public function getUserByToken($token)
    {
        $resetRecord = $this->getTokenByValue($token);

        if (!$resetRecord) {
            return null;
        }

        $utilisateurModel = new UtilisateurModel();
        return $utilisateurModel->getUserById($resetRecord['idUtilisateur']);
    }

    /**
     * Compte les tokens non expirés et non utilisés d'un utilisateur
     * @param int $idUtilisateur
     * @return int
     */
    public function countValidTokensByUser($idUtilisateur)
    {
        $db = $this;
        $query = "
            SELECT COUNT(*) as count FROM reset_password
            WHERE idUtilisateur = :idUtilisateur
              AND utilise = 0
              AND expiration > NOW()
        ";
        $stmt = $db->query($query, ['idUtilisateur' => $idUtilisateur]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }
}
