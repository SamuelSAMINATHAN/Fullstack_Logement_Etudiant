<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Modération des Signalements</h1>
    <div class="btn-group">
        <a href="<?= URLROOT ?>/adminsignalement/index" class="btn btn-sm btn-outline-secondary <?= !$current_status ? 'active' : '' ?>">Tous</a>
        <a href="<?= URLROOT ?>/adminsignalement/index?status=En attente" class="btn btn-sm btn-outline-secondary <?= $current_status === 'En attente' ? 'active' : '' ?>">En attente</a>
        <a href="<?= URLROOT ?>/adminsignalement/index?status=Traité" class="btn btn-sm btn-outline-secondary <?= $current_status === 'Traité' ? 'active' : '' ?>">Traités</a>
        <a href="<?= URLROOT ?>/adminsignalement/index?status=Rejeté" class="btn btn-sm btn-outline-secondary <?= $current_status === 'Rejeté' ? 'active' : '' ?>">Rejetés</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (empty($reports)): ?>
            <p class="text-center text-muted my-4">Aucun signalement trouvé.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Annonce</th>
                            <th>Bailleur</th>
                            <th>Signalé par</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                        <tr>
                            <td class="small"><?= date('d/m/Y H:i', strtotime($report['dateSignalement'])) ?></td>
                            <td>
                                <a href="<?= URLROOT ?>/annonce/detail/<?= $report['idAnnonce'] ?>" target="_blank" class="text-decoration-none">
                                    <?= \App\Core\Security::escape($report['annonce_titre']) ?>
                                </a>
                            </td>
                            <td><?= \App\Core\Security::escape($report['bailleur_nom']) ?></td>
                            <td><?= \App\Core\Security::escape($report['student_prenom'] . ' ' . $report['student_nom']) ?></td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?= \App\Core\Security::escape($report['motif']) ?>">
                                    <?= \App\Core\Security::escape($report['motif']) ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    $badgeClass = 'bg-secondary';
                                    if ($report['statut'] === 'En attente') $badgeClass = 'bg-warning text-dark';
                                    if ($report['statut'] === 'Traité') $badgeClass = 'bg-success';
                                    if ($report['statut'] === 'Rejeté') $badgeClass = 'bg-danger';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $report['statut'] ?></span>
                            </td>
                            <td>
                                <?php if ($report['statut'] === 'En attente'): ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                            Traiter
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form action="<?= URLROOT ?>/adminsignalement/process/<?= $report['idSignalement'] ?>" method="POST">
                                                    <input type="hidden" name="action" value="none">
                                                    <button type="submit" class="dropdown-item">Marquer comme traité</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="<?= URLROOT ?>/adminsignalement/process/<?= $report['idSignalement'] ?>" method="POST">
                                                    <input type="hidden" name="action" value="delete_annonce">
                                                    <button type="submit" class="dropdown-item text-danger">Supprimer l'annonce</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="<?= URLROOT ?>/adminsignalement/process/<?= $report['idSignalement'] ?>" method="POST">
                                                    <input type="hidden" name="action" value="shadowban_bailleur">
                                                    <button type="submit" class="dropdown-item text-danger">Shadowban le bailleur</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="<?= URLROOT ?>/adminsignalement/reject/<?= $report['idSignalement'] ?>" class="dropdown-item text-secondary">Rejeter le signalement</a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">Aucune action</span>
                                <?php endif; ?>
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
