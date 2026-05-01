<?php

namespace App\Models;

use App\Core\Model;

class InformationLegaleModel extends Model
{
    /**
     * Récupère toutes les informations légales
     * @return array
     */
    public function getAllInformation()
    {
        return $this->findAll('informationlegale');
    }

    /**
     * Récupère une information légale par son ID
     * @param int $idInfo
     * @return array|null
     */
    public function getInformationById($idInfo)
    {
        return $this->findById('informationlegale', 'idInfo', $idInfo);
    }

    /**
     * Récupère une information légale par son titre
     * @param string $titre 'CGU' | 'Mentions légales' | 'Politique de confidentialité'
     * @return array|null
     */
    public function getInformationByTitle($titre)
    {
        return $this->findWhere('informationlegale', 'titre', $titre);
    }

    /**
     * Récupère les CGU
     * @return array|null
     */
    public function getCGU()
    {
        return $this->getInformationByTitle('CGU');
    }

    /**
     * Récupère les mentions légales
     * @return array|null
     */
    public function getLegalNotice()
    {
        return $this->getInformationByTitle('Mentions légales');
    }

    /**
     * Récupère la politique de confidentialité
     * @return array|null
     */
    public function getPrivacyPolicy()
    {
        return $this->getInformationByTitle('Politique de confidentialité');
    }

    /**
     * Crée une nouvelle information légale
     * @param array $data
     * @return int ID de la nouvelle information
     */
    public function createInformation($data)
    {
        return $this->create('informationlegale', $data);
    }

    /**
     * Met à jour une information légale
     * @param int $idInfo
     * @param array $data
     * @return bool
     */
    public function updateInformation($idInfo, $data)
    {
        return $this->updateById('informationlegale', 'idInfo', $idInfo, $data);
    }

    /**
     * Met à jour ou crée une information par titre (upsert)
     * @param string $titre
     * @param string $contenu
     * @return bool|int
     */
    public function upsertByTitle($titre, $contenu)
    {
        $existingInfo = $this->getInformationByTitle($titre);

        if ($existingInfo) {
            return $this->updateInformation($existingInfo['idInfo'], [
                'contenu' => $contenu,
                'dateMiseAJour' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->createInformation([
                'titre' => $titre,
                'contenu' => $contenu
            ]);
        }
    }

    /**
     * Supprime une information légale
     * @param int $idInfo
     * @return bool
     */
    public function deleteInformation($idInfo)
    {
        return $this->deleteById('informationlegale', 'idInfo', $idInfo);
    }

    /**
     * Récupère toutes les informations légales pour le frontend
     * @return array
     */
    public function getAllForFrontend()
    {
        return $this->getAllInformation();
    }

    /**
     * Compte le total des informations légales
     * @return int
     */
    public function countInformation()
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM informationlegale";
        $stmt = $db->query($query);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Vérifie si une information existe par titre
     * @param string $titre
     * @return bool
     */
    public function existsByTitle($titre)
    {
        return $this->getInformationByTitle($titre) !== null;
    }
}
