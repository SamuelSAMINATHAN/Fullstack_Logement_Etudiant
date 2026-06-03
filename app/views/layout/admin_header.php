<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Dorocho Logements</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --admin-primary: #2c3e50;
            --admin-secondary: #34495e;
            --admin-accent: #3498db;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-admin {
            background-color: var(--admin-primary);
        }
        .sidebar {
            background-color: var(--admin-secondary);
            min-height: calc(100vh - 56px);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .main-content {
            padding: 2rem;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .badge-verified {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= URLROOT ?>/admin/dashboard">
                <i class="fas fa-shield-alt"></i> Dorocho Admin
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="text-light me-3">Bonjour, <?= \App\Core\Security::escape($_SESSION['admin_name'] ?? 'Admin') ?></span>
                <a href="<?= URLROOT ?>/adminauth/logout" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/admin/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/admin/users">
                                <i class="fas fa-users"></i> Utilisateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/admin/verifyBailleurs">
                                <i class="fas fa-user-check"></i> Vérif. Bailleurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/admin/admins">
                                <i class="fas fa-user-shield"></i> Administrateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/admin/logements">
                                <i class="fas fa-home"></i> Gestion Logements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/admin/messages">
                                <i class="fas fa-envelope"></i> Messages reçus
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/adminsignalement/index">
                                <i class="fas fa-exclamation-triangle"></i> Signalements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/adminfaq/index">
                                <i class="fas fa-question-circle"></i> Gestion FAQ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URLROOT ?>/admin/legal">
                                <i class="fas fa-file-contract"></i> CGU / Mentions
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link text-warning" href="<?= URLROOT ?>/" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Voir le site
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 p-0">
                <div class="main-content">
                    <?php if (isset($success_message) && $success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error_message) && $error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>