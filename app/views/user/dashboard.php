<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <!-- Menu latéral -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <a href="<?php echo URLROOT; ?>/profil/dashboard" class="list-group-item list-group-item-action active">
                <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
            </a>
            <a href="<?php echo URLROOT; ?>/profil/profile" class="list-group-item list-group-item-action">
                <i class="fas fa-user me-2"></i> Mon Profil
            </a>
            <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                <a href="<?php echo URLROOT; ?>/favoris" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-2"></i> Mes Favoris
                </a>
                <a href="<?php echo URLROOT; ?>/alerte" class="list-group-item list-group-item-action">
                    <i class="fas fa-bell me-2"></i> Mes Alertes
                </a>
            <?php elseif ($_SESSION['user_role'] === 'bailleur'): ?>
                <a href="<?php echo URLROOT; ?>/annonce/mes-annonces" class="list-group-item list-group-item-action">
                    <i class="fas fa-building me-2"></i> Mes Annonces
                </a>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>/profil/changePassword" class="list-group-item list-group-item-action">
                <i class="fas fa-lock me-2"></i> Sécurité
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-9">
        <h2 class="mb-4">Bonjour, <?php echo Security::escape($_SESSION['user_prenom']); ?> !</h2>
        
        <div class="row mb-4">
            <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary h-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h5 class="card-title">Favoris</h5>
                            <h2 class="display-4 mb-0"><?php echo isset($stats['favoris_count']) ? $stats['favoris_count'] : 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success h-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h5 class="card-title">Candidatures</h5>
                            <h2 class="display-4 mb-0"><?php echo isset($stats['candidatures_count']) ? $stats['candidatures_count'] : 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-info h-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h5 class="card-title">Alertes actives</h5>
                            <h2 class="display-4 mb-0"><?php echo isset($stats['alertes_count']) ? $stats['alertes_count'] : 0; ?></h2>
                        </div>
                    </div>
                </div>
            <?php elseif ($_SESSION['user_role'] === 'bailleur'): ?>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary h-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h5 class="card-title">Mes annonces</h5>
                            <h2 class="display-4 mb-0"><?php echo isset($stats['annonces_count']) ? $stats['annonces_count'] : 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success h-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h5 class="card-title">Candidatures reçues</h5>
                            <h2 class="display-4 mb-0"><?php echo isset($stats['candidatures_reçues']) ? $stats['candidatures_reçues'] : 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-warning h-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h5 class="card-title">Vues sur mes annonces</h5>
                            <h2 class="display-4 mb-0"><?php echo isset($stats['vues_totales']) ? $stats['vues_totales'] : 0; ?></h2>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Activité récente</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($activites)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($activites as $activite): ?>
                            <li class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo Security::escape($activite['titre']); ?></h6>
                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($activite['date'])); ?></small>
                                </div>
                                <p class="mb-1 small"><?php echo Security::escape($activite['description']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted text-center my-4">Aucune activité récente à afficher.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
