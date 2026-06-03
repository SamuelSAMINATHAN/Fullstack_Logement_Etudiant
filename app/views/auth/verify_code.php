<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Vérification du code</h2>
                <p class="text-center text-muted mb-4">Veuillez saisir le code à 6 chiffres envoyé à votre adresse email.</p>
                
                <form action="<?php echo URLROOT; ?>/password/verifyHandler" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    
                    <div class="mb-4">
                        <label for="code" class="form-label text-center d-block">Code de vérification</label>
                        <input type="text" class="form-control form-control-lg text-center fw-bold" 
                               id="code" name="code" placeholder="000000" maxlength="6" 
                               pattern="\d{6}" required autofocus style="letter-spacing: 10px; font-size: 2rem;">
                        <div class="form-text text-center mt-2">Le code est composé de 6 chiffres.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 btn-lg">Vérifier le code</button>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0 text-muted small">Vous n'avez pas reçu le code ?</p>
                        <a href="<?php echo URLROOT; ?>/password/forgot" class="text-decoration-none">Renvoyer une demande</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
