<?php require APPROOT . '/views/layout/header.php'; ?>

<!-- Section Hero -->
<section class="bg-light py-5 mb-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold text-primary mb-3">Dorocho</h1>
                <p class="lead text-muted mb-4">
                    La plateforme de référence dédiée au logement étudiant. Nous simplifions la mise en relation entre bailleurs de confiance et étudiants à la recherche de leur futur foyer, pour une expérience de location sereine et sécurisée.
                </p>
                <a href="<?= URLROOT ?>/annonce" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-sm">
                    <i class="fas fa-search me-2"></i> Trouve ton logement
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Section Pourquoi nous choisir ? -->
<section class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Pourquoi nous choisir ?</h2>
        <div class="mx-auto bg-primary" style="width: 60px; height: 3px;"></div>
    </div>

    <div class="row g-4">
        <!-- Avantage 1 : Simplicité -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-magic fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Simplicité</h5>
                    <p class="card-text text-muted">
                        Une interface intuitive et des filtres précis pour trouver votre logement en quelques clics seulement, sans perdre de temps dans vos recherches.
                    </p>
                </div>
            </div>
        </div>

        <!-- Avantage 2 : Gratuité -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-hand-holding-usd fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Gratuité pour les étudiants</h5>
                    <p class="card-text text-muted">
                        L'accès à toutes les annonces et la mise en relation avec les propriétaires sont totalement gratuits pour tous les étudiants inscrits.
                    </p>
                </div>
            </div>
        </div>

        <!-- Avantage 3 : Vérification -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-shield fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Vérification des bailleurs</h5>
                    <p class="card-text text-muted">
                        Nous vérifions l'identité des bailleurs et la qualité des annonces pour vous garantir des logements réels et éviter toute tentative de fraude.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require APPROOT . '/views/layout/footer.php'; ?>
