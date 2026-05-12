<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <!-- Menu latéral -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <a href="<?php echo URLROOT; ?>/profil/dashboard" class="list-group-item list-group-item-action">
                <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
            </a>
            <a href="<?php echo URLROOT; ?>/profil/profile" class="list-group-item list-group-item-action">
                <i class="fas fa-user me-2"></i> Mon Profil
            </a>
            <a href="<?php echo URLROOT; ?>/favoris" class="list-group-item list-group-item-action active">
                <i class="fas fa-heart me-2"></i> Mes Favoris
            </a>
            <a href="<?php echo URLROOT; ?>/alerte" class="list-group-item list-group-item-action">
                <i class="fas fa-bell me-2"></i> Mes Alertes
            </a>
            <a href="<?php echo URLROOT; ?>/profil/changePassword" class="list-group-item list-group-item-action">
                <i class="fas fa-lock me-2"></i> Sécurité
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-9">
        <h4 class="mb-4"><i class="fas fa-heart text-danger"></i> Mes Logements Favoris</h4>

        <div class="row g-4">
            <?php if (!empty($favoris)): ?>
                <?php foreach ($favoris as $fav): ?>
                    <div class="col-md-6 col-lg-6">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo Security::escape($fav['image'] ?? 'default.jpg'); ?>" class="card-img-top" alt="Image" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title text-truncate"><?php echo Security::escape($fav['titre']); ?></h5>
                                <p class="card-text text-muted mb-2">
                                    <i class="fas fa-map-marker-alt text-primary"></i> <?php echo Security::escape($fav['ville']); ?>
                                </p>
                                <h4 class="text-primary mb-3"><?php echo Security::escape($fav['loyer']); ?> €</h4>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="<?php echo URLROOT; ?>/annonce/detail/<?php echo Security::escape($fav['annonce_id']); ?>" class="btn btn-outline-primary btn-sm">Voir détails</a>
                                    
                                    <form action="<?php echo URLROOT; ?>/favoris/remove" method="POST" class="m-0">
                                        <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                                        <input type="hidden" name="annonce_id" value="<?php echo Security::escape($fav['annonce_id']); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Retirer des favoris">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="far fa-heart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Vous n'avez aucun logement en favoris pour le moment.</p>
                    <a href="<?php echo URLROOT; ?>/annonce/liste" class="btn btn-primary">Parcourir les annonces</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
