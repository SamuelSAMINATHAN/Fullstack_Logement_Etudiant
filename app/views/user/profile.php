<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row">
    <!-- Sidebar navigation -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-primary">
                    <i class="fas fa-cog"></i> Paramètres
                </h5>
                <nav class="nav flex-column">
                    <a href="#informations" class="nav-link text-dark ps-0 py-2 active" data-bs-toggle="tab">
                        <i class="fas fa-user-circle"></i> Informations personnelles
                    </a>
                    <a href="#securite" class="nav-link text-dark ps-0 py-2" data-bs-toggle="tab">
                        <i class="fas fa-shield-alt"></i> Sécurité
                    </a>
                    <a href="#preferences" class="nav-link text-dark ps-0 py-2" data-bs-toggle="tab">
                        <i class="fas fa-sliders-h"></i> Préférences
                    </a>
                    <a href="#notifications" class="nav-link text-dark ps-0 py-2" data-bs-toggle="tab">
                        <i class="fas fa-bell"></i> Notifications
                    </a>
                    <hr>
                    <a href="<?php echo URLROOT; ?>/auth/logout" class="nav-link text-danger ps-0 py-2">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-9">
        <div class="tab-content">
            <!-- Onglet Informations personnelles -->
            <div class="tab-pane fade show active" id="informations">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-user-circle text-primary"></i> Informations personnelles
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo URLROOT; ?>/profil/updateProfile" novalidate enctype="multipart/form-data">
                            <!-- Token CSRF -->
                            <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">

                            <div class="row mb-4">
                                <!-- Photo de profil -->
                                <div class="col-md-3 text-center mb-3">
                                    <div class="position-relative d-inline-block">
                                        <img 
                                            id="profileImagePreview"
                                            src="<?php echo Security::escape($user['photo_url'] ?? '/img/default-avatar.jpg'); ?>" 
                                            alt="Photo de profil"
                                            class="rounded-circle"
                                            style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #2563eb;"
                                        >
                                        <label class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 m-0" style="cursor: pointer;">
                                            <i class="fas fa-camera"></i>
                                            <input 
                                                type="file" 
                                                id="profilePhoto" 
                                                name="photo" 
                                                class="d-none" 
                                                accept="image/*"
                                                onchange="previewImage(this)"
                                            >
                                        </label>
                                    </div>
                                    <p class="text-muted mt-2"><small>Cliquez pour changer</small></p>
                                </div>

                                <!-- Formulaire -->
                                <div class="col-md-9">
                                    <!-- Prénom et Nom -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="prenom" class="form-label fw-500">Prénom</label>
                                            <input 
                                                type="text" 
                                                class="form-control <?php echo (!empty($errors['prenom']) ? 'is-invalid' : ''); ?>"
                                                id="prenom" 
                                                name="prenom" 
                                                value="<?php echo Security::escape($user['prenom'] ?? ''); ?>"
                                                required
                                            >
                                            <?php if (!empty($errors['prenom'])): ?>
                                                <div class="invalid-feedback"><?php echo Security::escape($errors['prenom']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nom" class="form-label fw-500">Nom</label>
                                            <input 
                                                type="text" 
                                                class="form-control <?php echo (!empty($errors['nom']) ? 'is-invalid' : ''); ?>"
                                                id="nom" 
                                                name="nom" 
                                                value="<?php echo Security::escape($user['nom'] ?? ''); ?>"
                                                required
                                            >
                                            <?php if (!empty($errors['nom'])): ?>
                                                <div class="invalid-feedback"><?php echo Security::escape($errors['nom']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-500">Adresse Email</label>
                                        <input 
                                            type="email" 
                                            class="form-control <?php echo (!empty($errors['email']) ? 'is-invalid' : ''); ?>"
                                            id="email" 
                                            name="email" 
                                            value="<?php echo Security::escape($user['email'] ?? ''); ?>"
                                            required
                                        >
                                        <?php if (!empty($errors['email'])): ?>
                                            <div class="invalid-feedback"><?php echo Security::escape($errors['email']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label fw-500">Numéro de téléphone</label>
                                        <input 
                                            type="tel" 
                                            class="form-control"
                                            id="telephone" 
                                            name="telephone" 
                                            value="<?php echo Security::escape($user['telephone'] ?? ''); ?>"
                                            placeholder="+33 6 12 34 56 78"
                                        >
                                    </div>

                                    <!-- Adresse -->
                                    <div class="mb-3">
                                        <label for="adresse" class="form-label fw-500">Adresse</label>
                                        <input 
                                            type="text" 
                                            class="form-control"
                                            id="adresse" 
                                            name="adresse" 
                                            value="<?php echo Security::escape($user['adresse'] ?? ''); ?>"
                                            placeholder="123 Rue de la Paix"
                                        >
                                    </div>

                                    <!-- Ville et Code postal -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="ville" class="form-label fw-500">Ville</label>
                                            <input 
                                                type="text" 
                                                class="form-control"
                                                id="ville" 
                                                name="ville" 
                                                value="<?php echo Security::escape($user['ville'] ?? ''); ?>"
                                            >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="code_postal" class="form-label fw-500">Code postal</label>
                                            <input 
                                                type="text" 
                                                class="form-control"
                                                id="code_postal" 
                                                name="code_postal" 
                                                value="<?php echo Security::escape($user['code_postal'] ?? ''); ?>"
                                            >
                                        </div>
                                    </div>

                                    <!-- Biographie -->
                                    <div class="mb-3">
                                        <label for="biographie" class="form-label fw-500">À propos de vous</label>
                                        <textarea 
                                            class="form-control" 
                                            id="biographie" 
                                            name="biographie" 
                                            rows="3"
                                            placeholder="Parlez un peu de vous..."
                                        ><?php echo Security::escape($user['biographie'] ?? ''); ?></textarea>
                                        <small class="text-muted">Maximum 500 caractères</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary fw-bold">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                                <button type="reset" class="btn btn-outline-secondary fw-bold">
                                    <i class="fas fa-redo"></i> Réinitialiser
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Onglet Sécurité -->
            <div class="tab-pane fade" id="securite">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-shield-alt text-primary"></i> Sécurité
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Changer le mot de passe -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3">Changer votre mot de passe</h6>
                            <form method="POST" action="<?php echo URLROOT; ?>/profil/changePassword" novalidate>
                                <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input 
                                        type="password" 
                                        class="form-control"
                                        id="current_password" 
                                        name="current_password" 
                                        required
                                    >
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                    <input 
                                        type="password" 
                                        class="form-control"
                                        id="new_password" 
                                        name="new_password" 
                                        required
                                    >
                                    <small class="text-muted">Au moins 8 caractères, 1 majuscule, 1 chiffre</small>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input 
                                        type="password" 
                                        class="form-control"
                                        id="confirm_password" 
                                        name="confirm_password" 
                                        required
                                    >
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-lock"></i> Mettre à jour le mot de passe
                                </button>
                            </form>
                        </div>

                        <hr>

                        <!-- Authentification 2FA -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3">Authentification à deux facteurs</h6>
                            <div class="alert alert-info border-0 mb-3">
                                <i class="fas fa-info-circle"></i> Renforcez la sécurité de votre compte
                            </div>
                            <?php if (isset($user['totp_secret']) && !empty($user['totp_secret'])): ?>
                                <p class="text-success mb-2">
                                    <i class="fas fa-check-circle"></i> <strong>Activée</strong>
                                </p>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-times"></i> Désactiver 2FA
                                </button>
                            <?php else: ?>
                                <p class="text-muted mb-2">Non configurée</p>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-mobile-alt"></i> Configurer 2FA
                                </button>
                            <?php endif; ?>
                        </div>

                        <hr>

                        <!-- Sessions actives -->
                        <div>
                            <h6 class="fw-bold mb-3">Sessions actives</h6>
                            <p class="text-muted mb-3">Appareil actuel</p>
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <i class="fas fa-laptop"></i> Votre appareil
                                    <small class="text-muted d-block">Actif maintenant</small>
                                </div>
                                <span class="badge bg-success">Actif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Préférences -->
            <div class="tab-pane fade" id="preferences">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-sliders-h text-primary"></i> Préférences
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo URLROOT; ?>/profil/updatePreferences" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">

                            <!-- Visibilité du profil -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Visibilité</h6>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="public_profile" 
                                        name="public_profile"
                                        <?php echo (!empty($user['profil_public']) ? 'checked' : ''); ?>
                                    >
                                    <label class="form-check-label" for="public_profile">
                                        Rendre mon profil public
                                    </label>
                                </div>
                            </div>

                            <!-- Préférences de logement -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Préférences de logement</h6>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="colocation" 
                                        name="prefer_colocation"
                                    >
                                    <label class="form-check-label" for="colocation">
                                        Intéressé par la colocation
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="meuble" 
                                        name="prefer_meuble"
                                    >
                                    <label class="form-check-label" for="meuble">
                                        Logement meublé préféré
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Onglet Notifications -->
            <div class="tab-pane fade" id="notifications">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom-0 p-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-bell text-primary"></i> Notifications
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo URLROOT; ?>/profil/updateNotifications" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="notify_messages" 
                                        name="notify_messages"
                                        checked
                                    >
                                    <label class="form-check-label" for="notify_messages">
                                        M'avertir des nouveaux messages
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="notify_annonces" 
                                        name="notify_annonces"
                                        checked
                                    >
                                    <label class="form-check-label" for="notify_annonces">
                                        M'avertir des nouvelles annonces correspondant à mes alertes
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="notify_candidatures" 
                                        name="notify_candidatures"
                                        checked
                                    >
                                    <label class="form-check-label" for="notify_candidatures">
                                        M'avertir des nouvelles candidatures
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Initialiser les tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
