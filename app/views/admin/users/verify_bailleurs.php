<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Vérification des Bailleurs</h1>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Bailleurs en attente de vérification</h5>
    </div>
    <div class="card-body">
        <?php if (empty($bailleurs)): ?>
            <p class="text-center text-muted my-4">Aucun bailleur à vérifier pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Date inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bailleurs as $bailleur): ?>
                        <tr>
                            <td><?= \App\Core\Security::escape($bailleur['prenom'] . ' ' . $bailleur['nom']) ?></td>
                            <td><?= \App\Core\Security::escape($bailleur['email']) ?></td>
                            <td><?= date('d/m/Y', strtotime($bailleur['date_acceptation_cgu'])) ?></td>
                            <td>
                                <a href="<?= URLROOT ?>/admin/verify/<?= $bailleur['idUtilisateur'] ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Accorder le badge vérifié
                                </a>
                                <a href="<?= URLROOT ?>/admin/shadowban/<?= $bailleur['idUtilisateur'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-user-secret"></i> Marquer comme suspect
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

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
