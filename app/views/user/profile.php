<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <!-- Menu latéral -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <a href="<?php echo URLROOT; ?>/profil/dashboard" class="list-group-item list-group-item-action">
                <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
            </a>
            <a href="<?php echo URLROOT; ?>/profil/profile" class="list-group-item list-group-item-action active">
                <i class="fas fa-user me-2"></i> Mon Profil
            </a>
            <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                <a href="<?php echo URLROOT; ?>/favoris" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-2"></i> Mes Favoris
                </a>
                <a href="<?php echo URLROOT; ?>/alerte" class="list-group-item list-group-item-action">
                    <i class="fas fa-bell me-2"></i> Mes Alertes
                </a>
            <?php elseif ($_SESSION['user_role'] === 'bailleur'): ?>
                <a href="<?php echo URLROOT; ?>/annonce/mes-annonces" class="list-group-item list-group-item-action">
                    <i class="fas fa-building me-2"></i> Mes Annonces
                </a>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>/profil/changePassword" class="list-group-item list-group-item-action">
                <i class="fas fa-lock me-2"></i> Sécurité
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Mon Profil</h4>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo URLROOT; ?>/profil/update" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    
                    <h5 class="mb-3">Informations personnelles</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required value="<?php echo isset($user['prenom']) ? Security::escape($user['prenom']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required value="<?php echo isset($user['nom']) ? Security::escape($user['nom']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control bg-light" id="email" value="<?php echo isset($user['email']) ? Security::escape($user['email']) : ''; ?>" readonly>
                        <div class="form-text">Pour modifier votre email, veuillez contacter le support.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo isset($user['telephone']) ? Security::escape($user['telephone']) : ''; ?>">
                    </div>
                    
                    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                        <hr class="my-4">
                        <h5 class="mb-3">Informations étudiantes</h5>
                        <div class="mb-3">
                            <label for="ecole" class="form-label">École / Université</label>
                            <input type="text" class="form-control" id="ecole" name="ecole" value="<?php echo isset($user['ecole']) ? Security::escape($user['ecole']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="garant" class="form-label">Garant (optionnel)</label>
                            <input type="text" class="form-control" id="garant" name="garant" value="<?php echo isset($user['garant']) ? Security::escape($user['garant']) : ''; ?>" placeholder="Ex: Parents, Visale...">
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
