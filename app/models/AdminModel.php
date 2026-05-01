<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class AdminModel extends Model
{
    /**
     * Récupère tous les administrateurs
     * @return array
     */
    public function getAllAdmins()
    {
        return $this->findAll('administrateur');
    }

    /**
     * Récupère un administrateur par son ID
     * @param int $idAdmin
     * @return array|null
     */
    public function getAdminById($idAdmin)
    {
        return $this->findById('administrateur', 'idAdmin', $idAdmin);
    }

    /**
     * Récupère un administrateur par son login
     * @param string $login
     * @return array|null
     */
    public function getAdminByLogin($login)
    {
        return $this->findWhere('administrateur', 'login', $login);
    }

    /**
     * Crée un nouvel administrateur
     * @param array $data Données de l'administrateur
     * @return int ID du nouvel administrateur
     */
    public function createAdmin($data)
    {
        // Hasher le mot de passe
        if (isset($data['motDePasse'])) {
            $data['motDePasse'] = Security::hashPassword($data['motDePasse']);
        }

        return $this->create('administrateur', $data);
    }

    /**
     * Met à jour un administrateur
     * @param int $idAdmin
     * @param array $data
     * @return bool
     */
    public function updateAdmin($idAdmin, $data)
    {
        // Hasher le mot de passe s'il est présent
        if (isset($data['motDePasse'])) {
            $data['motDePasse'] = Security::hashPassword($data['motDePasse']);
        }

        return $this->updateById('administrateur', 'idAdmin', $idAdmin, $data);
    }

    /**
     * Supprime un administrateur
     * @param int $idAdmin
     * @return bool
     */
    public function deleteAdmin($idAdmin)
    {
        return $this->deleteById('administrateur', 'idAdmin', $idAdmin);
    }

    /**
     * Vérifie l'unicité d'un login
     * @param string $login
     * @return bool True si le login n'existe pas encore
     */
    public function isLoginUnique($login)
    {
        $admin = $this->getAdminByLogin($login);
        return $admin === null;
    }
}
