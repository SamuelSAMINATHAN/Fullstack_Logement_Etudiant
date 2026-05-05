<?php
require APPROOT . '/views/layout/header.php';
?>

<!-- Hero Section -->
<section class="py-5" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; margin-top: -70px; padding-top: 120px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    Trouvez votre logement étudiant
                </h1>
                <p class="lead mb-4">
                    Dorocho est la plateforme leader pour trouver un logement sécurisé et abordable. 
                    Connectez-vous avec des propriétaires de confiance et d'autres étudiants.
                </p>
                <div class="d-flex gap-2">
                    <a href="<?php echo URLROOT; ?>/annonce" class="btn btn-light btn-lg fw-bold">
                        <i class="fas fa-search"></i> Parcourir les annonces
                    </a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/auth/register" class="btn btn-outline-light btn-lg fw-bold">
                            <i class="fas fa-user-plus"></i> S'inscrire
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div style="text-align: center; font-size: 5rem; opacity: 0.2;">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistiques -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <h3 class="fw-bold text-primary display-6"><?php echo $stats['total_annonces'] ?? '500+'; ?></h3>
                <p class="text-muted">Annonces actives</p>
            </div>
            <div class="col-md-3">
                <h3 class="fw-bold text-success display-6"><?php echo $stats['utilisateurs'] ?? '2000+'; ?></h3>
                <p class="text-muted">Utilisateurs actifs</p>
            </div>
            <div class="col-md-3">
                <h3 class="fw-bold text-warning display-6"><?php echo $stats['villes'] ?? '50+'; ?></h3>
                <p class="text-muted">Villes couvertes</p>
            </div>
            <div class="col-md-3">
                <h3 class="fw-bold text-info display-6"><?php echo $stats['logements_loues'] ?? '1000+'; ?></h3>
                <p class="text-muted">Logements loués</p>
            </div>
        </div>
    </div>
</section>

<!-- Fonctionnalités principales -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Comment ça marche ?</h2>
        <div class="row g-4">
            <!-- Étape 1 -->
            <div class="col-md-4">
                <div class="card border-0 text-center rounded-4 shadow-sm h-100 p-4">
                    <div class="mb-3">
                        <span class="badge bg-primary p-3" style="font-size: 2rem;">
                            <i class="fas fa-user-plus"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">1. S'inscrire</h5>
                    <p class="text-muted">
                        Créez un compte en quelques secondes. Aucune carte bancaire requise.
                    </p>
                </div>
            </div>

            <!-- Étape 2 -->
            <div class="col-md-4">
                <div class="card border-0 text-center rounded-4 shadow-sm h-100 p-4">
                    <div class="mb-3">
                        <span class="badge bg-success p-3" style="font-size: 2rem;">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">2. Rechercher</h5>
                    <p class="text-muted">
                        Filtrez par budget, localisation, type de logement. Retrouvez votre perle rare.
                    </p>
                </div>
            </div>

            <!-- Étape 3 -->
            <div class="col-md-4">
                <div class="card border-0 text-center rounded-4 shadow-sm h-100 p-4">
                    <div class="mb-3">
                        <span class="badge bg-warning p-3" style="font-size: 2rem;">
                            <i class="fas fa-handshake"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2">3. Contacter</h5>
                    <p class="text-muted">
                        Échangez avec les propriétaires. Visitez le logement. Signez le bail.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Annonces populaires -->
<?php if (!empty($annonces_populaires)): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-4">Annonces populaires</h2>
            <div class="row g-4">
                <?php foreach (array_slice($annonces_populaires, 0, 6) as $annonce): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden hover-lift">
                            <div style="height: 200px; overflow: hidden; position: relative;">
                                <img 
                                    src="<?php echo Security::escape($annonce['photo_url'] ?? '/img/default-listing.jpg'); ?>"
                                    alt="<?php echo Security::escape($annonce['titre']); ?>"
                                    class="w-100 h-100 object-fit-cover"
                                >
                                <span class="badge bg-primary position-absolute top-2 start-2">
                                    <?php echo Security::escape(ucfirst($annonce['type_logement'])); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-truncate">
                                    <?php echo Security::escape($annonce['titre']); ?>
                                </h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo Security::escape($annonce['ville']); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-primary fw-bold">
                                        €<?php echo number_format($annonce['prix_mensuel'], 0); ?>/mois
                                    </h6>
                                    <a href="<?php echo URLROOT; ?>/annonce/detail/<?php echo $annonce['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="<?php echo URLROOT; ?>/annonce" class="btn btn-primary btn-lg">
                    <i class="fas fa-list"></i> Voir toutes les annonces
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Témoignages -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Ce que disent nos utilisateurs</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3 fst-italic">
                            "J'ai trouvé mon logement en une semaine ! L'interface est facile à utiliser et les propriétaires sont sérieux."
                        </p>
                        <strong>Marie L.</strong>
                        <small class="text-muted d-block">Étudiante à Lyon</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3 fst-italic">
                            "Plateforme très pratique. J'ai reçu beaucoup de candidatures qualifiées pour mes annonces."
                        </p>
                        <strong>Pierre D.</strong>
                        <small class="text-muted d-block">Propriétaire à Toulouse</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3 fst-italic">
                            "Service client très réactif et à l'écoute. Je recommande vivement Dorocho !"
                        </p>
                        <strong>Sophie B.</strong>
                        <small class="text-muted d-block">Étudiante à Bordeaux</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA final -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Prêt à trouver votre logement ?</h2>
        <p class="lead mb-4">Rejoignez des milliers d'étudiants qui ont trouvé leur home sweet home sur Dorocho.</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="<?php echo URLROOT; ?>/annonce" class="btn btn-light btn-lg fw-bold">
                <i class="fas fa-search"></i> Commencer la recherche
            </a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?php echo URLROOT; ?>/auth/register" class="btn btn-outline-light btn-lg fw-bold">
                    <i class="fas fa-user-plus"></i> S'inscrire gratuitement
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Styles -->
<style>
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }

    .object-fit-cover {
        object-fit: cover;
    }
</style>

<?php require APPROOT . '/views/layout/footer.php'; ?>

