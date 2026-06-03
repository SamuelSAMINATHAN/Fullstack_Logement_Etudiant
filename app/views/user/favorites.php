<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <?php require APPROOT . '/views/user/sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4">Mes Favoris</h2>

        <?php if (empty($favoris)): ?>
            <div class="card shadow-sm text-center p-5">
                <i class="far fa-heart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Vous n'avez pas encore d'annonces en favoris.</p>
                <a href="<?= URLROOT ?>/annonce" class="btn btn-primary mx-auto">Parcourir les offres</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($favoris as $fav): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-truncate"><?= Security::escape($fav['titre'] ?? 'Annonce sans titre') ?></h5>
                                <p class="text-muted small mb-2"><?= Security::escape($fav['localisation'] ?? 'Non spécifiée') ?></p>
                                <p class="fw-bold text-primary mb-3"><?= number_format(($fav['prix'] ?? 0), 2) ?> € / mois</p>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="<?= URLROOT ?>/annonce/detail/<?= $fav['idAnnonce'] ?? $fav['id'] ?? '#' ?>" class="btn btn-sm btn-outline-info">Voir l'offre</a>
                                    
                                    <button class="btn btn-sm btn-outline-danger btn-toggle-favoris active" data-id="<?= $fav['idAnnonce'] ?? $fav['id'] ?? '' ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?= URLROOT ?>/js/favoris.js"></script>

<?php require APPROOT . '/views/layout/footer.php'; ?>