<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-white">
                <h3 class="mb-0"><?= !empty($annonce['idAnnonce']) ? 'Modifier l\'annonce' : 'Déposer une annonce' ?></h3>
            </div>
            <div class="card-body p-4">
                <form action="<?= !empty($annonce['idAnnonce']) ? URLROOT . '/annonce/edit/' . $annonce['idAnnonce'] : URLROOT . '/annonce/create' ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo de l'annonce</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        <div class="form-text">Formats acceptés : JPG, PNG, WEBP.</div>
                    </div>

                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de l'annonce</label>
                        <input type="text" class="form-control" id="titre" name="titre" required value="<?= !empty($annonce['titre']) ? Security::escape($annonce['titre']) : '' ?>" placeholder="Ex: Studio cosy au coeur du Quartier Latin">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description détaillée</label>
                        <textarea class="form-control" id="description" name="description" rows="6" required><?= !empty($annonce['description']) ? Security::escape($annonce['description']) : '' ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prix" class="form-label">Prix du loyer (€ / mois)</label>
                            <input type="number" class="form-control" id="prix" name="prix" required step="0.01" value="<?= !empty($annonce['prix']) ? Security::escape($annonce['prix']) : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="localisation" class="form-label">Localisation (Ville / Quartier)</label>
                            <input type="text" class="form-control" id="localisation" name="localisation" required value="<?= !empty($annonce['localisation']) ? Security::escape($annonce['localisation']) : '' ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="type_logement" class="form-label">Type de logement</label>
                            <select class="form-select" id="type_logement" name="type_logement" required>
                                <option value="Studio" <?= !empty($annonce['type_logement']) && $annonce['type_logement'] == 'Studio' ? 'selected' : '' ?>>Studio</option>
                                <option value="Appartement" <?= !empty($annonce['type_logement']) && $annonce['type_logement'] == 'Appartement' ? 'selected' : '' ?>>Appartement</option>
                                <option value="Chambre" <?= !empty($annonce['type_logement']) && $annonce['type_logement'] == 'Chambre' ? 'selected' : '' ?>>Chambre</option>
                                <option value="Colocation" <?= !empty($annonce['type_logement']) && $annonce['type_logement'] == 'Colocation' ? 'selected' : '' ?>>Colocation</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="surface" class="form-label">Surface (m²)</label>
                            <input type="number" class="form-control" id="surface" name="surface" required value="<?= !empty($annonce['surface']) ? Security::escape($annonce['surface']) : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="nbPieces" class="form-label">Nombre de pièces</label>
                            <input type="number" class="form-control" id="nbPieces" name="nbPieces" required value="<?= !empty($annonce['nbPieces']) ? Security::escape($annonce['nbPieces']) : '1' ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dateDisponibilite" class="form-label">Date de disponibilité</label>
                            <input type="date" class="form-control" id="dateDisponibilite" name="dateDisponibilite" required value="<?= !empty($annonce['dateDisponibilite']) ? Security::escape($annonce['dateDisponibilite']) : '' ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block fw-bold mb-2">Équipements et options</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="meuble" name="meuble" <?= !empty($annonce['meuble']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="meuble">Logement meublé</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="estColocation" name="estColocation" <?= !empty($annonce['estColocation']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="estColocation">C'est une colocation</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="ascenseur" name="ascenseur" <?= !empty($annonce['ascenseur']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="ascenseur">Ascenseur</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="parking" name="parking" <?= !empty($annonce['parking']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="parking">Parking / Garage</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="balcon_terrasse" name="balcon_terrasse" <?= !empty($annonce['balcon_terrasse']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="balcon_terrasse">Balcon / Terrasse</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="animaux_acceptes" name="animaux_acceptes" <?= !empty($annonce['animaux_acceptes']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="animaux_acceptes">Animaux acceptés</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="pmr" name="pmr" <?= !empty($annonce['pmr']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="pmr">Accès PMR</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <?= !empty($annonce['idAnnonce']) ? 'Mettre à jour l\'annonce' : 'Publier l\'annonce' ?>
                        </button>
                        <a href="<?= URLROOT ?>/annonce/mesAnnonces" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
