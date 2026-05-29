

<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Créer un compte</h2>

                <form action="<?php echo URLROOT; ?>/auth/registerHandler" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required value="<?php echo isset($prenom) ? Security::escape($prenom) : ''; ?>">
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required value="<?php echo isset($nom) ? Security::escape($nom) : ''; ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($email) ? Security::escape($email) : ''; ?>">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Type de profil</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_etudiant" value="etudiant" <?php echo (!isset($role) || $role === 'etudiant') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="role_etudiant">Étudiant</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_bailleur" value="bailleur" <?php echo (isset($role) && $role === 'bailleur') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="role_bailleur">Propriétaire / Bailleur</label>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="cgu" name="cgu" required>
                        <label class="form-check-label small" for="cgu">J'accepte les <a href="<?php echo URLROOT; ?>/page/cgu" target="_blank">Conditions Générales d'Utilisation</a> et la politique de confidentialité.</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">Déjà un compte ? <a href="<?php echo URLROOT; ?>/auth/login" class="text-decoration-none fw-bold">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>

