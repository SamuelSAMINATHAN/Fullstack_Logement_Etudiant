<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

class FavorisController extends Controller
{
    private $favorisModel;
    private $annonceModel;
    private $photoAnnonceModel;

    public function __construct()
    {
        $this->requireRole('etudiant');
        $this->favorisModel = $this->model('FavorisModel');
        $this->annonceModel = $this->model('AnnonceModel');
        $this->photoAnnonceModel = $this->model('PhotoAnnonceModel');
    }

    /**
     * Liste les favoris de l'étudiant
     */
    public function index()
    {
        $favoris = $this->favorisModel->getFavoritesByStudent($_SESSION['user_id']);
        
        foreach ($favoris as &$fav) {
            $fav['photos'] = $this->photoAnnonceModel->getPhotosByAnnouncement($fav['idAnnonce']);
        }

        $this->view('user/favorites', ['favoris' => $favoris]);
    }

    /**
     * Liste les favoris de l'étudiant
     */
    public function home() // Tu remplaces index par home
    {
        $favoris = $this->favorisModel->getFavoritesByStudent($_SESSION['user_id']);
        
        foreach ($favoris as &$fav) {
            $fav['photos'] = $this->photoAnnonceModel->getPhotosByAnnouncement($fav['idAnnonce']);
        }

        $this->view('user/favorites', ['favoris' => $favoris]);
    }

    /**
     * Action AJAX : Ajouter/Retirer des favoris
     */
    public function toggle($idAnnonce = null)
    {
        header('Content-Type: application/json');
        
        if (!$idAnnonce) {
            echo json_encode(['success' => false, 'message' => 'ID annonce manquant']);
            return;
        }

        $idEtudiant = $_SESSION['user_id'];
        
        try {
            if ($this->favorisModel->isFavorite($idEtudiant, $idAnnonce)) {
                $this->favorisModel->removeFavorite($idEtudiant, $idAnnonce);
                echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Retiré des favoris']);
            } else {
                $this->favorisModel->addFavorite($idEtudiant, $idAnnonce);
                echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Ajouté aux favoris']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }
}
