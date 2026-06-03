<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestion des Utilisateurs</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut/Détails</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['idUtilisateur'] ?></td>
                        <td>
                            <strong><?= \App\Core\Security::escape($user['prenom'] . ' ' . $user['nom']) ?></strong>
                        </td>
                        <td><?= \App\Core\Security::escape($user['email']) ?></td>
                        <td>
                            <form action="<?= URLROOT ?>/admin/changeRole/<?= $user['idUtilisateur'] ?>" method="POST" class="d-flex gap-1">
                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="etudiant" <?= $user['role'] === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
                                    <option value="bailleur" <?= $user['role'] === 'bailleur' ? 'selected' : '' ?>>Bailleur</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <?php if ($user['role'] === 'bailleur'): ?>
                                <?php if ($user['estVerifie']): ?>
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Vérifié</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non vérifié</span>
                                <?php endif; ?>

                                <?php if ($user['estShadowban']): ?>
                                    <span class="badge bg-danger">Shadowbanni</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted small">Étudiant</span>
                            <?php endif; ?>
                            
                            <?php if ($user['demande_suppression']): ?>
                                <span class="badge bg-warning text-dark">Demande suppression</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <?php if ($user['role'] === 'bailleur'): ?>
                                    <?php if (!$user['estVerifie']): ?>
                                        <a href="<?= URLROOT ?>/admin/verify/<?= $user['idUtilisateur'] ?>" class="btn btn-sm btn-outline-success" title="Vérifier">
                                            <i class="fas fa-user-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?= URLROOT ?>/admin/shadowban/<?= $user['idUtilisateur'] ?>?status=<?= $user['estShadowban'] ? '0' : '1' ?>" 
                                       class="btn btn-sm btn-outline-<?= $user['estShadowban'] ? 'info' : 'warning' ?>" 
                                       title="<?= $user['estShadowban'] ? 'Retirer Shadowban' : 'Shadowban' ?>">
                                        <i class="fas <?= $user['estShadowban'] ? 'fa-user-slash' : 'fa-user-secret' ?>"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="if(confirm('Supprimer cet utilisateur ?')) window.location.href='<?= URLROOT ?>/admin/deleteUser/<?= $user['idUtilisateur'] ?>'">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
