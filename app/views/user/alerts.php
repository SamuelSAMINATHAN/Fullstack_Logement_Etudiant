<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <?php require APPROOT . '/views/user/sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4">Mes Alertes</h2>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Créer une nouvelle alerte</h5>
            </div>
            <div class="card-body">
                <form action="<?= URLROOT ?>/alerte/create" method="POST" class="row g-3">
                    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                    <div class="col-md-4">
                        <label for="localisation" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="localisation" name="localisation" placeholder="Ex: Paris" required>
                    </div>
                    <div class="col-md-4">
                        <label for="budgetMax" class="form-label">Budget Max (€)</label>
                        <input type="number" class="form-control" id="budgetMax" name="budgetMax" placeholder="Ex: 800">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="colocation" name="colocation">
                            <label class="form-check-label" for="colocation">Colocation uniquement</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Enregistrer l'alerte</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Mes alertes actives</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($alertes)): ?>
                    <p class="text-center text-muted my-4">Vous n'avez pas encore d'alertes.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ville</th>
                                    <th>Budget Max</th>
                                    <th>Colocation</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertes as $alerte): ?>
                                    <tr>
                                        <td><?= Security::escape($alerte['localisation']) ?></td>
                                        <td><?= $alerte['budgetMax'] ? Security::escape($alerte['budgetMax']) . ' €' : 'Non précisé' ?></td>
                                        <td><?= $alerte['colocation'] ? 'Oui' : 'Indifférent' ?></td>
                                        <td class="text-end">
                                            <a href="<?= URLROOT ?>/alerte/delete/<?= $alerte['idAlerte'] ?>" class="text-danger" onclick="return confirm('Supprimer cette alerte ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
