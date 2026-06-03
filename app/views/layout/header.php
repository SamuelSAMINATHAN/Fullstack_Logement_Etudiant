<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo \App\Core\Security::escape($title ?? 'Dorocho - Plateforme de logements étudiants'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Unified Stylesheet -->
    <link rel="stylesheet" href="http://localhost:8888/test/public/css/styles.css">
    <script>
        const URLROOT = '<?= URLROOT ?>';
    </script>
</head>
<body>
    <!-- Navigation -->
 <!-- Navigation Simplifiée -->
<!-- Navigation conforme au Document de Spécification v1.2 (Structure HTML uniquement) -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <!-- Logo / Nom du site -->
        <a class="navbar-brand" href="<?php echo URLROOT; ?>/">
            <i class="fas fa-home"></i> Dorocho
        </a>

        <!-- Bouton Responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Liens publics principaux (À gauche) -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/annonce">
                        <i class="fas fa-search"></i> Rechercher un logement
                    </a>
                </li>
            </ul>

            <!-- Liens dynamiques (À droite) -->
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- CAS : UTILISATEUR CONNECTÉ -->

                    <!-- Menu Étudiant -->
                    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/favoris">
                                <i class="fas fa-heart"></i> <span class="d-lg-none">Favoris</span>
                            </a>
                        </li>
                    
                    <!-- Menu Bailleur -->
                    <?php elseif ($_SESSION['user_role'] === 'bailleur'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/annonce/mesAnnonces">
                                <i class="fas fa-building"></i> <span class="d-lg-none">Mes Annonces</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-auth btn-sm" href="<?php echo URLROOT; ?>/annonce/create">
                                <i class="fas fa-plus"></i> Déposer une annonce
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Messagerie -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/message/inbox">
                            <i class="fas fa-comments"></i> Messages
                            <?php if (isset($unread_messages_count) && $unread_messages_count > 0): ?>
                                <span class="badge bg-danger ms-1"><?php echo $unread_messages_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <!-- Dropdown Utilisateur -->
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <?php echo Security::escape($_SESSION['user_prenom'] ?? 'Utilisateur'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/profil/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/profil/profile">
                                <i class="fas fa-user"></i> Mon Profil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/auth/logout">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a></li>
                        </ul>
                    </li>

                <?php else: ?>
                    <!-- CAS : UTILISATEUR NON CONNECTÉ -->
                    <li class="nav-item">
                        <a class="btn btn-auth btn-sm" href="<?php echo URLROOT; ?>/auth/login">
                            <i class="fas fa-sign-in-alt"></i> Connexion
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-auth btn-sm" href="<?php echo URLROOT; ?>/auth/register">
                            <i class="fas fa-user-plus"></i> Inscription
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Lien Admin -->
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="<?php echo URLROOT; ?>/admin/dashboard">
                            <i class="fas fa-lock"></i> Admin
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <!-- Messages Flash -->
    <div class="container mt-3">
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i>
                <strong>Succès !</strong> <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Erreur !</strong> <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php 
            $info_message = Session::getFlash('info');
            if ($info_message): 
        ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Info :</strong> <?php echo $info_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <main class="container my-4">
