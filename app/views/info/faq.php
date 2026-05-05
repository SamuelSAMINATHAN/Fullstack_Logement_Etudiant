<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="fw-bold text-primary mb-1">
            <i class="fas fa-question-circle"></i> Foire Aux Questions
        </h1>
        <p class="text-muted">Trouvez les réponses à vos questions les plus fréquentes</p>
    </div>
    <div class="col-md-4">
        <input 
            type="text" 
            class="form-control"
            placeholder="Rechercher une question..."
            id="faqSearch"
        >
    </div>
</div>

<!-- Catégories de FAQ -->
<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="nav nav-pills flex-column" role="tablist">
            <button class="nav-link active fw-500 text-start" data-bs-toggle="pill" data-bs-target="#general" type="button">
                <i class="fas fa-info-circle me-2"></i> Général
            </button>
            <button class="nav-link fw-500 text-start" data-bs-toggle="pill" data-bs-target="#inscription" type="button">
                <i class="fas fa-user-plus me-2"></i> Inscription
            </button>
            <button class="nav-link fw-500 text-start" data-bs-toggle="pill" data-bs-target="#recherche" type="button">
                <i class="fas fa-search me-2"></i> Recherche
            </button>
            <button class="nav-link fw-500 text-start" data-bs-toggle="pill" data-bs-target="#contact" type="button">
                <i class="fas fa-envelope me-2"></i> Contact
            </button>
            <button class="nav-link fw-500 text-start" data-bs-toggle="pill" data-bs-target="#securite" type="button">
                <i class="fas fa-shield-alt me-2"></i> Sécurité
            </button>
            <button class="nav-link fw-500 text-start" data-bs-toggle="pill" data-bs-target="#paiement" type="button">
                <i class="fas fa-credit-card me-2"></i> Paiement
            </button>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="tab-content">
            <!-- Général -->
            <div class="tab-pane fade show active" id="general">
                <div class="accordion mb-4">
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Qu'est-ce que Dorocho ?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#general">
                            <div class="accordion-body">
                                Dorocho est une plateforme en ligne qui aide les étudiants à trouver des logements adaptés à leurs besoins. 
                                Elle connecte les étudiants avec des propriétaires de confiance et d'autres étudiants cherchant des colocataires.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Comment fonctionne Dorocho ?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#general">
                            <div class="accordion-body">
                                C'est simple : 1) Créez un compte, 2) Parcourez les annonces, 3) Contactez les propriétaires, 
                                4) Visitez les logements, 5) Signez le bail. Tout peut se faire directement sur notre plateforme !
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Dorocho est-il gratuit ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#general">
                            <div class="accordion-body">
                                Oui, Dorocho est 100% gratuit pour les étudiants. Les propriétaires paient une petite commission 
                                sur les annonces, mais vous n'avez rien à payer pour utiliser la plateforme.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inscription -->
            <div class="tab-pane fade" id="inscription">
                <div class="accordion mb-4">
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Comment m'inscrire sur Dorocho ?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse show" data-bs-parent="#inscription">
                            <div class="accordion-body">
                                C'est très facile ! Cliquez sur "S'inscrire", remplissez vos informations personnelles, vérifiez votre email, 
                                et voilà ! Vous pouvez commencer à chercher dès maintenant.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Quels documents dois-je préparer ?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#inscription">
                            <div class="accordion-body">
                                Généralement, vous aurez besoin de : une pièce d'identité valide, un justificatif de revenus 
                                (pour les étudiants, un relevé de bourse suffit), et parfois une lettre de recommandation.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                Puis-je changer mon type de profil ?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#inscription">
                            <div class="accordion-body">
                                Oui, vous pouvez créer plusieurs comptes ou modifier votre profil à tout moment dans les paramètres. 
                                Contactez-nous si vous avez besoin d'aide.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recherche -->
            <div class="tab-pane fade" id="recherche">
                <div class="accordion mb-4">
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                Comment rechercher un logement ?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse show" data-bs-parent="#recherche">
                            <div class="accordion-body">
                                Utilisez notre page "Annonces" avec les filtres : budget, localisation, type de logement, etc. 
                                Vous pouvez également créer des alertes pour être notifiés automatiquement de nouvelles annonces.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                Comment ajouter un logement aux favoris ?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#recherche">
                            <div class="accordion-body">
                                Cliquez sur le cœur <i class="fas fa-heart text-danger"></i> en haut à droite de chaque annonce. 
                                Vous pourrez retrouver tous vos favoris dans votre tableau de bord.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                                Puis-je créer des alertes ?
                            </button>
                        </h2>
                        <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#recherche">
                            <div class="accordion-body">
                                Absolument ! Créez une alerte avec vos critères de recherche et vous recevrez une notification 
                                chaque fois qu'une nouvelle annonce correspond à vos attentes.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="tab-pane fade" id="contact">
                <div class="accordion mb-4">
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                                Comment contacter un propriétaire ?
                            </button>
                        </h2>
                        <div id="faq10" class="accordion-collapse collapse show" data-bs-parent="#contact">
                            <div class="accordion-body">
                                Cliquez sur "Contacter" ou "Postuler" sur la page de l'annonce. Un message privé s'ouvrira 
                                et vous pourrez communiquer directement sans révéler votre email.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq11">
                                Puis-je appeler directement ?
                            </button>
                        </h2>
                        <div id="faq11" class="accordion-collapse collapse" data-bs-parent="#contact">
                            <div class="accordion-body">
                                Les propriétaires peuvent ajouter leur numéro de téléphone sur l'annonce. Nous recommandons d'utiliser 
                                d'abord la messagerie interne pour plus de sécurité.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12">
                                Comment signaler un utilisateur malveillant ?
                            </button>
                        </h2>
                        <div id="faq12" class="accordion-collapse collapse" data-bs-parent="#contact">
                            <div class="accordion-body">
                                Cliquez sur le bouton "Signaler" sur le profil ou l'annonce. Notre équipe étudiera le signalement 
                                et prendra les mesures appropriées.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="tab-pane fade" id="securite">
                <div class="accordion mb-4">
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq13">
                                Mes données sont-elles sécurisées ?
                            </button>
                        </h2>
                        <div id="faq13" class="accordion-collapse collapse show" data-bs-parent="#securite">
                            <div class="accordion-body">
                                Oui, nous utilisons le chiffrement SSL et respectons les normes de sécurité les plus strictes (RGPD). 
                                Vos données personnelles ne seront jamais vendues à des tiers.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq14">
                                Comment vérifier l'identité d'un propriétaire ?
                            </button>
                        </h2>
                        <div id="faq14" class="accordion-collapse collapse" data-bs-parent="#securite">
                            <div class="accordion-body">
                                Les propriétaires vérifiés affichent un badge <span class="badge bg-success">✓ Vérifié</span> sur leur profil. 
                                Évitez les transactions en espèces avant d'avoir signé un bail officiel.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq15">
                                Puis-je activer l'authentification à deux facteurs ?
                            </button>
                        </h2>
                        <div id="faq15" class="accordion-collapse collapse" data-bs-parent="#securite">
                            <div class="accordion-body">
                                Oui ! Allez dans vos paramètres de sécurité et activez l'authentification à deux facteurs 
                                pour protéger votre compte.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paiement -->
            <div class="tab-pane fade" id="paiement">
                <div class="accordion mb-4">
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq16">
                                Puis-je payer directement sur Dorocho ?
                            </button>
                        </h2>
                        <div id="faq16" class="accordion-collapse collapse show" data-bs-parent="#paiement">
                            <div class="accordion-body">
                                Dorocho est une plateforme de mise en relation. Les paiements du loyer se font directement 
                                entre vous et le propriétaire, généralement par virement bancaire.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq17">
                                Y a-t-il des frais supplémentaires ?
                            </button>
                        </h2>
                        <div id="faq17" class="accordion-collapse collapse" data-bs-parent="#paiement">
                            <div class="accordion-body">
                                Non, Dorocho est gratuit pour les étudiants. Aucune frais caché, aucune commission prélevée sur le loyer.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq18">
                                Que faire en cas de litige de paiement ?
                            </button>
                        </h2>
                        <div id="faq18" class="accordion-collapse collapse" data-bs-parent="#paiement">
                            <div class="accordion-body">
                                Contactez-nous immédiatement via notre formulaire de contact. Notre équipe mediateur pourra vous aider 
                                à résoudre le conflit.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact support -->
<div class="row mt-5">
    <div class="col-12">
        <div class="alert alert-light border rounded-4 p-4 text-center">
            <h5 class="fw-bold mb-2">Vous n'avez pas trouvé de réponse ?</h5>
            <p class="text-muted mb-3">Notre équipe support est disponible 24h/24, 7j/7 pour vous aider.</p>
            <a href="<?php echo URLROOT; ?>/page/contact" class="btn btn-primary btn-lg">
                <i class="fas fa-envelope"></i> Nous contacter
            </a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
