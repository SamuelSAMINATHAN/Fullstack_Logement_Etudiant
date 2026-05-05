<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="fw-bold text-primary mb-1">
            <i class="fas fa-tachometer-alt"></i> Tableau de bord
        </h1>
        <p class="text-muted">Bienvenue, <?php echo Security::escape($_SESSION['user_prenom'] ?? 'Utilisateur'); ?> !</p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary" onclick="location.href='<?php echo URLROOT; ?>/profil/profile'">
                <i class="fas fa-user"></i> Mon profil
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="location.href='<?php echo URLROOT; ?>/message/inbox'">
                <i class="fas fa-envelope"></i> Messages
            </button>
        </div>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row g-3 mb-4">
    <!-- Annonces -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-gradient-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="opacity-75">Mes annonces</small>
                        <h3 class="fw-bold mb-0"><?php echo $stats['total_annonces'] ?? 0; ?></h3>
                    </div>
                    <i class="fas fa-building" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
                <a href="<?php echo URLROOT; ?>/annonce" class="text-white text-decoration-none small">
                    Gérer <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Candidatures (pour propriétaires) -->
    <?php if ($_SESSION['user_role'] === 'bailleur'): ?>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Candidatures</small>
                            <h3 class="fw-bold mb-0"><?php echo $stats['candidatures'] ?? 0; ?></h3>
                        </div>
                        <i class="fas fa-user-check" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                    <a href="<?php echo URLROOT; ?>/candidature/received" class="text-white text-decoration-none small">
                        Consulter <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-warning text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Vues cette semaine</small>
                            <h3 class="fw-bold mb-0"><?php echo $stats['vues_semaine'] ?? 0; ?></h3>
                        </div>
                        <i class="fas fa-eye" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                    <p class="mb-0 small">+<?php echo $stats['vues_augmentation'] ?? 0; ?>% par rapport à la semaine précédente</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Revenus</small>
                            <h3 class="fw-bold mb-0">€<?php echo number_format($stats['revenus_mois'] ?? 0, 0); ?></h3>
                        </div>
                        <i class="fas fa-euro-sign" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                    <p class="mb-0 small">Ce mois-ci</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Pour les étudiants -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Favoris</small>
                            <h3 class="fw-bold mb-0"><?php echo $stats['favoris'] ?? 0; ?></h3>
                        </div>
                        <i class="fas fa-heart" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                    <a href="<?php echo URLROOT; ?>/favoris" class="text-white text-decoration-none small">
                        Voir <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-warning text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Candidatures</small>
                            <h3 class="fw-bold mb-0"><?php echo $stats['mes_candidatures'] ?? 0; ?></h3>
                        </div>
                        <i class="fas fa-file-alt" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                    <p class="mb-0 small"><?php echo $stats['candidatures_en_attente'] ?? 0; ?> en attente</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-info text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="opacity-75">Alertes</small>
                            <h3 class="fw-bold mb-0"><?php echo $stats['alertes'] ?? 0; ?></h3>
                        </div>
                        <i class="fas fa-bell" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                    <a href="<?php echo URLROOT; ?>/alerte" class="text-white text-decoration-none small">
                        Gérer <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <!-- Actions rapides (30%) -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 p-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-bolt text-warning"></i> Actions rapides
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if ($_SESSION['user_role'] === 'bailleur'): ?>
                        <a href="<?php echo URLROOT; ?>/annonce/create" class="list-group-item list-group-item-action py-3 px-4">
                            <i class="fas fa-plus-circle text-primary"></i> Créer une annonce
                        </a>
                        <a href="<?php echo URLROOT; ?>/candidature/received" class="list-group-item list-group-item-action py-3 px-4">
                            <i class="fas fa-tasks text-success"></i> Gérer les candidatures
                        </a>
                        <a href="<?php echo URLROOT; ?>/message/inbox" class="list-group-item list-group-item-action py-3 px-4">
                            <i class="fas fa-envelope text-info"></i> Messages (<?php echo $unread_count ?? 0; ?>)
                        </a>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/annonce" class="list-group-item list-group-item-action py-3 px-4">
                            <i class="fas fa-search text-primary"></i> Parcourir les annonces
                        </a>
                        <a href="<?php echo URLROOT; ?>/alerte/create" class="list-group-item list-group-item-action py-3 px-4">
                            <i class="fas fa-bell text-warning"></i> Créer une alerte
                        </a>
                        <a href="<?php echo URLROOT; ?>/candidature" class="list-group-item list-group-item-action py-3 px-4">
                            <i class="fas fa-file-alt text-success"></i> Mes candidatures
                        </a>
                        <a href="<?php echo URLROOT; ?>/message/inbox" class="list-group-item list-group-item-action py-3 px-4">
                            <i class="fas fa-envelope text-info"></i> Messages (<?php echo $unread_count ?? 0; ?>)
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/profil/profile" class="list-group-item list-group-item-action py-3 px-4">
                        <i class="fas fa-user-cog text-secondary"></i> Paramètres du profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Activité récente (70%) -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 p-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-history text-info"></i> Activité récente
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($activites)): ?>
                    <div class="timeline">
                        <?php foreach ($activites as $activite): ?>
                            <div class="timeline-item mb-3 pb-3 border-bottom">
                                <div class="d-flex">
                                    <div class="timeline-marker me-3">
                                        <span class="badge bg-<?php echo $activite['type_badge'] ?? 'primary'; ?> rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="<?php echo $activite['icon'] ?? 'fas fa-dot-circle'; ?>"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-500">
                                            <?php echo Security::escape($activite['description']); ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            <?php echo $activite['temps_ago'] ?? 'Il y a quelques minutes'; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mt-3">Aucune activité pour le moment</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Annonces récentes -->
<?php if (!empty($annonces_recentes)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-list text-primary"></i> 
                            <?php echo ($_SESSION['user_role'] === 'bailleur' ? 'Mes annonces' : 'Dernières consultations'); ?>
                        </h5>
                        <a href="<?php echo URLROOT; ?>/annonce" class="text-decoration-none">Voir tout</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Titre</th>
                                    <th>Localisation</th>
                                    <th>Prix</th>
                                    <th><?php echo ($_SESSION['user_role'] === 'bailleur' ? 'Vues' : 'Statut'); ?></th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($annonces_recentes as $annonce): ?>
                                    <tr>
                                        <td class="fw-500"><?php echo Security::escape($annonce['titre']); ?></td>
                                        <td><small class="text-muted"><?php echo Security::escape($annonce['ville']); ?></small></td>
                                        <td><strong class="text-primary">€<?php echo number_format($annonce['prix_mensuel'], 0); ?></strong></td>
                                        <td>
                                            <?php if ($_SESSION['user_role'] === 'bailleur'): ?>
                                                <span class="badge bg-info"><?php echo $annonce['nombre_vues'] ?? 0; ?> vues</span>
                                            <?php else: ?>
                                                <span class="badge bg-<?php echo ($annonce['statut'] === 'active' ? 'success' : 'secondary'); ?>">
                                                    <?php echo Security::escape(ucfirst($annonce['statut'])); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/annonce/detail/<?php echo $annonce['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Styles -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        flex-shrink: 0;
    }
</style>

<?php require APPROOT . '/views/layout/footer.php'; ?>
