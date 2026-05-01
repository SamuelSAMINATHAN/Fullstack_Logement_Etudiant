<?php

class AnnonceController extends Controller
{
    private $annonceModel;
    private $photoAnnonceModel;
    private $bailleurModel;
    private $avisModel;
    private $favorisModel;
    private $candidatureModel;

    public function __construct()
    {
        $this->annonceModel = $this->model('AnnonceModel');
        $this->photoAnnonceModel = $this->model('PhotoAnnonceModel');
        $this->bailleurModel = $this->model('BailleurModel');
        $this->avisModel = $this->model('AvisModel');
        $this->favorisModel = $this->model('FavorisModel');
        $this->candidatureModel = $this->model('CandidatureModel');
    }

    /**
     * Liste toutes les annonces
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filters = [];

        // Récupérer les filtres GET
        if (!empty($_GET['localisation'])) {
            $filters['localisation'] = $_GET['localisation'];
        }
        if (!empty($_GET['prix_max'])) {
            $filters['prix_max'] = (float)$_GET['prix_max'];
        }
        if (!empty($_GET['type_logement'])) {
            $filters['type_logement'] = $_GET['type_logement'];
        }

        $annonces = $this->annonceModel->searchAnnouncements($filters);

        // Ajouter les photos et les avis
        foreach ($annonces as &$annonce) {
            $annonce['photos'] = $this->photoAnnonceModel->getPhotosByAnnouncement($annonce['idAnnonce']);
            $annonce['note_moyenne'] = $this->avisModel->getAverageRatingByAnnouncement($annonce['idAnnonce']);
            $annonce['nb_avis'] = $this->avisModel->countReviewsByAnnouncement($annonce['idAnnonce']);
        }

        $data = [
            'annonces' => $annonces,
            'filters' => $filters,
            'page' => $page
        ];

        $this->view('annonce/index', $data);
    }

    /**
     * Affiche une annonce en détail
     */
    public function detail($idAnnonce = null)
    {
        if ($idAnnonce === null) {
            $this->redirect('/annonce');
        }

        $annonce = $this->annonceModel->getAnnouncementWithLandlord($idAnnonce);

        if (!$annonce) {
            $this->setFlash('error', 'Annonce introuvable.');
            $this->redirect('/annonce');
        }

        // Récupérer les photos
        $annonce['photos'] = $this->photoAnnonceModel->getPhotosByAnnouncement($idAnnonce);

        // Récupérer les avis
        $avis = $this->avisModel->getReviewsWithStudents($idAnnonce);
        $annonce['avis'] = $avis;
        $annonce['note_moyenne'] = $this->avisModel->getAverageRatingByAnnouncement($idAnnonce);

        // Vérifier si l'utilisateur a mis en favori
        $annonce['est_favori'] = false;
        if ($this->isLoggedIn() && isset($_SESSION['user_id'])) {
            $annonce['est_favori'] = $this->favorisModel->isFavorite($_SESSION['user_id'], $idAnnonce);
        }

        // Vérifier si l'utilisateur a postulé
        $annonce['a_postule'] = false;
        $candidature = null;
        if ($this->isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant') {
            $candidature = $this->candidatureModel->getApplicationByStudentAndAnnouncement($_SESSION['user_id'], $idAnnonce);
            $annonce['a_postule'] = $candidature !== null;
            $annonce['candidature'] = $candidature;
        }

        $data = [
            'annonce' => $annonce,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('annonce/detail', $data);
    }

    /**
     * Affiche le formulaire de création d'annonce
     */
    public function create()
    {
        $this->requireRole('bailleur');

        $data = [
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('annonce/edit', $data);
    }

    /**
     * Traite la création d'une nouvelle annonce
     */
    public function createHandler()
    {
        $this->requireRole('bailleur');

        if (!$this->isPost()) {
            $this->redirect('/annonce/create');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/annonce/create');
        }

        // Récupérer les données
        $titre = $post['titre'] ?? '';
        $description = $post['description'] ?? '';
        $prix = $post['prix'] ?? '';
        $localisation = $post['localisation'] ?? '';
        $type_logement = $post['type_logement'] ?? '';
        $surface = $post['surface'] ?? '';
        $nbPieces = $post['nbPieces'] ?? 1;
        $meuble = isset($post['meuble']) ? 1 : 0;
        $estColocation = isset($post['estColocation']) ? 1 : 0;
        $dateDisponibilite = $post['dateDisponibilite'] ?? null;

        // Valider les données
        if (empty($titre) || empty($description) || empty($prix) || empty($localisation)) {
            $this->setFlash('error', 'Tous les champs obligatoires doivent être remplis.');
            $this->redirect('/annonce/create');
        }

        try {
            $data = [
                'titre' => $titre,
                'description' => $description,
                'prix' => (float)$prix,
                'localisation' => $localisation,
                'type_logement' => $type_logement,
                'surface' => (int)$surface ?: null,
                'nbPieces' => (int)$nbPieces,
                'meuble' => $meuble,
                'estColocation' => $estColocation,
                'dateDisponibilite' => $dateDisponibilite ?: null,
                'idBailleur' => $_SESSION['user_id']
            ];

            $idAnnonce = $this->annonceModel->createAnnouncement($data);

            if (!$idAnnonce) {
                $this->setFlash('error', 'Erreur lors de la création de l\'annonce.');
                $this->redirect('/annonce/create');
            }

            $this->setFlash('success', 'Annonce créée avec succès !');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la création : ' . $e->getMessage());
            $this->redirect('/annonce/create');
        }
    }

    /**
     * Affiche le formulaire de modification d'annonce
     */
    public function edit($idAnnonce = null)
    {
        $this->requireRole('bailleur');

        if ($idAnnonce === null) {
            $this->redirect('/annonce');
        }

        $annonce = $this->annonceModel->getAnnouncementById($idAnnonce);

        if (!$annonce || $annonce['idBailleur'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Annonce introuvable ou accès non autorisé.');
            $this->redirect('/annonce');
        }

        $data = [
            'annonce' => $annonce,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('annonce/edit', $data);
    }

    /**
     * Traite la modification d'une annonce
     */
    public function editHandler($idAnnonce = null)
    {
        $this->requireRole('bailleur');

        if (!$this->isPost()) {
            $this->redirect('/annonce');
        }

        if ($idAnnonce === null) {
            $this->redirect('/annonce');
        }

        $annonce = $this->annonceModel->getAnnouncementById($idAnnonce);

        if (!$annonce || $annonce['idBailleur'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Annonce introuvable ou accès non autorisé.');
            $this->redirect('/annonce');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/annonce/edit/' . $idAnnonce);
        }

        $titre = $post['titre'] ?? '';
        $description = $post['description'] ?? '';
        $prix = $post['prix'] ?? '';
        $localisation = $post['localisation'] ?? '';
        $meuble = isset($post['meuble']) ? 1 : 0;
        $estColocation = isset($post['estColocation']) ? 1 : 0;

        if (empty($titre) || empty($description) || empty($prix)) {
            $this->setFlash('error', 'Tous les champs obligatoires doivent être remplis.');
            $this->redirect('/annonce/edit/' . $idAnnonce);
        }

        try {
            $data = [
                'titre' => $titre,
                'description' => $description,
                'prix' => (float)$prix,
                'localisation' => $localisation,
                'meuble' => $meuble,
                'estColocation' => $estColocation
            ];

            $this->annonceModel->updateAnnouncement($idAnnonce, $data);

            $this->setFlash('success', 'Annonce mise à jour avec succès !');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
            $this->redirect('/annonce/edit/' . $idAnnonce);
        }
    }

    /**
     * Supprime une annonce
     */
    public function delete($idAnnonce = null)
    {
        $this->requireRole('bailleur');

        if ($idAnnonce === null) {
            $this->redirect('/annonce');
        }

        $annonce = $this->annonceModel->getAnnouncementById($idAnnonce);

        if (!$annonce || $annonce['idBailleur'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Annonce introuvable ou accès non autorisé.');
            $this->redirect('/annonce');
        }

        try {
            $this->annonceModel->deleteAnnouncement($idAnnonce);
            $this->setFlash('success', 'Annonce supprimée avec succès !');
            $this->redirect('/annonce');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }
    }
}
