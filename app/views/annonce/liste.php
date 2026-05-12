<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>Annonces de logements</h2>
    </div>
    <div class="col-md-4 text-md-end">
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'bailleur'): ?>
            <a href="<?php echo URLROOT; ?>/annonce/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Publier une annonce
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filtres de recherche avancée -->
<div class="card shadow-sm mb-5">
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/annonce" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="q" class="form-label">Ville ou Mots-clés</label>
                <input type="text" name="q" id="q" class="form-control" value="<?php echo isset($q) ? Security::escape($q) : ''; ?>">
            </div>
            <div class="col-md-3">
                <label for="type" class="form-label">Type de logement</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="chambre" <?php echo (isset($type) && $type === 'chambre') ? 'selected' : ''; ?>>Chambre</option>
                    <option value="studio" <?php echo (isset($type) && $type === 'studio') ? 'selected' : ''; ?>>Studio</option>
                    <option value="t1" <?php echo (isset($type) && $type === 't1') ? 'selected' : ''; ?>>T1 / T1 Bis</option>
                    <option value="t2" <?php echo (isset($type) && $type === 't2') ? 'selected' : ''; ?>>T2</option>
                    <option value="colocation" <?php echo (isset($type) && $type === 'colocation') ? 'selected' : ''; ?>>Colocation</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="budget_max" class="form-label">Budget max (€)</label>
                <input type="number" name="budget_max" id="budget_max" class="form-control" value="<?php echo isset($budget_max) ? Security::escape($budget_max) : ''; ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <?php if (!empty($annonces)): ?>
        <?php foreach ($annonces as $annonce): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo Security::escape($annonce['image'] ?? 'default.jpg'); ?>" class="card-img-top" alt="Image logement" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title text-truncate mb-0" title="<?php echo Security::escape($annonce['titre']); ?>">
                                <?php echo Security::escape($annonce['titre']); ?>
                            </h5>
                            <span class="badge bg-primary"><?php echo Security::escape($annonce['type_logement']); ?></span>
                        </div>
                        
                        <p class="card-text text-muted mb-2">
                            <i class="fas fa-map-marker-alt text-danger"></i> <?php echo Security::escape($annonce['ville']); ?> (<?php echo Security::escape($annonce['code_postal']); ?>)
                        </p>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted small">
                            <span><i class="fas fa-vector-square"></i> <?php echo Security::escape($annonce['surface']); ?> m²</span>
                            <?php if ($annonce['meuble']): ?>
                                <span><i class="fas fa-couch"></i> Meublé</span>
                            <?php endif; ?>
                            <?php if ($annonce['colocation_acceptee']): ?>
                                <span><i class="fas fa-users"></i> Coloc OK</span>
                            <?php endif; ?>
                        </div>
                        
                        <h4 class="text-primary mb-3"><?php echo Security::escape($annonce['loyer']); ?> € <small class="text-muted fs-6">/ mois cc</small></h4>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo URLROOT; ?>/annonce/detail/<?php echo Security::escape($annonce['id']); ?>" class="btn btn-outline-primary">Détails</a>
                            
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant'): ?>
                                <form action="<?php echo URLROOT; ?>/favoris/add" method="POST" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                                    <input type="hidden" name="annonce_id" value="<?php echo Security::escape($annonce['id']); ?>">
                                    <button type="submit" class="btn btn-outline-danger" title="Ajouter aux favoris">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer text-muted small">
                        Publiée le <?php echo date('d/m/Y', strtotime($annonce['date_creation'])); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h3>Aucun logement trouvé</h3>
            <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
