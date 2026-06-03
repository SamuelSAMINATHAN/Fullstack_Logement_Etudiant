<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <!-- Menu latéral -->
    <div class="col-md-3 mb-4">
        <?php require APPROOT . '/views/user/sidebar.php'; ?>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-9">
        <h2 class="mb-4">Bonjour, <?php echo Security::escape($_SESSION['user_prenom']); ?> !</h2>
        
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-primary h-100 shadow-sm">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <h5 class="card-title">Mes Favoris</h5>
                        <h2 class="display-4 mb-0"><?php echo isset($stats['favoris_count']) ? $stats['favoris_count'] : 0; ?></h2>
                        <a href="<?= URLROOT ?>/favoris" class="text-white mt-2 small text-decoration-none">Voir mes favoris <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-success h-100 shadow-sm">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <h5 class="card-title">Messages non lus</h5>
                        <h2 class="display-4 mb-0"><?php echo isset($stats['messages_count']) ? $stats['messages_count'] : 0; ?></h2>
                        <a href="<?= URLROOT ?>/message/inbox" class="text-white mt-2 small text-decoration-none">Voir mes messages <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
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

<script src="<?php echo URLROOT; ?>/public/js/dashboard.js"></script>
<?php require APPROOT . '/views/layout/footer.php'; ?>
