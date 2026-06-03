<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tableau de Bord</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title text-uppercase mb-1">Utilisateurs</h5>
                    <h2 class="mb-0"><?= $stats['users_count'] ?></h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="<?= URLROOT ?>/admin/users">Voir détails</a>
                <i class="fas fa-angle-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title text-uppercase mb-1">Annonces</h5>
                    <h2 class="mb-0"><?= $stats['annonces_count'] ?></h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-building fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="<?= URLROOT ?>/adminannonce/index">Voir détails</a>
                <i class="fas fa-angle-right"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title text-uppercase mb-1">Signalements</h5>
                    <h2 class="mb-0"><?= $stats['signalements_count'] ?></h2>
                </div>
                <div class="ms-3">
                    <i class="fas fa-flag fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-dark stretched-link" href="<?= URLROOT ?>/adminsignalement/index">Voir détails</a>
                <i class="fas fa-angle-right"></i>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-history me-1"></i>
        Utilisateurs Récents
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($recent_users, 0, 10) as $user): ?>
                    <tr>
                        <td><?= $user['idUtilisateur'] ?></td>
                        <td><?= \App\Core\Security::escape($user['prenom'] . ' ' . $user['nom']) ?></td>
                        <td><?= \App\Core\Security::escape($user['email']) ?></td>
                        <td>
                            <span class="badge <?= $user['role'] === 'etudiant' ? 'bg-info' : 'bg-primary' ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($user['date_acceptation_cgu'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
