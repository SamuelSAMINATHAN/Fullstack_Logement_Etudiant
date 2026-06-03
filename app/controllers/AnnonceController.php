<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Session;

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
     * Liste toutes les annonces (recherche)
     */
    public function home()
    {
        $filters = [];
        if (!empty($_GET['localisation'])) $filters['localisation'] = $_GET['localisation'];
        if (!empty($_GET['prix_max'])) $filters['prix_max'] = (float)$_GET['prix_max'];
        if (!empty($_GET['type_logement'])) $filters['type_logement'] = $_GET['type_logement'];

        $annonces = $this->annonceModel->searchAnnouncements($filters);

        foreach ($annonces as &$annonce) {
            $id = $annonce['idAnnonce'];
            $annonce['photos'] = $this->photoAnnonceModel->getPhotosByAnnouncement($id);
            $annonce['note_moyenne'] = $this->avisModel->getAverageRatingByAnnouncement($id);
        }

        $this->view('annonce/liste', ['annonces' => $annonces, 'filters' => $filters]);
    }

    /**
     * Détails d'une annonce
     */
    public function detail($id = null)
    {
        if (!$id) $this->redirect('/annonce');

        $annonce = $this->annonceModel->getAnnouncementWithLandlord($id);
        if (!$annonce) {
            Session::setFlash('error', 'Annonce introuvable.');
            $this->redirect('/annonce');
        }

        // Si le bailleur est shadowbanni, on ne montre l'annonce qu'à lui-même ou à un admin
        if ($annonce['estShadowban'] == 1) {
            $isOwner = $this->isLoggedIn() && $_SESSION['user_id'] == $annonce['idBailleur'];
            $isAdmin = isset($_SESSION['admin_id']);
            
            if (!$isOwner && !$isAdmin) {
                Session::setFlash('error', 'Cette annonce n\'est plus disponible.');
                $this->redirect('/annonce');
            }
        }

        $annonce['photos'] = $this->photoAnnonceModel->getPhotosByAnnouncement($id);
        $annonce['avis'] = $this->avisModel->getReviewsWithStudents($id);
        $annonce['note_moyenne'] = $this->avisModel->getAverageRatingByAnnouncement($id);
        
        $annonce['est_favori'] = false;
        if ($this->isLoggedIn() && $_SESSION['user_role'] === 'etudiant') {
            $annonce['est_favori'] = $this->favorisModel->isFavorite($_SESSION['user_id'], $id);
        }

        $this->view('annonce/detail', ['annonce' => $annonce]);
    }

    /**
     * Mes annonces (Bailleur uniquement)
     */
    public function mesAnnonces()
    {
        $this->requireRole('bailleur');
        $annonces = $this->annonceModel->getAnnouncementsByLandlord($_SESSION['user_id']);
        $this->view('user/my_announcements', ['annonces' => $annonces]);
    }

    /**
     * API pour la recherche d'annonces (utilisé par logements.js)
     */
    public function apisearch()
    {
        header('Content-Type: application/json');
        
        $filters = [];
        if (!empty($_GET['search'])) $filters['search'] = $_GET['search'];
        if (!empty($_GET['price_max'])) $filters['price_max'] = (float)$_GET['price_max'];
        if (!empty($_GET['surface_min'])) $filters['surface_min'] = (float)$_GET['surface_min'];
        
        // Types de logement
        if (!empty($_GET['types'])) {
            $filters['types'] = $_GET['types']; // On laisse en string pour le modèle
        }
        
        if (!empty($_GET['rooms']) && $_GET['rooms'] !== '0') {
            $filters['rooms'] = (int)$_GET['rooms'];
        }
        
        // Équipements (booléens)
        if (isset($_GET['meuble'])) $filters['meuble'] = true;
        if (isset($_GET['ascenseur'])) $filters['ascenseur'] = true;
        if (isset($_GET['parking'])) $filters['parking'] = true;
        if (isset($_GET['balcony'])) $filters['balcony'] = true;
        if (isset($_GET['animaux'])) $filters['animaux'] = true;
        if (isset($_GET['pmr'])) $filters['pmr'] = true;

        try {
            $annonces = $this->annonceModel->searchAnnouncements($filters);
            
            foreach ($annonces as &$annonce) {
                $id = $annonce['idAnnonce'];
                $annonce['photos'] = $this->photoAnnonceModel->getPhotosByAnnouncement($id);
                $annonce['note_moyenne'] = $this->avisModel->getAverageRatingByAnnouncement($id);
            }
            
            echo json_encode(['success' => true, 'data' => $annonces]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Créer une annonce avec gestion d'upload de photo
     */
    public function create()
    {
        $this->requireRole('bailleur');
        
        if ($this->isPost()) {
            $post = $this->sanitizePost();
            if (!Security::verifyCsrf($post['csrf_token'] ?? '')) {
                Session::setFlash('error', 'Token CSRF invalide.');
                $this->redirect('/annonce/create');
            }

            $data = [
                'titre' => $post['titre'],
                'description' => $post['description'],
                'prix' => $post['prix'],
                'localisation' => $post['localisation'],
                'type_logement' => $post['type_logement'],
                'surface' => $post['surface'],
                'nbPieces' => $post['nbPieces'],
                'meuble' => isset($post['meuble']) ? 1 : 0,
                'estColocation' => isset($post['estColocation']) ? 1 : 0,
                'dateDisponibilite' => $post['dateDisponibilite'],
                'idBailleur' => $_SESSION['user_id']
            ];

            // 1. Création de l'annonce textuelle
            $idAnnonce = $this->annonceModel->createAnnouncement($data);
            
            if ($idAnnonce) {
                // 2. Gestion de l'upload de la photo
                if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = dirname(dirname(__DIR__)) . "/public/uploads/annonces/";
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    
                    $fileName = time() . '_' . basename($_FILES['photo']['name']);
                    $targetFilePath = $targetDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                        $urlPhoto = "/uploads/annonces/" . $fileName;
                        
                        $this->photoAnnonceModel->createPhoto([
                            'urlPhoto' => $urlPhoto,
                            'idAnnonce' => $idAnnonce
                        ]);
                    }
                }

                Session::setFlash('success', 'Annonce créée avec succès.');
                $this->redirect('/annonce/detail/' . $idAnnonce);
            }
        }

        $this->view('annonce/edit', ['annonce' => []]); 
    }

    /**
     * Modifier une annonce
     */
    public function edit($id = null)
    {
        $this->requireRole('bailleur');
        if (!$id) $this->redirect('/annonce/mesAnnonces');

        $annonce = $this->annonceModel->getAnnouncementById($id);
        if (!$annonce || $annonce['idBailleur'] != $_SESSION['user_id']) {
            $this->redirect('/annonce/mesAnnonces');
        }

        if ($this->isPost()) {
            $post = $this->sanitizePost();
            if (!Security::verifyCsrf($post['csrf_token'] ?? '')) {
                Session::setFlash('error', 'Token CSRF invalide.');
            } else {
                $data = [
                    'titre' => $post['titre'],
                    'description' => $post['description'],
                    'prix' => $post['prix'],
                    'localisation' => $post['localisation'],
                    'type_logement' => $post['type_logement'],
                    'surface' => $post['surface'],
                    'nbPieces' => $post['nbPieces'],
                    'meuble' => isset($post['meuble']) ? 1 : 0,
                    'estColocation' => isset($post['estColocation']) ? 1 : 0,
                    'dateDisponibilite' => $post['dateDisponibilite']
                ];

                if ($this->annonceModel->updateAnnouncement($id, $data)) {
                    // Gestion de l'upload de la photo lors de la modification
                    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                        $targetDir = dirname(dirname(__DIR__)) . "/public/uploads/annonces/";
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }
                        
                        $fileName = time() . '_' . basename($_FILES['photo']['name']);
                        $targetFilePath = $targetDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                            $urlPhoto = "/uploads/annonces/" . $fileName;
                            
                            // On ajoute la nouvelle photo
                            $this->photoAnnonceModel->createPhoto([
                                'urlPhoto' => $urlPhoto,
                                'idAnnonce' => $id
                            ]);
                        }
                    }

                    Session::setFlash('success', 'Annonce mise à jour.');
                    $this->redirect('/annonce/detail/' . $id);
                }
            }
        }

        $this->view('annonce/edit', ['annonce' => $annonce]);
    }

    /**
     * Supprimer une annonce
     */
    public function delete($id = null)
    {
        $this->requireRole('bailleur');
        if (!$id) $this->redirect('/annonce/mesAnnonces');

        $annonce = $this->annonceModel->getAnnouncementById($id);
        if ($annonce && $annonce['idBailleur'] == $_SESSION['user_id']) {
            $this->annonceModel->deleteAnnouncement($id);
            Session::setFlash('success', 'Annonce supprimée.');
        }

        $this->redirect('/annonce/mesAnnonces');
    }
}