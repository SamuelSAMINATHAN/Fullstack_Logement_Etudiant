<?php

class HomeController extends Controller
{
    public function index(): void
    {
        $titrePage = 'Accueil - Logements étudiants';
        $this->render('home/index', [
            'titrePage' => $titrePage,
        ]);
    }
}

