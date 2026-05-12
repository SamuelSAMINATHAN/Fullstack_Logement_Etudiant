<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0">Publier une annonce</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/annonce/<?php echo isset($annonce) ? 'editHandler/' . $annonce['id'] : 'createHandler'; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    
                    <h5 class="mb-3 text-primary border-bottom pb-2">Informations générales</h5>
                    
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de l'annonce *</label>
                        <input type="text" class="form-control" id="titre" name="titre" required value="<?php echo isset($annonce['titre']) ? Security::escape($annonce['titre']) : ''; ?>">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="type_logement" class="form-label">Type de logement *</label>
                            <select class="form-select" id="type_logement" name="type_logement" required>
                                <option value="">Sélectionnez...</option>
                                <option value="chambre" <?php echo (isset($annonce['type_logement']) && $annonce['type_logement'] === 'chambre') ? 'selected' : ''; ?>>Chambre</option>
                                <option value="studio" <?php echo (isset($annonce['type_logement']) && $annonce['type_logement'] === 'studio') ? 'selected' : ''; ?>>Studio</option>
                                <option value="t1" <?php echo (isset($annonce['type_logement']) && $annonce['type_logement'] === 't1') ? 'selected' : ''; ?>>T1 / T1 Bis</option>
                                <option value="t2" <?php echo (isset($annonce['type_logement']) && $annonce['type_logement'] === 't2') ? 'selected' : ''; ?>>T2</option>
                                <option value="t3" <?php echo (isset($annonce['type_logement']) && $annonce['type_logement'] === 't3') ? 'selected' : ''; ?>>T3 ou plus</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="surface" class="form-label">Surface (en m²) *</label>
                            <input type="number" class="form-control" id="surface" name="surface" required value="<?php echo isset($annonce['surface']) ? Security::escape($annonce['surface']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description complète *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo isset($annonce['description']) ? Security::escape($annonce['description']) : ''; ?></textarea>
                    </div>

                    <h5 class="mb-3 text-primary border-bottom pb-2 mt-4">Localisation</h5>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse *</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" required value="<?php echo isset($annonce['adresse']) ? Security::escape($annonce['adresse']) : ''; ?>">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="code_postal" class="form-label">Code postal *</label>
                            <input type="text" class="form-control" id="code_postal" name="code_postal" required value="<?php echo isset($annonce['code_postal']) ? Security::escape($annonce['code_postal']) : ''; ?>">
                        </div>
                        <div class="col-md-8">
                            <label for="ville" class="form-label">Ville *</label>
                            <input type="text" class="form-control" id="ville" name="ville" required value="<?php echo isset($annonce['ville']) ? Security::escape($annonce['ville']) : ''; ?>">
                        </div>
                    </div>

                    <h5 class="mb-3 text-primary border-bottom pb-2 mt-4">Tarifs et conditions</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="loyer" class="form-label">Loyer mensuel (hors charges) *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="loyer" name="loyer" required value="<?php echo isset($annonce['loyer']) ? Security::escape($annonce['loyer']) : ''; ?>">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="charges" class="form-label">Montant des charges</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="charges" name="charges" value="<?php echo isset($annonce['charges']) ? Security::escape($annonce['charges']) : ''; ?>">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="depot_garantie" class="form-label">Dépôt de garantie</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="depot_garantie" name="depot_garantie" value="<?php echo isset($annonce['depot_garantie']) ? Security::escape($annonce['depot_garantie']) : ''; ?>">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="date_disponibilite" class="form-label">Date de disponibilité *</label>
                            <input type="date" class="form-control" id="date_disponibilite" name="date_disponibilite" required value="<?php echo isset($annonce['date_disponibilite']) ? Security::escape($annonce['date_disponibilite']) : date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="meuble" name="meuble" value="1" <?php echo (isset($annonce['meuble']) && $annonce['meuble']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="meuble">Logement meublé</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="charges_comprises" name="charges_comprises" value="1" <?php echo (isset($annonce['charges_comprises']) && $annonce['charges_comprises']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="charges_comprises">Charges comprises dans le loyer</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="colocation_acceptee" name="colocation_acceptee" value="1" <?php echo (isset($annonce['colocation_acceptee']) && $annonce['colocation_acceptee']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="colocation_acceptee">Colocation acceptée</label>
                        </div>
                    </div>

                    <h5 class="mb-3 text-primary border-bottom pb-2 mt-4">Photos</h5>

                    <div class="mb-4">
                        <label for="image" class="form-label">Photo principale</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg, image/png, image/webp">
                        <?php if (isset($annonce['image']) && $annonce['image']): ?>
                            <div class="mt-2">
                                <small class="text-muted">Image actuelle :</small>
                                <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo Security::escape($annonce['image']); ?>" alt="Image actuelle" class="img-thumbnail d-block mt-1" style="height: 100px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo URLROOT; ?>/annonce/mes-annonces" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <?php echo isset($annonce) ? 'Mettre à jour' : 'Publier l\'annonce'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
