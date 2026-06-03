<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Réinitialiser le mot de passe</h2>
                <p class="text-center text-muted mb-4">Veuillez choisir votre nouveau mot de passe.</p>
                
                <form action="<?php echo URLROOT; ?>/password/resetHandler" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8" autofocus>
                        <div class="form-text">Au moins 8 caractères.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="8">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Mettre à jour le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
