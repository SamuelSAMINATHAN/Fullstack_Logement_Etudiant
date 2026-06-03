<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <?php require APPROOT . '/views/user/sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mes Annonces</h2>
            <a href="<?= URLROOT ?>/annonce/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Déposer une annonce
            </a>
        </div>

        <?php if (empty($annonces)): ?>
            <div class="card shadow-sm text-center p-5">
                <p class="text-muted">Vous n'avez pas encore publié d'annonce.</p>
                <a href="<?= URLROOT ?>/annonce/create" class="btn btn-outline-primary mx-auto">Publier ma première annonce</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($annonces as $annonce): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-truncate"><?= Security::escape($annonce['titre']) ?></h5>
                                <p class="text-muted small mb-2"><?= Security::escape($annonce['localisation']) ?></p>
                                <p class="fw-bold text-primary mb-3"><?= number_format($annonce['prix'], 2) ?> € / mois</p>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= URLROOT ?>/annonce/detail/<?= $annonce['idAnnonce'] ?>" class="btn btn-sm btn-outline-info">Voir</a>
                                    <div>
                                        <a href="<?= URLROOT ?>/annonce/edit/<?= $annonce['idAnnonce'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <button onclick="if(confirm('Supprimer cette annonce ?')) window.location.href='<?= URLROOT ?>/annonce/delete/<?= $annonce['idAnnonce'] ?>'" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
