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
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 70px;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: white !important;
            font-weight: 600;
            border-bottom: 2px solid white;
        }

        .btn-auth {
            background-color: white;
            color: var(--primary-color);
            border: none;
            margin: 0 0.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-error, .alert-danger {
            background-color: #fee2e2;
            color: #7f1d1d;
        }

        .alert-close {
            cursor: pointer;
            float: right;
            font-size: 20px;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        .user-dropdown {
            position: relative;
        }

        .dropdown-menu-custom {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white;
        }

        main {
            min-height: calc(100vh - 70px - 300px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo URLROOT; ?>/">
                <i class="fas fa-home"></i> Dorocho
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/">
                            <i class="fas fa-house"></i> Accueil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/annonce">
                            <i class="fas fa-building"></i> Annonces
                        </a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Menu pour utilisateurs connectés -->
                        <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/favoris">
                                    <i class="fas fa-heart"></i> Favoris
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/alerte">
                                    <i class="fas fa-bell"></i> Alertes
                                </a>
                            </li>
                        <?php elseif ($_SESSION['user_role'] === 'bailleur'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/annonce/create">
                                    <i class="fas fa-plus"></i> Nouvelle Annonce
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLROOT; ?>/candidature/received">
                                    <i class="fas fa-inbox"></i> Candidatures
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/message/inbox">
                                <i class="fas fa-envelope"></i> Messages
                                <?php if (isset($unread_messages_count) && $unread_messages_count > 0): ?>
                                    <span class="badge bg-danger ms-1"><?php echo $unread_messages_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <!-- Dropdown utilisateur -->
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
                                <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/profil/changePassword">
                                    <i class="fas fa-lock"></i> Changer le mot de passe
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/auth/logout">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Menu pour utilisateurs non connectés -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLROOT; ?>/page/faq">
                                <i class="fas fa-question-circle"></i> FAQ
                            </a>
                        </li>

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

                    <!-- Lien admin -->
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
                <strong>Succès !</strong> <?php echo Security::escape($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Erreur !</strong> <?php echo Security::escape($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <main class="container my-4">

