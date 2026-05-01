<?php

namespace App\Models;

use App\Core\Model;

class AlerteModel extends Model
{
    /**
     * Récupère toutes les alertes
     * @return array
     */
    public function getAllAlerts()
    {
        return $this->findAll('alerte');
    }

    /**
     * Récupère une alerte par son ID
     * @param int $idAlerte
     * @return array|null
     */
    public function getAlertById($idAlerte)
    {
        return $this->findById('alerte', 'idAlerte', $idAlerte);
    }

    /**
     * Récupère les alertes d'un étudiant
     * @param int $idEtudiant
     * @return array
     */
    public function getAlertsByStudent($idEtudiant)
    {
        return $this->findWhere('alerte', 'idEtudiant', $idEtudiant);
    }

    /**
     * Récupère les alertes pour une localisation
     * @param string $localisation
     * @return array
     */
    public function getAlertsByLocation($localisation)
    {
        return $this->findWhere('alerte', 'localisation', $localisation);
    }

    /**
     * Crée une nouvelle alerte
     * @param array $data
     * @return int ID de la nouvelle alerte
     */
    public function createAlert($data)
    {
        return $this->create('alerte', $data);
    }

    /**
     * Met à jour une alerte
     * @param int $idAlerte
     * @param array $data
     * @return bool
     */
    public function updateAlert($idAlerte, $data)
    {
        return $this->updateById('alerte', 'idAlerte', $idAlerte, $data);
    }

    /**
     * Supprime une alerte
     * @param int $idAlerte
     * @return bool
     */
    public function deleteAlert($idAlerte)
    {
        return $this->deleteById('alerte', 'idAlerte', $idAlerte);
    }

    /**
     * Supprime toutes les alertes d'un étudiant
     * @param int $idEtudiant
     * @return bool
     */
    public function deleteAlertsByStudent($idEtudiant)
    {
        $db = $this;
        $query = "DELETE FROM alerte WHERE idEtudiant = :idEtudiant";
        return $db->execute($query, ['idEtudiant' => $idEtudiant]);
    }

    /**
     * Compte les alertes d'un étudiant
     * @param int $idEtudiant
     * @return int
     */
    public function countAlertsByStudent($idEtudiant)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM alerte WHERE idEtudiant = :idEtudiant";
        $stmt = $db->query($query, ['idEtudiant' => $idEtudiant]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }

    /**
     * Récupère les annonces correspondant à une alerte
     * @param int $idAlerte
     * @return array
     */
    public function getMatchingAnnouncements($idAlerte)
    {
        $alert = $this->getAlertById($idAlerte);
        if (!$alert) {
            return [];
        }

        $db = $this;
        $query = "SELECT * FROM annonce WHERE 1=1";
        $params = [];

        if (!empty($alert['localisation'])) {
            $query .= " AND localisation LIKE :localisation";
            $params['localisation'] = '%' . $alert['localisation'] . '%';
        }

        if (!empty($alert['budgetMax'])) {
            $query .= " AND prix <= :budgetMax";
            $params['budgetMax'] = $alert['budgetMax'];
        }

        if ($alert['colocation']) {
            $query .= " AND estColocation = 1";
        }

        $query .= " ORDER BY datePublication DESC";

        $stmt = $db->query($query, $params);
        return $stmt ?? [];
    }

    /**
     * Récupère les alertes à notifier pour une nouvelle annonce
     * @param int $idAnnonce
     * @return array
     */
    public function getAlertsForAnnouncement($idAnnonce)
    {
        $annonceModel = new AnnonceModel();
        $annonce = $annonceModel->getAnnouncementById($idAnnonce);

        if (!$annonce) {
            return [];
        }

        $db = $this;
        $query = "
            SELECT * FROM alerte
            WHERE (localisation IS NULL OR localisation = :localisation OR :localisation LIKE CONCAT('%', localisation, '%'))
            AND (budgetMax IS NULL OR budgetMax >= :prix)
            AND (colocation = 0 OR :estColocation = 1)
        ";
        $params = [
            'localisation' => $annonce['localisation'],
            'prix' => $annonce['prix'],
            'estColocation' => $annonce['estColocation']
        ];

        $stmt = $db->query($query, $params);
        return $stmt ?? [];
    }

    /**
     * Désactive les alertes colocation
     * @return bool
     */
    public function disableAllColocationAlerts()
    {
        $db = $this;
        $query = "UPDATE alerte SET colocation = 0 WHERE colocation = 1";
        return $db->execute($query);
    }
}
