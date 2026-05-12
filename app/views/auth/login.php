<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Connexion</h2>
                
                <form action="<?php echo URLROOT; ?>/auth/loginHandler" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus value="<?php echo isset($email) ? Security::escape($email) : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>
                        <a href="<?php echo URLROOT; ?>/password/forgot" class="text-decoration-none small">Mot de passe oublié ?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Pas encore de compte ? <a href="<?php echo URLROOT; ?>/auth/register" class="text-decoration-none fw-bold">S'inscrire</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
