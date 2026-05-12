<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row text-center mb-5">
    <div class="col-12">
        <h1 class="display-4 fw-bold text-primary">Bienvenue sur Dorocho</h1>
        <p class="lead">Trouvez le logement étudiant idéal en quelques clics.</p>
        
        <form action="<?php echo URLROOT; ?>/annonce/recherche" method="GET" class="mt-4">
            <div class="input-group input-group-lg w-75 mx-auto">
                <input type="text" name="q" class="form-control" placeholder="Rechercher une ville, un campus..." required>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Dernières annonces</h2>
    </div>
    
    <?php if (!empty($annonces)): ?>
        <?php foreach ($annonces as $annonce): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo Security::escape($annonce['image'] ?? 'default.jpg'); ?>" class="card-img-top" alt="Image logement" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo Security::escape($annonce['titre']); ?></h5>
                        <p class="card-text text-muted">
                            <i class="fas fa-map-marker-alt"></i> <?php echo Security::escape($annonce['ville']); ?>
                        </p>
                        <p class="card-text fw-bold text-primary">
                            <?php echo Security::escape($annonce['loyer']); ?> € / mois
                        </p>
                        <a href="<?php echo URLROOT; ?>/annonce/detail/<?php echo Security::escape($annonce['id']); ?>" class="btn btn-outline-primary w-100">
                            Voir les détails
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                Aucune annonce n'est disponible pour le moment.
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
