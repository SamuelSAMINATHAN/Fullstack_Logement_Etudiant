<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestion des Textes Légaux</h1>
    <a href="<?= URLROOT ?>/admin/addLegal" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Ajouter un texte légal
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (!empty($infos)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Titre</th>
                            <th>Dernière mise à jour</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($infos as $info): ?>
                        <tr>
                            <td>
                                <h6 class="mb-0"><?= \App\Core\Security::escape($info['titre']) ?></h6>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <?= !empty($info['dateMiseAJour']) ? date('d/m/Y H:i', strtotime($info['dateMiseAJour'])) : 'Jamais' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= URLROOT ?>/admin/editLegal/<?= $info['idInfo'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i> Modifier
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i> Aucun texte légal trouvé en base de données.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
