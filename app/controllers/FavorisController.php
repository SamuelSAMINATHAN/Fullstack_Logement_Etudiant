<?php

namespace App\Controllers;

use App\Core\Controller;

class FavorisController extends Controller
{
    private $favorisModel;
    private $annonceModel;

    public function __construct()
    {
        $this->favorisModel = $this->model('FavorisModel');
        $this->annonceModel = $this->model('AnnonceModel');
    }

    /**
     * Affiche mes favoris
     */
    public function index()
    {
        $this->requireRole('etudiant');

        $favoris = $this->favorisModel->getFavoritesWithAnnouncements($_SESSION['user_id']);

        $data = [
            'favoris' => $favoris,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('user/favorites', $data);
    }

    /**
     * Ajoute un favori
     */
    public function add($idAnnonce = null)
    {
        $this->requireRole('etudiant');

        if ($idAnnonce === null || !$this->isPost()) {
            $this->redirect('/annonce');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        $annonce = $this->annonceModel->getAnnouncementById($idAnnonce);

        if (!$annonce) {
            $this->setFlash('error', 'Annonce introuvable.');
            $this->redirect('/annonce');
        }

        try {
            $this->favorisModel->addFavorite($_SESSION['user_id'], $idAnnonce);
            $this->setFlash('success', 'Annonce ajoutée aux favoris !');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de l\'ajout aux favoris : ' . $e->getMessage());
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }
    }

    /**
     * Supprime un favori
     */
    public function remove($idAnnonce = null)
    {
        $this->requireRole('etudiant');

        if ($idAnnonce === null) {
            $this->redirect('/favoris');
        }

        try {
            $this->favorisModel->removeFavorite($_SESSION['user_id'], $idAnnonce);
            $this->setFlash('success', 'Annonce supprimée des favoris !');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/favoris');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression des favoris.');
            $this->redirect('/favoris');
        }
    }
}
