<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="mb-3">
    <a href="<?php echo URLROOT; ?>/annonce/liste" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour aux annonces
    </a>
</div>

<?php if (isset($annonce)): ?>
    <div class="row">
        <!-- Colonne principale : Détails -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo Security::escape($annonce['image'] ?? 'default.jpg'); ?>" class="card-img-top" alt="Image logement" style="height: 400px; object-fit: cover;">
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="card-title h2 mb-0"><?php echo Security::escape($annonce['titre']); ?></h1>
                        <span class="badge bg-primary fs-6"><?php echo Security::escape($annonce['type_logement']); ?></span>
                    </div>
                    
                    <p class="text-muted fs-5">
                        <i class="fas fa-map-marker-alt text-danger"></i> <?php echo Security::escape($annonce['adresse']); ?>, <?php echo Security::escape($annonce['code_postal']); ?> <?php echo Security::escape($annonce['ville']); ?>
                    </p>
                    
                    <div class="row text-center bg-light p-3 rounded mb-4 mx-0">
                        <div class="col-4 border-end">
                            <h4 class="text-primary mb-0"><?php echo Security::escape($annonce['loyer']); ?> €</h4>
                            <small class="text-muted">/ mois (CC)</small>
                        </div>
                        <div class="col-4 border-end">
                            <h4 class="mb-0"><?php echo Security::escape($annonce['surface']); ?> m²</h4>
                            <small class="text-muted">Surface</small>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-0"><?php echo $annonce['meuble'] ? 'Oui' : 'Non'; ?></h4>
                            <small class="text-muted">Meublé</small>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Description</h4>
                    <p class="card-text" style="white-space: pre-line;"><?php echo Security::escape($annonce['description']); ?></p>
                    
                    <hr>
                    
                    <h4 class="mb-3">Critères & Équipements</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Charges comprises
                                    <span class="badge bg-<?php echo $annonce['charges_comprises'] ? 'success' : 'secondary'; ?> rounded-pill">
                                        <?php echo $annonce['charges_comprises'] ? 'Oui' : 'Non'; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Montant des charges
                                    <span><?php echo Security::escape($annonce['charges'] ?? '0'); ?> €</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Dépôt de garantie
                                    <span><?php echo Security::escape($annonce['depot_garantie'] ?? '0'); ?> €</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Colocation acceptée
                                    <span class="badge bg-<?php echo $annonce['colocation_acceptee'] ? 'success' : 'secondary'; ?> rounded-pill">
                                        <?php echo $annonce['colocation_acceptee'] ? 'Oui' : 'Non'; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Date de disponibilité
                                    <span><?php echo date('d/m/Y', strtotime($annonce['date_disponibilite'])); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Avis -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Avis</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($avis)): ?>
                        <?php foreach ($avis as $a): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <strong><?php echo Security::escape($a['utilisateur_prenom']); ?></strong>
                                    <span class="text-warning">
                                        <?php for($i=1; $i<=5; $i++) { echo $i <= $a['note'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; } ?>
                                    </span>
                                </div>
                                <p class="text-muted small mb-1"><?php echo date('d/m/Y', strtotime($a['date_creation'])); ?></p>
                                <p class="mb-0"><?php echo Security::escape($a['commentaire']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">Aucun avis pour ce logement pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Colonne latérale : Contact / Actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4 sticky-top" style="top: 90px; z-index: 1000;">
                <div class="card-body text-center">
                    <h5 class="card-title mb-1">Contacter le propriétaire</h5>
                    <p class="text-muted mb-4"><?php echo Security::escape($annonce['bailleur_prenom'] . ' ' . $annonce['bailleur_nom']); ?></p>
                    
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant'): ?>
                        <form action="<?php echo URLROOT; ?>/candidature/apply" method="POST" class="mb-3">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                            <input type="hidden" name="annonce_id" value="<?php echo Security::escape($annonce['id']); ?>">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-paper-plane"></i> Envoyer ma candidature
                            </button>
                        </form>
                        
                        <a href="<?php echo URLROOT; ?>/message/conversation/<?php echo Security::escape($annonce['bailleur_id']); ?>" class="btn btn-outline-primary w-100 mb-3">
                            <i class="fas fa-comment-alt"></i> Envoyer un message
                        </a>
                        
                        <form action="<?php echo URLROOT; ?>/favoris/add" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                            <input type="hidden" name="annonce_id" value="<?php echo Security::escape($annonce['id']); ?>">
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="far fa-heart"></i> Ajouter aux favoris
                            </button>
                        </form>
                        
                    <?php elseif (!isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-info py-2 mb-3">
                            Connectez-vous pour postuler.
                        </div>
                        <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-primary w-100 mb-2">Se connecter</a>
                        <a href="<?php echo URLROOT; ?>/auth/register" class="btn btn-outline-secondary w-100">S'inscrire</a>
                        
                    <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $annonce['bailleur_id']): ?>
                        <div class="alert alert-success py-2 mb-3">
                            C'est votre annonce.
                        </div>
                        <a href="<?php echo URLROOT; ?>/annonce/edit/<?php echo Security::escape($annonce['id']); ?>" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <button class="btn btn-light w-100 btn-sm text-muted" onclick="navigator.clipboard.writeText(window.location.href); alert('Lien copié !');">
                        <i class="fas fa-share-alt"></i> Partager l'annonce
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-danger">
        L'annonce demandée est introuvable.
    </div>
<?php endif; ?>

<?php require APPROOT . '/views/layout/footer.php'; ?>
