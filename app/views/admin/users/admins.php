<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h2">Gestion des Administrateurs</h1>
        <a href="<?= URLROOT ?>/admin/createAdmin" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Ajouter un Admin
        </a>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <?php if (empty($admins)): ?>
                <div class="alert alert-info mb-0">Aucun autre administrateur trouvé.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10%;">ID Admin</th>
                                <th style="width: 50%;">Nom Complet</th>
                                <th style="width: 40%;">Identifiant (Login)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $adm): ?>
                                <tr>
                                    <td><strong>#<?= $adm['idAdmin']; ?></strong></td>
                                    <td><?= htmlspecialchars($adm['nom'] ?? 'Non renseigné'); ?></td>
                                    <td><span class="badge bg-dark px-3 py-2"><?= htmlspecialchars($adm['login']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>