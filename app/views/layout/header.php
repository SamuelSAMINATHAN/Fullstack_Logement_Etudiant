<?php
if (!isset($titrePage)) {
    $titrePage = 'Logements étudiants';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titrePage, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>
<header>
    <div class="container">
        <h1><a href="<?= BASE_URL ?>index.php">Plateforme logements étudiants</a></h1>
        <nav>
            <a href="<?= BASE_URL ?>index.php">Accueil</a>
            <a href="<?= BASE_URL ?>index.php?controller=annonce&action=index">Annonces</a>
        </nav>
    </div>
</header>
<main class="container">

