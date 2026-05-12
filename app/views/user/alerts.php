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
            <a href="<?php echo URLROOT; ?>/favoris" class="list-group-item list-group-item-action">
                <i class="fas fa-heart me-2"></i> Mes Favoris
            </a>
            <a href="<?php echo URLROOT; ?>/alerte" class="list-group-item list-group-item-action active">
                <i class="fas fa-bell me-2"></i> Mes Alertes
            </a>
            <a href="<?php echo URLROOT; ?>/profil/changePassword" class="list-group-item list-group-item-action">
                <i class="fas fa-lock me-2"></i> Sécurité
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mes Alertes de Recherche</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAlertModal">
                    <i class="fas fa-plus"></i> Créer une alerte
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($alertes)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ville / Mots-clés</th>
                                    <th>Type</th>
                                    <th>Budget max</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertes as $alerte): ?>
                                    <tr>
                                        <td><?php echo Security::escape($alerte['mots_cles']); ?></td>
                                        <td><?php echo Security::escape($alerte['type_logement'] ?: 'Tous'); ?></td>
                                        <td><?php echo $alerte['budget_max'] ? Security::escape($alerte['budget_max']) . ' €' : '-'; ?></td>
                                        <td>
                                            <form action="<?php echo URLROOT; ?>/alerte/delete" method="POST" class="d-inline">
                                                <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                                                <input type="hidden" name="alerte_id" value="<?php echo Security::escape($alerte['id']); ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr ?');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center my-4">Vous n'avez configuré aucune alerte pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Alerte -->
<div class="modal fade" id="addAlertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer une alerte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo URLROOT; ?>/alerte/add" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    
                    <div class="mb-3">
                        <label for="mots_cles" class="form-label">Ville ou mots-clés</label>
                        <input type="text" class="form-control" id="mots_cles" name="mots_cles" required>
                    </div>
                    <div class="mb-3">
                        <label for="type_logement" class="form-label">Type de logement</label>
                        <select class="form-select" id="type_logement" name="type_logement">
                            <option value="">Tous les types</option>
                            <option value="chambre">Chambre</option>
                            <option value="studio">Studio</option>
                            <option value="t1">T1 / T1 Bis</option>
                            <option value="t2">T2</option>
                            <option value="colocation">Colocation</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="budget_max" class="form-label">Budget maximum (€)</label>
                        <input type="number" class="form-control" id="budget_max" name="budget_max">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
