<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-primary mb-2">
                <i class="fas fa-envelope"></i> Nous contacter
            </h1>
            <p class="text-muted lead">
                Vous avez une question ? Notre équipe est là pour vous aider.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Email -->
            <div class="col-md-4">
                <div class="card border-0 text-center rounded-4 shadow-sm h-100 p-4">
                    <div class="mb-3">
                        <span class="badge bg-primary p-3" style="font-size: 1.5rem;">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                    <h6 class="fw-bold">Email</h6>
                    <p class="text-muted mb-0">
                        <a href="mailto:support@dorocho.fr" class="text-decoration-none">
                            support@dorocho.fr
                        </a>
                    </p>
                </div>
            </div>

            <!-- Téléphone -->
            <div class="col-md-4">
                <div class="card border-0 text-center rounded-4 shadow-sm h-100 p-4">
                    <div class="mb-3">
                        <span class="badge bg-success p-3" style="font-size: 1.5rem;">
                            <i class="fas fa-phone"></i>
                        </span>
                    </div>
                    <h6 class="fw-bold">Téléphone</h6>
                    <p class="text-muted mb-0">
                        <a href="tel:+33123456789" class="text-decoration-none">
                            +33 1 23 45 67 89
                        </a>
                    </p>
                </div>
            </div>

            <!-- Chat -->
            <div class="col-md-4">
                <div class="card border-0 text-center rounded-4 shadow-sm h-100 p-4">
                    <div class="mb-3">
                        <span class="badge bg-info p-3" style="font-size: 1.5rem;">
                            <i class="fas fa-comments"></i>
                        </span>
                    </div>
                    <h6 class="fw-bold">Chat</h6>
                    <p class="text-muted mb-0">
                        <button class="btn btn-sm btn-link text-decoration-none p-0" onclick="alert('Chat en direct non disponible')">
                            Ouvrir le chat
                        </button>
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulaire de contact -->
        <div class="card border-0 shadow-sm rounded-4 p-5">
            <h5 class="fw-bold mb-4">Envoyez-nous un message</h5>
            
            <form method="POST" action="<?php echo URLROOT; ?>/page/contact" novalidate>
                <!-- Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">

                <!-- Nom -->
                <div class="mb-3">
                    <label for="nom" class="form-label fw-500">Votre nom</label>
                    <input 
                        type="text" 
                        class="form-control form-control-lg <?php echo (!empty($errors['nom']) ? 'is-invalid' : ''); ?>"
                        id="nom" 
                        name="nom"
                        placeholder="Jean Dupont"
                        value="<?php echo Security::escape($nom ?? ''); ?>"
                        required
                    >
                    <?php if (!empty($errors['nom'])): ?>
                        <div class="invalid-feedback"><?php echo Security::escape($errors['nom']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-500">Adresse Email</label>
                    <input 
                        type="email" 
                        class="form-control form-control-lg <?php echo (!empty($errors['email']) ? 'is-invalid' : ''); ?>"
                        id="email" 
                        name="email"
                        placeholder="jean@exemple.com"
                        value="<?php echo Security::escape($email ?? ''); ?>"
                        required
                    >
                    <?php if (!empty($errors['email'])): ?>
                        <div class="invalid-feedback"><?php echo Security::escape($errors['email']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Sujet -->
                <div class="mb-3">
                    <label for="sujet" class="form-label fw-500">Sujet</label>
                    <select 
                        class="form-select form-select-lg <?php echo (!empty($errors['sujet']) ? 'is-invalid' : ''); ?>"
                        id="sujet" 
                        name="sujet"
                        required
                    >
                        <option value="">-- Sélectionnez un sujet --</option>
                        <option value="bug" <?php echo (isset($sujet) && $sujet === 'bug' ? 'selected' : ''); ?>>Signaler un bug</option>
                        <option value="suggestion" <?php echo (isset($sujet) && $sujet === 'suggestion' ? 'selected' : ''); ?>>Suggestion d'amélioration</option>
                        <option value="probleme_annonce" <?php echo (isset($sujet) && $sujet === 'probleme_annonce' ? 'selected' : ''); ?>>Problème avec une annonce</option>
                        <option value="compte" <?php echo (isset($sujet) && $sujet === 'compte' ? 'selected' : ''); ?>>Problème de compte</option>
                        <option value="autre" <?php echo (isset($sujet) && $sujet === 'autre' ? 'selected' : ''); ?>>Autre</option>
                    </select>
                    <?php if (!empty($errors['sujet'])): ?>
                        <div class="invalid-feedback"><?php echo Security::escape($errors['sujet']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Message -->
                <div class="mb-4">
                    <label for="message" class="form-label fw-500">Votre message</label>
                    <textarea 
                        class="form-control form-control-lg <?php echo (!empty($errors['message']) ? 'is-invalid' : ''); ?>"
                        id="message" 
                        name="message"
                        rows="6"
                        placeholder="Décrivez votre demande en détail..."
                        required
                    ><?php echo Security::escape($message ?? ''); ?></textarea>
                    <small class="text-muted">Maximum 2000 caractères</small>
                    <?php if (!empty($errors['message'])): ?>
                        <div class="invalid-feedback d-block"><?php echo Security::escape($errors['message']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Checkbox conditions -->
                <div class="mb-4 form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input"
                        id="conditions" 
                        name="conditions"
                        required
                    >
                    <label class="form-check-label" for="conditions">
                        J'accepte que Dorocho utilise mon message pour améliorer le service
                    </label>
                </div>

                <!-- Boutons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                    <a href="<?php echo URLROOT; ?>/" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Retour à l'accueil
                    </a>
                </div>
            </form>
        </div>

        <!-- Horaires de support -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-clock"></i> Horaires
                        </h6>
                        <ul class="list-unstyled text-muted small">
                            <li class="mb-2">Lundi - Vendredi : 09:00 - 18:00</li>
                            <li class="mb-2">Samedi : 10:00 - 16:00</li>
                            <li>Dimanche : Fermé</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-lightning-bolt"></i> Temps de réponse
                        </h6>
                        <ul class="list-unstyled text-muted small">
                            <li class="mb-2"><strong>Urgent :</strong> 30 minutes</li>
                            <li class="mb-2"><strong>Normal :</strong> 24 heures</li>
                            <li><strong>Non-urgent :</strong> 48-72 heures</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
