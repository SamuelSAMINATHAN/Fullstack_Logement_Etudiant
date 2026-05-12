<?php

namespace App\Controllers;

use App\Core\Controller;

class AvisController extends Controller
{
    private $avisModel;
    private $annonceModel;
    private $candidatureModel;

    public function __construct()
    {
        $this->avisModel = $this->model('AvisModel');
        $this->annonceModel = $this->model('AnnonceModel');
        $this->candidatureModel = $this->model('CandidatureModel');
    }

    /**
     * Crée un nouvel avis
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

        // Vérifier que l'étudiant a une candidature acceptée pour cette annonce
        $candidature = $this->candidatureModel->getApplicationByStudentAndAnnouncement(
            $_SESSION['user_id'],
            $idAnnonce
        );

        if (!$candidature || $candidature['statut'] !== 'Acceptée') {
            $this->setFlash('error', 'Vous ne pouvez laisser un avis que si votre candidature a été acceptée.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        // Vérifier si l'étudiant a déjà laissé un avis
        if ($this->avisModel->hasReviewed($_SESSION['user_id'], $idAnnonce)) {
            $this->setFlash('error', 'Vous avez déjà laissé un avis pour cette annonce.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        $note = (int)($post['note'] ?? 0);
        $commentaire = $post['commentaire'] ?? '';

        if ($note < 1 || $note > 5) {
            $this->setFlash('error', 'La note doit être entre 1 et 5.');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }

        try {
            $data = [
                'idEtudiant' => $_SESSION['user_id'],
                'idAnnonce' => $idAnnonce,
                'note' => $note,
                'commentaire' => $commentaire ?: null
            ];

            $idAvis = $this->avisModel->createReview($data);

            if (!$idAvis) {
                $this->setFlash('error', 'Erreur lors de la création de l\'avis.');
                $this->redirect('/annonce/detail/' . $idAnnonce);
            }

            $this->setFlash('success', 'Avis posté avec succès !');
            $this->redirect('/annonce/detail/' . $idAnnonce);
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la création de l\'avis : ' . $e->getMessage());
            $this->redirect('/annonce/detail/' . $idAnnonce);
        }
    }

    /**
     * Affiche mes avis (pour étudiant)
     */
    public function myReviews()
    {
        $this->requireRole('etudiant');

        $avis = $this->avisModel->getReviewsByStudent($_SESSION['user_id']);

        // Récupérer les détails des annonces
        foreach ($avis as &$av) {
            $av['annonce'] = $this->annonceModel->getAnnouncementById($av['idAnnonce']);
        }

        $data = [
            'avis' => $avis
        ];

        $this->view('user/avis', $data);
    }

    /**
     * Édite un avis
     */
    public function edit($idAvis = null)
    {
        $this->requireRole('etudiant');

        if ($idAvis === null) {
            $this->redirect('/');
        }

        $avis = $this->avisModel->getReviewById($idAvis);

        if (!$avis || $avis['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Avis introuvable ou accès non autorisé.');
            $this->redirect('/');
        }

        $data = [
            'avis' => $avis,
            'csrf_token' => Security::csrfToken()
        ];

        $this->view('user/avis_edit', $data);
    }

    /**
     * Traite la modification d'un avis
     */
    public function editHandler($idAvis = null)
    {
        $this->requireRole('etudiant');

        if ($idAvis === null || !$this->isPost()) {
            $this->redirect('/');
        }

        $post = $this->sanitizePost();

        if (!isset($post['csrf_token']) || !Security::verifyCsrf($post['csrf_token'])) {
            $this->setFlash('error', 'Token CSRF invalide.');
            $this->redirect('/');
        }

        $avis = $this->avisModel->getReviewById($idAvis);

        if (!$avis || $avis['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Avis introuvable ou accès non autorisé.');
            $this->redirect('/');
        }

        $note = (int)($post['note'] ?? 0);
        $commentaire = $post['commentaire'] ?? '';

        if ($note < 1 || $note > 5) {
            $this->setFlash('error', 'La note doit être entre 1 et 5.');
            $this->redirect('/avis/edit/' . $idAvis);
        }

        try {
            $data = [
                'note' => $note,
                'commentaire' => $commentaire ?: null
            ];

            $this->avisModel->updateReview($idAvis, $data);

            $this->setFlash('success', 'Avis mis à jour avec succès !');
            $this->redirect('/avis/myReviews');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
            $this->redirect('/avis/edit/' . $idAvis);
        }
    }

    /**
     * Supprime un avis
     */
    public function delete($idAvis = null)
    {
        $this->requireRole('etudiant');

        if ($idAvis === null) {
            $this->redirect('/');
        }

        $avis = $this->avisModel->getReviewById($idAvis);

        if (!$avis || $avis['idEtudiant'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Avis introuvable ou accès non autorisé.');
            $this->redirect('/');
        }

        try {
            $this->avisModel->deleteReview($idAvis);
            $this->setFlash('success', 'Avis supprimé avec succès !');
            $this->redirect('/avis/myReviews');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression.');
            $this->redirect('/');
        }
    }
}
