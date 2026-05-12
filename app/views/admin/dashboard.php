<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <!-- Menu latéral Admin -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <a href="<?php echo URLROOT; ?>/admin/dashboard" class="list-group-item list-group-item-action active bg-dark border-dark">
                <i class="fas fa-shield-alt me-2"></i> Tableau de bord
            </a>
            <a href="<?php echo URLROOT; ?>/admin/users" class="list-group-item list-group-item-action">
                <i class="fas fa-users me-2"></i> Gestion Utilisateurs
            </a>
            <a href="<?php echo URLROOT; ?>/admin/annonces" class="list-group-item list-group-item-action">
                <i class="fas fa-building me-2"></i> Gestion Annonces
            </a>
            <a href="<?php echo URLROOT; ?>/admin/faq" class="list-group-item list-group-item-action">
                <i class="fas fa-question-circle me-2"></i> Gestion FAQ
            </a>
            <a href="<?php echo URLROOT; ?>/admin/cgu" class="list-group-item list-group-item-action">
                <i class="fas fa-file-contract me-2"></i> CGU & Mentions
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-9">
        <h2 class="mb-4">Administration Dorocho</h2>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h5 class="card-title">Utilisateurs</h5>
                        <h2 class="display-4"><?php echo isset($stats['users_count']) ? $stats['users_count'] : 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-building fa-3x mb-3"></i>
                        <h5 class="card-title">Annonces actives</h5>
                        <h2 class="display-4"><?php echo isset($stats['annonces_count']) ? $stats['annonces_count'] : 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-flag fa-3x mb-3"></i>
                        <h5 class="card-title">Signalements</h5>
                        <h2 class="display-4"><?php echo isset($stats['signalements_count']) ? $stats['signalements_count'] : 0; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Dernières inscriptions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_users)): ?>
                                <?php foreach ($recent_users as $user): ?>
                                    <tr>
                                        <td><?php echo Security::escape($user['prenom'] . ' ' . $user['nom']); ?></td>
                                        <td><?php echo Security::escape($user['email']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['role'] === 'etudiant' ? 'info' : 'primary'; ?>">
                                                <?php echo ucfirst(Security::escape($user['role'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Aucune inscription récente.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
