<?php

require_once __DIR__ . '/../models/Annonce.php';

class AnnonceController extends Controller
{
    public function index(): void
    {
        $model = new Annonce();
        $annonces = $model->getDernieresAnnonces();

        $this->render('annonce/liste', [
            'titrePage' => 'Liste des annonces',
            'annonces'  => $annonces,
        ]);
    }

    public function detail(): void
    {
        if (empty($_GET['id'])) {
            http_response_code(400);
            echo 'ID d\'annonce manquant.';
            return;
        }

        $id = (int) $_GET['id'];
        $model = new Annonce();
        $annonce = $model->getById($id);

        if (!$annonce) {
            http_response_code(404);
            echo 'Annonce introuvable.';
            return;
        }

        $this->render('annonce/detail', [
            'titrePage' => $annonce['titre'] ?? 'Détail annonce',
            'annonce'   => $annonce,
        ]);
    }
}

