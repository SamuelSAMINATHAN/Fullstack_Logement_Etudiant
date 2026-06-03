<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm mt-5">
            <div class="card-header bg-white p-4">
                <h3 class="mb-0 text-center">Configurer la Double Authentification</h3>
            </div>
            <div class="card-body p-4">
                <p>1. Téléchargez une application d'authentification (Google Authenticator, Microsoft Authenticator, Authy...) sur votre téléphone.</p>
                <p>2. Scannez le QR Code ci-dessous avec l'application :</p>
                
                <div class="text-center my-4">
                    <img src="<?php echo $qrCode; ?>" alt="QR Code 2FA" class="img-fluid border p-2 bg-white">
                </div>

                <div class="alert alert-info">
                    <small>Si vous ne pouvez pas scanner le QR Code, voici la clé secrète à saisir manuellement : <strong><?php echo $secret; ?></strong></small>
                </div>

                <hr class="my-4">

                <p>3. Saisissez le code à 6 chiffres généré par l'application pour confirmer l'activation :</p>
                
                <form action="<?php echo URLROOT; ?>/profil/verify2FASetup" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-4">
                        <input type="text" name="code" class="form-control form-control-lg text-center" 
                               placeholder="000000" maxlength="6" pattern="\d{6}" required autofocus 
                               style="letter-spacing: 5px; font-weight: bold; font-size: 1.5rem;">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Activer la Double Authentification</button>
                        <a href="<?php echo URLROOT; ?>/profil/profile" class="btn btn-link text-muted">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
