<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <h1 class="fw-bold text-primary mb-4">Mentions Légales</h1>

        <!-- Contenu -->
        <div>
            <!-- Éditeur -->
            <section id="editeur" class="mb-5">
                <h2 class="fw-bold h4 mb-3">
                    <i class="fas fa-building text-primary"></i> Éditeur du site
                </h2>
                <div class="card border-0 shadow-sm p-4 rounded-3">
                    <p>
                        <strong>Raison sociale :</strong> Dorocho SAS<br>
                        <strong>Siège social :</strong> 123 Rue de la Paix, 75000 Paris, France<br>
                        <strong>SIRET :</strong> 123 456 789 00012<br>
                        <strong>Capital social :</strong> 10 000 €<br>
                        <strong>Directeur de publication :</strong> Jean Dupont<br>
                        <strong>Email :</strong> <a href="mailto:contact@dorocho.fr">contact@dorocho.fr</a><br>
                        <strong>Téléphone :</strong> +33 1 23 45 67 89
                    </p>
                </div>
            </section>

            <!-- Hébergeur -->
            <section id="hebergeur" class="mb-5">
                <h2 class="fw-bold h4 mb-3">
                    <i class="fas fa-server text-primary"></i> Hébergeur du site
                </h2>
                <div class="card border-0 shadow-sm p-4 rounded-3">
                    <p>
                        <strong>Nom :</strong> OVH SAS<br>
                        <strong>Adresse :</strong> 2 Rue Kellermann, 59100 Roubaix, France<br>
                        <strong>Email :</strong> support@ovh.com<br>
                        <strong>Téléphone :</strong> 1007
                    </p>
                </div>
            </section>

            <!-- Données personnelles -->
            <section id="donnees" class="mb-5">
                <h2 class="fw-bold h4 mb-3">
                    <i class="fas fa-lock text-primary"></i> Données personnelles
                </h2>
                <div class="card border-0 shadow-sm p-4 rounded-3">
                    <p>
                        Conformément à la Loi Informatique et Libertés du 6 janvier 1978 modifiée et au Règlement Général 
                        sur la Protection des Données (RGPD), vous disposez d'un droit d'accès, de rectification et de suppression 
                        de vos données personnelles.
                    </p>
                    <p>
                        <strong>Responsable de traitement :</strong> Dorocho SAS<br>
                        <strong>DPO :</strong> <a href="mailto:dpo@dorocho.fr">dpo@dorocho.fr</a>
                    </p>
                </div>
            </section>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
