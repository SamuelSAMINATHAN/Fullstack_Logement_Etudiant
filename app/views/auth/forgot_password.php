<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Mot de passe oublié</h2>
                <p class="text-center text-muted mb-4">Entrez votre adresse email ci-dessous et nous vous enverrons les instructions pour réinitialiser votre mot de passe.</p>
                
                <form action="<?php echo URLROOT; ?>/password/forgotHandler" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Envoyer le lien de réinitialisation</button>
                </form>
                
                <div class="text-center mt-4">
                    <a href="<?php echo URLROOT; ?>/auth/login" class="text-decoration-none"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
