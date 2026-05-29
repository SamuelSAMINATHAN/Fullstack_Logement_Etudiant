<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h2 class="mb-4">Choisissez votre profil</h2>
            <div class="card shadow-sm p-4">
                <p class="text-muted">Quel type de compte souhaitez-vous créer ?</p>
                <div class="d-grid gap-3">
                    <a href="<?php echo URLROOT; ?>/auth/register/etudiant" class="btn btn-primary btn-lg">
                        Je suis un Étudiant
                    </a>
                    <a href="<?php echo URLROOT; ?>/auth/register/bailleur" class="btn btn-success btn-lg">
                        Je suis un Bailleur
                    </a>
                </div>
                <div class="mt-4">
                    <p>Déjà un compte ? <a href="<?php echo URLROOT; ?>/auth/login">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/header.php'; ?>