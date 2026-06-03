<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Créer un Administrateur</h1>
        <a href="<?= URLROOT ?>/admin/admins" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="<?= URLROOT ?>/admin/createAdminHandler" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Identifiant (Login)</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                            <div class="form-text">Cet identifiant servira pour la connexion de l'administrateur.</div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <div class="form-text">Au moins 8 caractères.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus-circle"></i> Créer le compte administrateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>