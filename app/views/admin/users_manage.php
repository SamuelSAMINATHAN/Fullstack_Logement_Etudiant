<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <!-- Menu latéral Admin -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <a href="<?php echo URLROOT; ?>/admin/dashboard" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-shield-alt me-2"></i> Administration
            </a>
            <a href="<?php echo URLROOT; ?>/admin/users" class="list-group-item list-group-item-action active">
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
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestion des Utilisateurs</h5>
                <form action="<?php echo URLROOT; ?>/admin/users" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Rechercher..." value="<?php echo isset($_GET['search']) ? Security::escape($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date d'inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo Security::escape($user['id']); ?></td>
                                        <td><?php echo Security::escape($user['prenom'] . ' ' . $user['nom']); ?></td>
                                        <td><?php echo Security::escape($user['email']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['role'] === 'etudiant' ? 'info' : ($user['role'] === 'bailleur' ? 'primary' : 'dark'); ?>">
                                                <?php echo ucfirst(Security::escape($user['role'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?></td>
                                        <td>
                                            <?php if (isset($user['banni']) && $user['banni']): ?>
                                                <span class="badge bg-danger">Banni</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo URLROOT; ?>/admin/user/<?php echo Security::escape($user['id']); ?>" class="btn btn-outline-secondary" title="Voir profil">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($user['role'] !== 'admin'): ?>
                                                    <form action="<?php echo URLROOT; ?>/admin/banUser" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir <?php echo (isset($user['banni']) && $user['banni']) ? 'débannir' : 'bannir'; ?> cet utilisateur ?');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                                                        <input type="hidden" name="user_id" value="<?php echo Security::escape($user['id']); ?>">
                                                        <button type="submit" class="btn btn-outline-<?php echo (isset($user['banni']) && $user['banni']) ? 'success' : 'danger'; ?>" title="<?php echo (isset($user['banni']) && $user['banni']) ? 'Débannir' : 'Bannir'; ?>">
                                                            <i class="fas fa-<?php echo (isset($user['banni']) && $user['banni']) ? 'check-circle' : 'ban'; ?>"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Aucun utilisateur trouvé.</td>
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
