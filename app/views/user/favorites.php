<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="fw-bold text-primary mb-1">
            <i class="fas fa-heart"></i> Mes Favoris
        </h1>
        <p class="text-muted">Retrouvez tous les logements que vous avez mis en favoris</p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary">
                <i class="fas fa-sort"></i> Trier
            </button>
            <button type="button" class="btn btn-outline-primary">
                <i class="fas fa-filter"></i> Filtrer
            </button>
        </div>
    </div>
</div>

<!-- Favoris -->
<div class="row g-4">
    <?php if (!empty($favoris)): ?>
        <?php foreach ($favoris as $fav): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden hover-lift position-relative">
                    <!-- Image -->
                    <div style="height: 250px; overflow: hidden;">
                        <img 
                            src="<?php echo Security::escape($fav['photo_url'] ?? '/img/default-listing.jpg'); ?>"
                            alt="<?php echo Security::escape($fav['titre']); ?>"
                            class="w-100 h-100 object-fit-cover"
                        >
                    </div>

                    <!-- Bouton supprimer des favoris -->
                    <button 
                        class="btn btn-danger btn-sm rounded-circle position-absolute top-2 end-2 shadow-lg"
                        onclick="removeFavorite(<?php echo $fav['id']; ?>)"
                        title="Supprimer des favoris"
                    >
                        <i class="fas fa-heart"></i>
                    </button>

                    <!-- Contenu -->
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-truncate-2">
                            <?php echo Security::escape($fav['titre']); ?>
                        </h5>

                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <?php echo Security::escape($fav['ville']); ?>
                        </p>

                        <div class="row g-2 mb-3 text-center">
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-ruler-combined"></i>
                                </small>
                                <small class="fw-bold"><?php echo Security::escape($fav['surface']); ?> m²</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-door-open"></i>
                                </small>
                                <small class="fw-bold"><?php echo Security::escape($fav['nombre_pieces']); ?></small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-euro-sign"></i>
                                </small>
                                <small class="fw-bold"><?php echo number_format($fav['prix_mensuel'], 0); ?></small>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-0 text-primary fw-bold">
                                €<?php echo number_format($fav['prix_mensuel'], 2, ',', ' '); ?>/mois
                            </h6>
                            <?php if (!empty($fav['charges'])): ?>
                                <small class="text-muted">
                                    + €<?php echo number_format($fav['charges'], 2, ',', ' '); ?> charges
                                </small>
                            <?php endif; ?>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <a 
                                href="<?php echo URLROOT; ?>/annonce/detail/<?php echo $fav['id']; ?>"
                                class="btn btn-primary btn-sm"
                            >
                                <i class="fas fa-eye"></i> Voir le détail
                            </a>
                            <a 
                                href="<?php echo URLROOT; ?>/candidature/create?annonce=<?php echo $fav['id']; ?>"
                                class="btn btn-outline-success btn-sm"
                            >
                                <i class="fas fa-check"></i> Postuler
                            </a>
                        </div>
                    </div>

                    <!-- Date ajout -->
                    <div class="card-footer bg-light py-2 text-center">
                        <small class="text-muted">
                            Ajouté le <?php echo date('d/m/Y', strtotime($fav['date_ajout'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Aucun favori -->
        <div class="col-12">
            <div class="alert alert-info border-0 rounded-4" role="alert">
                <div class="text-center py-5">
                    <i class="fas fa-heart" style="font-size: 4rem; color: rgba(0,0,0,0.1);"></i>
                    <h4 class="mt-3 mb-2">Vous n'avez pas encore de favoris</h4>
                    <p class="text-muted mb-3">Ajoutez des annonces à vos favoris pour les retrouver facilement.</p>
                    <a href="<?php echo URLROOT; ?>/annonce" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Parcourir les annonces
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Styles -->
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }

    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .object-fit-cover {
        object-fit: cover;
    }
</style>

<script>
    function removeFavorite(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet annonce de vos favoris ?')) {
            // Appel AJAX pour supprimer le favori
            fetch('<?php echo URLROOT; ?>/favoris/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?php echo Security::csrfToken(); ?>'
                },
                body: JSON.stringify({ annonce_id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
