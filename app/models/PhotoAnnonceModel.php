<?php

namespace App\Models;

use App\Core\Model;

class PhotoAnnonceModel extends Model
{
    /**
     * Récupère toutes les photos
     * @return array
     */
    public function getAllPhotos()
    {
        return $this->findAll('photo_annonce');
    }

    /**
     * Récupère une photo par son ID
     * @param int $idPhoto
     * @return array|null
     */
    public function getPhotoById($idPhoto)
    {
        return $this->findById('photo_annonce', 'idPhoto', $idPhoto);
    }

    /**
     * Récupère toutes les photos d'une annonce
     * @param int $idAnnonce
     * @return array
     */
    public function getPhotosByAnnouncement($idAnnonce)
    {
        return $this->findWhere('photo_annonce', 'idAnnonce', $idAnnonce);
    }

    /**
     * Crée une nouvelle photo
     * @param array $data
     * @return int ID de la nouvelle photo
     */
    public function createPhoto($data)
    {
        return $this->create('photo_annonce', $data);
    }

    /**
     * Ajoute plusieurs photos à une annonce
     * @param int $idAnnonce
     * @param array $photos Liste de chemins de photos
     * @return bool
     */
    public function addPhotosToAnnouncement($idAnnonce, $photos)
    {
        try {
            $this->beginTransaction();

            foreach ($photos as $urlPhoto) {
                $data = [
                    'urlPhoto' => $urlPhoto,
                    'idAnnonce' => $idAnnonce
                ];
                if (!$this->create('photo_annonce', $data)) {
                    $this->rollBack();
                    return false;
                }
            }

            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * Met à jour une photo
     * @param int $idPhoto
     * @param array $data
     * @return bool
     */
    public function updatePhoto($idPhoto, $data)
    {
        return $this->updateById('photo_annonce', 'idPhoto', $idPhoto, $data);
    }

    /**
     * Supprime une photo
     * @param int $idPhoto
     * @return bool
     */
    public function deletePhoto($idPhoto)
    {
        return $this->deleteById('photo_annonce', 'idPhoto', $idPhoto);
    }

    /**
     * Supprime toutes les photos d'une annonce
     * @param int $idAnnonce
     * @return bool
     */
    public function deletePhotosByAnnouncement($idAnnonce)
    {
        $db = $this;
        $query = "DELETE FROM photo_annonce WHERE idAnnonce = :idAnnonce";
        return $db->execute($query, ['idAnnonce' => $idAnnonce]);
    }

    /**
     * Compte le nombre de photos d'une annonce
     * @param int $idAnnonce
     * @return int
     */
    public function countPhotosByAnnouncement($idAnnonce)
    {
        $db = $this;
        $query = "SELECT COUNT(*) as count FROM photo_annonce WHERE idAnnonce = :idAnnonce";
        $stmt = $db->query($query, ['idAnnonce' => $idAnnonce]);
        return $stmt ? ($stmt[0]['count'] ?? 0) : 0;
    }
}
