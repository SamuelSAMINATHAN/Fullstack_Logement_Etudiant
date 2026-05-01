<?php

class CandidatureController extends Controller
{
    private $candidatureModel;
    private $annonceModel;
    private $etudiantModel;

    public function __construct()
    {
        $this->candidatureModel = $this->model('CandidatureModel');
        $this->annonceModel = $this->model('AnnonceModel');
        $this->etudiantModel = $this->model('EtudiantModel');
    }

    /**
     * Crée une nouvelle candidature
     */
    public function create($idAnnonce = null)
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

        // Vérifier si l'étudiant a déjà postulé
        $existingApp = $this->candidatureModel->getApplicationByStudentAndAnnouncement(
            $_SESSION['user_id'],
            $idAnnonce
        );

        if ($existingApp) {
            $this->setFlash('error', 'Vous avez déjà postulé à cette annonce.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        $message = $post['message'] ?? '';

        try {
            $data = [
                'idEtudiant' => $_SESSION['user_id'],
                'idAnnonce' => $idAnnonce,
                'message' => $message,
                'statut' => 'Envoyée'
            ];

            $idCandidature = $this->candidatureModel->createApplication($data);

            if (!$idCandidature) {
                $this->setFlash('error', 'Erreur lors de la création de la candidature.');
                $this->redirect('/annonce/detail/' . $idAnnonce);
            }

            $this->setFlash('success', 'Candidature envoyée avec succès !');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la candidature : ' . $e->getMessage());
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }
    }

    /**
     * Affiche mes candidatures (pour étudiant)
     */
    public function myApplications()
    {
        $this->requireRole('etudiant');

        $candidatures = $this->candidatureModel->getApplicationsByStudent($_SESSION['user_id']);

        // Récupérer les détails des annonces
        foreach ($candidatures as &$cand) {
            $cand['annonce'] = $this->annonceModel->getAnnouncementById($cand['idAnnonce']);
        }

        $data = [
            'candidatures' => $candidatures
        ];

        $this->view('user/candidatures', $data);
    }

    /**
     * Affiche les candidatures reçues (pour bailleur)
     */
    public function received()
    {
        $this->requireRole('bailleur');

        // Récupérer toutes les annonces du bailleur
        $annonces = $this->annonceModel->getAnnouncementsByLandlord($_SESSION['user_id']);

        $allCandidatures = [];

        foreach ($annonces as $annonce) {
            $cands = $this->candidatureModel->getApplicationsWithStudents($annonce['idAnnonce']);
            $allCandidatures = array_merge($allCandidatures, $cands);
        }

        $data = [
            'candidatures' => $allCandidatures
        ];

        $this->view('user/candidatures_recues', $data);
    }

    /**
     * Change le statut d'une candidature
     */
    public function updateStatus($idCandidature = null)
    {
        $this->requireRole('bailleur');

        if ($idCandidature === null || !$this->isPost()) {
            $this->redirect('/');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/');
        }

        $candidature = $this->candidatureModel->getApplicationById($idCandidature);

        if (!$candidature) {
            $this->setFlash('error', 'Candidature introuvable.');
            $this->redirect('/');
        }

        // Vérifier que le bailleur est le propriétaire de l'annonce
        $annonce = $this->annonceModel->getAnnouncementById($candidature['idAnnonce']);

        if (!$annonce || $annonce['idBailleur'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Accès non autorisé.');
            $this->redirect('/');
        }

        $statut = $post['statut'] ?? '';

        if (!in_array($statut, ['Envoyée', 'Vue', 'Acceptée', 'Refusée'])) {
            $this->setFlash('error', 'Statut invalide.');
            $this->redirect('/');
        }

        try {
            $this->candidatureModel->updateApplicationStatus($idCandidature, $statut);
            $this->setFlash('success', 'Statut de la candidature mis à jour !');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la mise à jour.');
            $this->redirect('/');
        }
    }

    /**
     * Supprime une candidature
     */
    public function delete($idCandidature = null)
    {
        $this->requireAuth();

        if ($idCandidature === null) {
            $this->redirect('/');
        }

        $candidature = $this->candidatureModel->getApplicationById($idCandidature);

        if (!$candidature) {
            $this->setFlash('error', 'Candidature introuvable.');
            $this->redirect('/');
        }

        // Vérifier que c'est l'étudiant qui a postulé
        if ($_SESSION['user_role'] === 'etudiant' && $candidature['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Accès non autorisé.');
            $this->redirect('/');
        }

        // Ou que c'est le bailleur propriétaire de l'annonce
        if ($_SESSION['user_role'] === 'bailleur') {
            $annonce = $this->annonceModel->getAnnouncementById($candidature['idAnnonce']);
            if (!$annonce || $annonce['idBailleur'] != $_SESSION['user_id']) {
                $this->setFlash('error', 'Accès non autorisé.');
                $this->redirect('/');
            }
        }

        try {
            $this->candidatureModel->deleteApplication($idCandidature);
            $this->setFlash('success', 'Candidature supprimée.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        } catch (Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/');
        }
    }
}
