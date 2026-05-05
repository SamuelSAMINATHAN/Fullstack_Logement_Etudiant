<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <!-- Titre -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary mb-2">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </h2>
                    <p class="text-muted">Connectez-vous à votre compte Dorocho</p>
                </div>

                <!-- Formulaire de connexion -->
                <form method="POST" action="<?php echo URLROOT; ?>/auth/login" novalidate>
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-500">
                            <i class="fas fa-envelope"></i> Adresse Email
                        </label>
                        <input 
                            type="email" 
                            class="form-control form-control-lg <?php echo (!empty($errors['email']) ? 'is-invalid' : ''); ?>"
                            id="email" 
                            name="email" 
                            placeholder="votre.email@exemple.com"
                            value="<?php echo Security::escape($email ?? ''); ?>"
                            required
                        >
                        <?php if (!empty($errors['email'])): ?>
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i> <?php echo Security::escape($errors['email']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-500">
                            <i class="fas fa-lock"></i> Mot de passe
                        </label>
                        <input 
                            type="password" 
                            class="form-control form-control-lg <?php echo (!empty($errors['password']) ? 'is-invalid' : ''); ?>"
                            id="password" 
                            name="password" 
                            placeholder="Entrez votre mot de passe"
                            required
                        >
                        <?php if (!empty($errors['password'])): ?>
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i> <?php echo Security::escape($errors['password']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Mémoriser -->
                    <div class="mb-4 form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="remember" 
                            name="remember"
                        >
                        <label class="form-check-label" for="remember">
                            Me mémoriser sur cet appareil
                        </label>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>

                    <!-- Mot de passe oublié -->
                    <div class="text-center mb-3">
                        <a href="<?php echo URLROOT; ?>/password/forgot" class="text-decoration-none text-primary">
                            <small><i class="fas fa-key"></i> Mot de passe oublié ?</small>
                        </a>
                    </div>
                </form>

                <!-- Divider -->
                <hr class="my-4">

                <!-- Liens d'inscription -->
                <div class="alert alert-light border border-info rounded-3" role="alert">
                    <p class="mb-0 text-center">
                        <small>Pas encore de compte ?</small><br>
                        <a href="<?php echo URLROOT; ?>/auth/register" class="btn btn-outline-primary btn-sm mt-2 w-100">
                            <i class="fas fa-user-plus"></i> Créer un compte
                        </a>
                    </p>
                </div>

                <!-- Rôles disponibles -->
                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted text-center mb-3"><small>Vous êtes :</small></p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Vous cherchez un logement">
                            <i class="fas fa-graduation-cap"></i> Étudiant
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Vous proposez un logement">
                            <i class="fas fa-key"></i> Propriétaire
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Accès administrateur">
                            <i class="fas fa-lock"></i> Administrateur
                        </button>
                    </div>
                    <p class="text-muted text-center mt-3"><small>Sélectionnez votre profil pour les identifiants appropriés</small></p>
                </div>
            </div>
        </div>

        <!-- Informations de sécurité -->
        <div class="mt-4 alert alert-info border-0 rounded-3">
            <i class="fas fa-shield-alt"></i>
            <small><strong>Sécurité :</strong> Votre connexion est protégée par chiffrement SSL. Ne partagez jamais votre mot de passe.</small>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
