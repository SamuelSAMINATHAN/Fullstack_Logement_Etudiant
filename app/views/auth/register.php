<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <!-- Titre -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary mb-2">
                        <i class="fas fa-user-plus"></i> Créer un compte
                    </h2>
                    <p class="text-muted">Rejoignez la communauté Dorocho</p>
                </div>

                <!-- Sélection du type d'utilisateur -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="form-check p-3 border-2 rounded text-center cursor-pointer role-card" style="cursor: pointer;">
                            <input class="form-check-input d-none" type="radio" name="user_type" id="type_etudiant" value="etudiant" checked>
                            <label class="form-check-label w-100" for="type_etudiant">
                                <i class="fas fa-graduation-cap" style="font-size: 2rem; color: #2563eb;"></i>
                                <h6 class="mt-2">Étudiant</h6>
                                <small class="text-muted">Cherchez un logement</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check p-3 border-2 rounded text-center cursor-pointer role-card" style="cursor: pointer;">
                            <input class="form-check-input d-none" type="radio" name="user_type" id="type_bailleur" value="bailleur">
                            <label class="form-check-label w-100" for="type_bailleur">
                                <i class="fas fa-key" style="font-size: 2rem; color: #10b981;"></i>
                                <h6 class="mt-2">Propriétaire</h6>
                                <small class="text-muted">Louez votre logement</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check p-3 border-2 rounded text-center cursor-pointer role-card" style="cursor: pointer;">
                            <input class="form-check-input d-none" type="radio" name="user_type" id="type_admin" value="admin">
                            <label class="form-check-label w-100" for="type_admin">
                                <i class="fas fa-shield-alt" style="font-size: 2rem; color: #ef4444;"></i>
                                <h6 class="mt-2">Administrateur</h6>
                                <small class="text-muted">Code requis</small>
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Formulaire -->
                <form method="POST" action="<?php echo URLROOT; ?>/auth/register" novalidate enctype="multipart/form-data">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    <input type="hidden" name="user_type" id="user_type_hidden" value="etudiant">

                    <!-- Informations personnelles -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label fw-500">
                                <i class="fas fa-user"></i> Prénom
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg <?php echo (!empty($errors['prenom']) ? 'is-invalid' : ''); ?>"
                                id="prenom" 
                                name="prenom" 
                                placeholder="Jean"
                                value="<?php echo Security::escape($prenom ?? ''); ?>"
                                required
                            >
                            <?php if (!empty($errors['prenom'])): ?>
                                <div class="invalid-feedback"><?php echo Security::escape($errors['prenom']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label for="nom" class="form-label fw-500">
                                <i class="fas fa-user"></i> Nom
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg <?php echo (!empty($errors['nom']) ? 'is-invalid' : ''); ?>"
                                id="nom" 
                                name="nom" 
                                placeholder="Dupont"
                                value="<?php echo Security::escape($nom ?? ''); ?>"
                                required
                            >
                            <?php if (!empty($errors['nom'])): ?>
                                <div class="invalid-feedback"><?php echo Security::escape($errors['nom']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

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
                            placeholder="jean.dupont@exemple.com"
                            value="<?php echo Security::escape($email ?? ''); ?>"
                            required
                        >
                        <?php if (!empty($errors['email'])): ?>
                            <div class="invalid-feedback"><?php echo Security::escape($errors['email']); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Téléphone -->
                    <div class="mb-3">
                        <label for="telephone" class="form-label fw-500">
                            <i class="fas fa-phone"></i> Téléphone
                        </label>
                        <input 
                            type="tel" 
                            class="form-control form-control-lg"
                            id="telephone" 
                            name="telephone" 
                            placeholder="+33 6 12 34 56 78"
                            value="<?php echo Security::escape($telephone ?? ''); ?>"
                        >
                    </div>

                    <!-- Mot de passe -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-500">
                                <i class="fas fa-lock"></i> Mot de passe
                            </label>
                            <input 
                                type="password" 
                                class="form-control form-control-lg <?php echo (!empty($errors['password']) ? 'is-invalid' : ''); ?>"
                                id="password" 
                                name="password" 
                                placeholder="Minimum 8 caractères"
                                required
                            >
                            <?php if (!empty($errors['password'])): ?>
                                <div class="invalid-feedback"><?php echo Security::escape($errors['password']); ?></div>
                            <?php endif; ?>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i> 
                                Au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirm" class="form-label fw-500">
                                <i class="fas fa-lock"></i> Confirmer le mot de passe
                            </label>
                            <input 
                                type="password" 
                                class="form-control form-control-lg <?php echo (!empty($errors['password_confirm']) ? 'is-invalid' : ''); ?>"
                                id="password_confirm" 
                                name="password_confirm" 
                                placeholder="Répétez votre mot de passe"
                                required
                            >
                            <?php if (!empty($errors['password_confirm'])): ?>
                                <div class="invalid-feedback"><?php echo Security::escape($errors['password_confirm']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="mb-4 form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input <?php echo (!empty($errors['conditions']) ? 'is-invalid' : ''); ?>"
                            id="conditions" 
                            name="conditions"
                            required
                        >
                        <label class="form-check-label" for="conditions">
                            J'accepte les 
                            <a href="<?php echo URLROOT; ?>/page/cgu" target="_blank" class="text-decoration-none">
                                conditions générales d'utilisation
                            </a> 
                            et la 
                            <a href="<?php echo URLROOT; ?>/page/mentions-legales" target="_blank" class="text-decoration-none">
                                politique de confidentialité
                            </a>
                        </label>
                        <?php if (!empty($errors['conditions'])): ?>
                            <div class="invalid-feedback d-block"><?php echo Security::escape($errors['conditions']); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Vérification reCAPTCHA -->
                    <div class="mb-4 alert alert-light border">
                        <p class="mb-0 text-muted text-center small">
                            <i class="fas fa-shield-alt"></i> Site protégé par reCAPTCHA
                        </p>
                    </div>

                    <!-- Bouton d'inscription -->
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                        <i class="fas fa-check"></i> Créer mon compte
                    </button>

                    <!-- Connexion -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Vous avez déjà un compte ?
                            <a href="<?php echo URLROOT; ?>/auth/login" class="text-decoration-none fw-bold">
                                Se connecter
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="alert alert-info border-0 rounded text-center">
                    <i class="fas fa-lock" style="font-size: 1.5rem;"></i>
                    <h6 class="mt-2 mb-1">Sécurisé</h6>
                    <small class="text-muted">Données protégées par chiffrement</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success border-0 rounded text-center">
                    <i class="fas fa-clock" style="font-size: 1.5rem;"></i>
                    <h6 class="mt-2 mb-1">Rapide</h6>
                    <small class="text-muted">Inscription en moins d'une minute</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-warning border-0 rounded text-center">
                    <i class="fas fa-check" style="font-size: 1.5rem;"></i>
                    <h6 class="mt-2 mb-1">Gratuit</h6>
                    <small class="text-muted">Aucune carte bancaire requise</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles et scripts -->
<style>
    .role-card {
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .role-card:hover {
        border-color: #2563eb;
        background-color: #f0f9ff;
    }

    .role-card input[type="radio"]:checked + label {
        color: #2563eb;
    }

    .role-card input[type="radio"]:checked ~ * {
        border-color: #2563eb;
    }
</style>

<script>
    // Mettre à jour le champ caché quand le type d'utilisateur change
    document.querySelectorAll('input[name="user_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('user_type_hidden').value = this.value;
        });
    });

    // Ajouter un effet visuel aux cartes de rôle
    document.querySelectorAll('.role-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            this.classList.add('active');
        });
    });
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
