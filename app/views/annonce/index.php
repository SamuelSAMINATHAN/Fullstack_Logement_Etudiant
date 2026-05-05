<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="fw-bold text-primary mb-1">
            <i class="fas fa-building"></i> Annonces de logements
        </h1>
        <p class="text-muted">Trouvez le logement étudiant qui vous convient</p>
    </div>
    <div class="col-md-4 text-md-end">
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'bailleur'): ?>
            <a href="<?php echo URLROOT; ?>/annonce/create" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Nouvelle annonce
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="card border-0 shadow-sm mb-4 rounded-3">
    <div class="card-body p-4">
        <form method="GET" action="<?php echo URLROOT; ?>/annonce" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Rechercher</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        class="form-control border-start-0" 
                        id="search"
                        name="search" 
                        placeholder="Ville, quartier..."
                        value="<?php echo Security::escape($search ?? ''); ?>"
                    >
                </div>
            </div>

            <div class="col-md-2">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type">
                    <option value="">Tous les types</option>
                    <option value="studio" <?php echo (isset($type) && $type === 'studio' ? 'selected' : ''); ?>>Studio</option>
                    <option value="t1" <?php echo (isset($type) && $type === 't1' ? 'selected' : ''); ?>>T1</option>
                    <option value="t2" <?php echo (isset($type) && $type === 't2' ? 'selected' : ''); ?>>T2</option>
                    <option value="t3" <?php echo (isset($type) && $type === 't3' ? 'selected' : ''); ?>>T3+</option>
                    <option value="colocation" <?php echo (isset($type) && $type === 'colocation' ? 'selected' : ''); ?>>Colocation</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="prix_min" class="form-label">Prix min (€)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="prix_min" 
                    name="prix_min"
                    placeholder="Min"
                    value="<?php echo Security::escape($prix_min ?? ''); ?>"
                >
            </div>

            <div class="col-md-2">
                <label for="prix_max" class="form-label">Prix max (€)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="prix_max" 
                    name="prix_max"
                    placeholder="Max"
                    value="<?php echo Security::escape($prix_max ?? ''); ?>"
                >
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Nombre de résultats -->
<div class="mb-3">
    <p class="text-muted">
        <strong><?php echo count($annonces ?? []); ?></strong> 
        annonce(s) trouvée(s)
    </p>
</div>

<!-- Liste des annonces -->
<div class="row g-4">
    <?php if (!empty($annonces)): ?>
        <?php foreach ($annonces as $annonce): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden hover-lift transition">
                    <!-- Image principale -->
                    <div class="position-relative" style="height: 250px; overflow: hidden;">
                        <?php if (!empty($annonce['photo_url'])): ?>
                            <img 
                                src="<?php echo Security::escape($annonce['photo_url']); ?>" 
                                alt="<?php echo Security::escape($annonce['titre']); ?>"
                                class="card-img-top w-100 h-100 object-fit-cover"
                            >
                        <?php else: ?>
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Badge type -->
                        <span class="badge bg-primary position-absolute top-3 start-3">
                            <?php echo Security::escape(ucfirst($annonce['type_logement'])); ?>
                        </span>

                        <!-- Badge prix -->
                        <span class="badge bg-success position-absolute top-3 end-3">
                            <i class="fas fa-euro-sign"></i> 
                            <?php echo number_format($annonce['prix_mensuel'], 0, ',', ' '); ?>
                        </span>

                        <!-- Favoris button -->
                        <button 
                            class="btn btn-light btn-sm rounded-circle position-absolute bottom-3 end-3 shadow"
                            onclick="toggleFavorite(<?php echo $annonce['id']; ?>)"
                        >
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>

                    <!-- Contenu -->
                    <div class="card-body">
                        <!-- Titre -->
                        <h5 class="card-title fw-bold text-truncate-2">
                            <?php echo Security::escape($annonce['titre']); ?>
                        </h5>

                        <!-- Localisation -->
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <?php echo Security::escape($annonce['ville'] ?? ''); ?>
                        </p>

                        <!-- Description -->
                        <p class="card-text text-muted small mb-3">
                            <?php echo Security::escape(substr($annonce['description'], 0, 80)) . '...'; ?>
                        </p>

                        <!-- Caractéristiques -->
                        <div class="row g-2 mb-3 text-center">
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-ruler-combined text-info"></i>
                                </small>
                                <small class="fw-bold"><?php echo Security::escape($annonce['surface'] ?? '0'); ?> m²</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-door-open text-warning"></i>
                                </small>
                                <small class="fw-bold"><?php echo Security::escape($annonce['nombre_pieces'] ?? '0'); ?> pièces</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-user text-success"></i>
                                </small>
                                <small class="fw-bold"><?php echo Security::escape($annonce['nb_candidatures'] ?? '0'); ?> cand.</small>
                            </div>
                        </div>

                        <!-- Notation -->
                        <?php if (!empty($annonce['note_moyenne'])): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <?php echo number_format($annonce['note_moyenne'], 1, ',', ' '); ?>/5
                                </small>
                                <small class="text-muted">(<?php echo $annonce['nombre_avis'] ?? '0'; ?> avis)</small>
                            </div>
                        <?php endif; ?>

                        <!-- Bouton détail -->
                        <a 
                            href="<?php echo URLROOT; ?>/annonce/detail/<?php echo $annonce['id']; ?>" 
                            class="btn btn-primary btn-sm w-100"
                        >
                            <i class="fas fa-eye"></i> Voir les détails
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Aucune annonce -->
        <div class="col-12">
            <div class="alert alert-info border-0 rounded-4" role="alert">
                <div class="text-center py-5">
                    <i class="fas fa-search" style="font-size: 4rem; color: rgba(0,0,0,0.1);"></i>
                    <h4 class="mt-3 mb-2">Aucune annonce trouvée</h4>
                    <p class="text-muted">Essayez de modifier vos filtres de recherche ou réessayez plus tard.</p>
                    <a href="<?php echo URLROOT; ?>/annonce" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-redo"></i> Réinitialiser les filtres
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- CSS personnalisé pour cette page -->
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
    function toggleFavorite(id) {
        // Fonction à implémenter pour ajouter/retirer des favoris
        console.log('Toggle favorite for:', id);
    }
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
