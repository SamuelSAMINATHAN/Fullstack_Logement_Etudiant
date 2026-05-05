<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <h1 class="fw-bold text-primary mb-4">Mentions Légales</h1>

        <!-- Navigation -->
        <div class="alert alert-light border rounded mb-4">
            <strong>Sommaire :</strong>
            <ul class="mb-0">
                <li><a href="#editeur" class="text-decoration-none">Éditeur</a></li>
                <li><a href="#hebergeur" class="text-decoration-none">Hébergeur</a></li>
                <li><a href="#donnees" class="text-decoration-none">Données personnelles</a></li>
                <li><a href="#droits" class="text-decoration-none">Droits d'auteur</a></li>
                <li><a href="#disclaimer" class="text-decoration-none">Disclaimer</a></li>
            </ul>
        </div>

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
                        <strong>DPO (Délégué à la Protection des Données) :</strong> 
                        <a href="mailto:dpo@dorocho.fr">dpo@dorocho.fr</a>
                    </p>
                    <p class="mb-0">
                        Pour exercer vos droits, veuillez nous contacter par email à l'adresse ci-dessus avec une copie de votre 
                        pièce d'identité.
                    </p>
                </div>
            </section>

            <!-- Droits d'auteur -->
            <section id="droits" class="mb-5">
                <h2 class="fw-bold h4 mb-3">
                    <i class="fas fa-copyright text-primary"></i> Droits d'auteur
                </h2>
                <div class="card border-0 shadow-sm p-4 rounded-3">
                    <p>
                        Le contenu et la structure du site Dorocho sont protégés par les droits d'auteur. 
                        Toute reproduction, même partielle, est interdite sans l'autorisation préalable de Dorocho SAS.
                    </p>
                    <p class="mb-0">
                        Les marques, logos et éléments graphiques présents sur le site sont des propriétés de Dorocho SAS 
                        ou de ses partenaires.
                    </p>
                </div>
            </section>

            <!-- Disclaimer -->
            <section id="disclaimer" class="mb-5">
                <h2 class="fw-bold h4 mb-3">
                    <i class="fas fa-exclamation-triangle text-primary"></i> Limitation de responsabilité
                </h2>
                <div class="card border-0 shadow-sm p-4 rounded-3">
                    <p>
                        Dorocho s'efforce de fournir des informations fiables et mises à jour, mais ne peut garantir 
                        l'exactitude, la complétude ou la pertinence des informations présentes sur le site.
                    </p>
                    <p>
                        Dorocho ne peut être tenu responsable des dommages directs ou indirects résultant de l'utilisation 
                        ou de l'impossibilité d'utilisation du site, y compris mais sans limitation :
                    </p>
                    <ul>
                        <li>Les pertes d'exploitation ou de revenus</li>
                        <li>Les pertes de données</li>
                        <li>Les dommages aux équipements informatiques</li>
                        <li>Tout autre préjudice pécuniaire</li>
                    </ul>
                    <p class="mb-0">
                        Dorocho décline également toute responsabilité quant aux contenus des liens externes présents sur le site.
                    </p>
                </div>
            </section>

            <!-- Conditions d'utilisation -->
            <section class="mb-5">
                <h2 class="fw-bold h4 mb-3">
                    <i class="fas fa-file-contract text-primary"></i> Conditions d'utilisation
                </h2>
                <div class="card border-0 shadow-sm p-4 rounded-3">
                    <p>
                        L'accès et l'utilisation de ce site impliquent l'acceptation de nos 
                        <a href="<?php echo URLROOT; ?>/page/cgu" class="text-decoration-none">Conditions Générales d'Utilisation</a>.
                    </p>
                </div>
            </section>
        </div>

        <!-- Contact -->
        <div class="alert alert-info border-0 rounded-4" role="alert">
            <h6 class="fw-bold mb-2">Pour toute question :</h6>
            <p class="mb-0">
                Contactez-nous à <a href="mailto:legal@dorocho.fr">legal@dorocho.fr</a> ou 
                <a href="<?php echo URLROOT; ?>/page/contact">via notre formulaire de contact</a>
            </p>
        </div>

        <!-- Dernière mise à jour -->
        <p class="text-muted text-center mt-4">
            <small>Dernière mise à jour : <?php echo date('d/m/Y'); ?></small>
        </p>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
