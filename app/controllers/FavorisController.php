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

    public function toggle()
    {
        // Nettoie les affichages parasites pour éviter de casser le JSON
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json');
        
        try {
            // On récupère l'ID envoyé par le JavaScript
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $idAnnonce = $data['idAnnonce'] ?? $_POST['idAnnonce'] ?? null;
            
            if (!$idAnnonce) {
                echo json_encode(['success' => false, 'message' => 'ID annonce manquant ou invalide.']);
                exit;
            }

            $idEtudiant = $_SESSION['user_id'] ?? null;
            if (!$idEtudiant || ($_SESSION['user_role'] ?? '') !== 'etudiant') {
                echo json_encode(['success' => false, 'message' => 'Action réservée aux étudiants connectés.']);
                exit;
            }
            
            if ($this->favorisModel->isFavorite($idEtudiant, $idAnnonce)) {
                if ($this->favorisModel->removeFavorite($idEtudiant, $idAnnonce)) {
                    echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Retiré des favoris']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors du retrait du favori.']);
                }
            } else {
                if ($this->favorisModel->addFavorite($idEtudiant, $idAnnonce)) {
                    echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Ajouté aux favoris']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du favori.']);
                }
            }
        } catch (\Exception $e) {
            error_log("[Favoris] Erreur : " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur technique : ' . $e->getMessage()]);
        }
        exit;
    }
}
