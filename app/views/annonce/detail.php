<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-lg-8">
        <!-- Galerie Photos -->
        <div class="card shadow-sm mb-4">
            <div id="carouselAnnonce" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded-top">
                    <?php if (empty($annonce['photos'])): ?>
                        <div class="carousel-item active">
                            <img src="<?= URLROOT ?>/img/no-image.jpg" class="d-block w-100" alt="Pas de photo" style="height: 400px; object-fit: cover;">
                        </div>
                    <?php else: ?>
                        <?php foreach ($annonce['photos'] as $index => $photo): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= URLROOT . Security::escape($photo['urlPhoto']) ?>" class="d-block w-100" alt="Photo" style="height: 400px; object-fit: cover;">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if (count($annonce['photos'] ?? []) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAnnonce" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselAnnonce" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2 class="mb-0"><?= Security::escape($annonce['titre']) ?></h2>
                    <div class="text-end">
                        <h3 class="text-primary mb-0"><?= number_format($annonce['prix'], 2) ?> € <small class="text-muted" style="font-size: 0.5em;">/ mois</small></h3>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4 text-muted small">
                    <span><i class="fas fa-map-marker-alt me-1"></i> <?= Security::escape($annonce['localisation']) ?></span>
                    <span><i class="fas fa-expand me-1"></i> <?= Security::escape($annonce['surface']) ?> m²</span>
                    <span><i class="fas fa-door-open me-1"></i> <?= Security::escape($annonce['nbPieces']) ?> pièce(s)</span>
                    <span><i class="fas fa-calendar-alt me-1"></i> Dispo : <?= date('d/m/Y', strtotime($annonce['dateDisponibilite'])) ?></span>
                </div>

                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-secondary" style="white-space: pre-line;"><?= Security::escape($annonce['description']) ?></p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-couch mb-2 text-primary"></i>
                            <div class="small fw-bold">Mobilier</div>
                            <div class="small text-muted"><?= $annonce['meuble'] ? 'Meublé' : 'Non meublé' ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-users mb-2 text-primary"></i>
                            <div class="small fw-bold">Colocation</div>
                            <div class="small text-muted"><?= $annonce['estColocation'] ? 'Acceptée' : 'Non acceptée' ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-home mb-2 text-primary"></i>
                            <div class="small fw-bold">Type</div>
                            <div class="small text-muted"><?= Security::escape($annonce['type_logement']) ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-building mb-2 text-primary"></i>
                            <div class="small fw-bold">Ascenseur</div>
                            <div class="small text-muted"><?= $annonce['ascenseur'] ? 'Oui' : 'Non' ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-car mb-2 text-primary"></i>
                            <div class="small fw-bold">Parking</div>
                            <div class="small text-muted"><?= $annonce['parking'] ? 'Oui' : 'Non' ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-sun mb-2 text-primary"></i>
                            <div class="small fw-bold">Balcon</div>
                            <div class="small text-muted"><?= $annonce['balcon_terrasse'] ? 'Oui' : 'Non' ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-paw mb-2 text-primary"></i>
                            <div class="small fw-bold">Animaux</div>
                            <div class="small text-muted"><?= $annonce['animaux_acceptes'] ? 'Acceptés' : 'Non' ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center h-100">
                            <i class="fas fa-wheelchair mb-2 text-primary"></i>
                            <div class="small fw-bold">Accès PMR</div>
                            <div class="small text-muted"><?= $annonce['pmr'] ? 'Oui' : 'Non' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Avis -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Avis des étudiants (<?= count($annonce['avis']) ?>)</h5>
                <?php if ($annonce['note_moyenne']): ?>
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-star me-1"></i> <?= number_format($annonce['note_moyenne'], 1) ?> / 5
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if ($this->isLoggedIn() && $_SESSION['user_role'] === 'etudiant'): ?>
                    <form action="<?= URLROOT ?>/avis/add" method="POST" class="mb-4 pb-4 border-bottom">
                        <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                        <input type="hidden" name="idAnnonce" value="<?= $annonce['idAnnonce'] ?>">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Note</label>
                                <select name="note" class="form-select" required>
                                    <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Très bien</option>
                                    <option value="3">3 - Moyen</option>
                                    <option value="2">2 - Passable</option>
                                    <option value="1">1 - À éviter</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Votre commentaire</label>
                                <textarea name="commentaire" class="form-control" rows="2" placeholder="Partagez votre expérience..."></textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-sm btn-primary">Publier mon avis</button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

                <?php if (empty($annonce['avis'])): ?>
                    <p class="text-center text-muted py-3">Aucun avis pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($annonce['avis'] as $avis): ?>
                        <div class="mb-3 pb-3 border-bottom last-child-no-border">
                            <div class="d-flex justify-content-between mb-1">
                                <strong><?= Security::escape($avis['prenom'] . ' ' . $avis['nom']) ?></strong>
                                <span class="text-warning small">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <i class="<?= $i <= $avis['note'] ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <p class="small text-secondary mb-1"><?= Security::escape($avis['commentaire']) ?></p>
                            <small class="text-muted" style="font-size: 0.8em;"><?= date('d/m/Y', strtotime($avis['dateAvis'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Bloc Propriétaire -->
        <div class="card shadow-sm mb-4 sticky-top" style="top: 2rem;">
            <div class="card-body">
                <h5 class="mb-3">Le Propriétaire</h5>
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <?= strtoupper(substr($annonce['prenom'], 0, 1) . substr($annonce['nom'], 0, 1)) ?>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?= Security::escape($annonce['prenom'] . ' ' . $annonce['nom']) ?></h6>
                        <?php if ($annonce['estVerifie']): ?>
                            <span class="badge bg-success small">
                                <i class="fas fa-check-circle me-1"></i> Propriétaire Vérifié
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-envelope me-2"></i> Contacter le bailleur
                    </button>
                    <?php if ($this->isLoggedIn() && $_SESSION['user_role'] === 'etudiant'): ?>
                        <button class="btn btn-outline-danger btn-toggle-favoris <?= $annonce['est_favori'] ? 'active' : '' ?>" data-id="<?= $annonce['idAnnonce'] ?>">
                            <i class="<?= $annonce['est_favori'] ? 'fas' : 'far' ?> fa-heart me-2"></i>
                            <span class="btn-text"><?= $annonce['est_favori'] ? 'Dans mes favoris' : 'Ajouter aux favoris' ?></span>
                        </button>
                        <button class="btn btn-link text-danger btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                            <i class="fas fa-flag me-1"></i> Signaler cette annonce
                        </button>
                    <?php endif; ?>
                </div>

                <hr>

                <h6 class="mb-3">Partager cette offre</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-light flex-grow-1" onclick="copyToClipboard('<?= URLROOT ?>/annonce/detail/<?= $annonce['idAnnonce'] ?>')">
                        <i class="fas fa-link me-2"></i> Copier le lien
                    </button>
                    <a href="mailto:?subject=Offre de logement : <?= Security::escape($annonce['titre']) ?>&body=Découvre cette offre sur Dorocho : <?= URLROOT ?>/annonce/detail/<?= $annonce['idAnnonce'] ?>" class="btn btn-light">
                        <i class="fas fa-share-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Contact -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contacter <?= Security::escape($annonce['prenom']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= URLROOT ?>/message/send" method="POST">
                <div class="modal-body">
                    <?php if (!$this->isLoggedIn()): ?>
                        <div class="alert alert-warning">
                            Vous devez être connecté pour envoyer un message.
                            <a href="<?= URLROOT ?>/auth/login" class="alert-link">Se connecter</a>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                        <input type="hidden" name="idDestinataire" value="<?= $annonce['idBailleur'] ?>">
                        <input type="hidden" name="idAnnonce" value="<?= $annonce['idAnnonce'] ?>">
                        
                        <div class="mb-3">
                            <label for="contenu" class="form-label">Votre message</label>
                            <textarea class="form-control" id="contenu" name="contenu" rows="5" required placeholder="Bonjour, je suis intéressé par votre annonce..."></textarea>
                        </div>
                        <div class="form-text">
                            Soyez poli et précis pour obtenir une réponse rapide.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <?php if ($this->isLoggedIn()): ?>
                        <button type="submit" class="btn btn-primary">Envoyer le message</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= URLROOT ?>/js/favoris.js"></script>
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Lien copié dans le presse-papier !');
    }).catch(err => {
        console.error('Erreur lors de la copie :', err);
    });
}
</script>

<!-- Modal de Signalement -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Signaler cette annonce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= URLROOT ?>/signalement/send" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                    <input type="hidden" name="idAnnonce" value="<?= $annonce['idAnnonce'] ?>">
                    
                    <div class="mb-3">
                        <label for="motif" class="form-label">Pourquoi signalez-vous cette annonce ?</label>
                        <select name="motif" id="motif" class="form-select" required>
                            <option value="">Choisissez un motif...</option>
                            <option value="Fraude / Arnaque">Fraude / Arnaque</option>
                            <option value="Contenu inapproprié">Contenu inapproprié</option>
                            <option value="Annonce déjà louée">Annonce déjà louée</option>
                            <option value="Informations erronées">Informations erronées</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-text">
                        Votre signalement sera examiné par notre équipe de modération.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Signaler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
