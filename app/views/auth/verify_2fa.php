<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-5">
            <div class="card-header bg-white p-4">
                <h3 class="mb-0 text-center">Double Authentification</h3>
            </div>
            <div class="card-body p-4">
                <p class="text-center text-muted">Veuillez saisir le code à 6 chiffres généré par votre application d'authentification.</p>
                
                <form action="<?php echo URLROOT; ?>/auth/verify2FAHandler" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="mb-4">
                        <input type="text" name="code" class="form-control form-control-lg text-center" 
                               placeholder="000000" maxlength="6" pattern="\d{6}" required autofocus 
                               style="letter-spacing: 5px; font-weight: bold; font-size: 1.5rem;">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Se connecter</button>
                        <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-link text-muted">Retour à la connexion</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
