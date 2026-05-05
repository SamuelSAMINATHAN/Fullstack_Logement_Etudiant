<?php
require APPROOT . '/views/layout/header.php';
?>

<?php if (!isset($annonce) || empty($annonce)): ?>
    <!-- Page d'erreur -->
    <div class="alert alert-danger border-0 rounded-4 text-center py-5" role="alert">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem;"></i>
        <h4 class="mt-3 mb-2">Annonce introuvable</h4>
        <p class="text-muted mb-3">L'annonce que vous recherchez n'existe pas ou a été supprimée.</p>
        <a href="<?php echo URLROOT; ?>/annonce" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour aux annonces
        </a>
    </div>
<?php else: ?>
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/annonce">Annonces</a></li>
            <li class="breadcrumb-item active"><?php echo Security::escape($annonce['titre']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Contenu principal (70%) -->
        <div class="col-lg-8">
            <!-- Galerie photos -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative" style="height: 450px; overflow: hidden;">
                    <img 
                        id="mainImage"
                        src="<?php echo Security::escape($annonce['photo_url'] ?? '/img/default-listing.jpg'); ?>"
                        alt="<?php echo Security::escape($annonce['titre']); ?>"
                        class="w-100 h-100 object-fit-cover"
                    >
                    
                    <!-- Badge -->
                    <span class="badge bg-primary position-absolute top-3 start-3" style="font-size: 0.9rem;">
                        <?php echo Security::escape(ucfirst($annonce['type_logement'])); ?>
                    </span>

                    <!-- Favoris button -->
                    <button 
                        class="btn btn-light btn-lg rounded-circle position-absolute top-3 end-3 shadow-lg"
                        onclick="toggleFavorite(<?php echo $annonce['id']; ?>)"
                    >
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Miniatures -->
                <div class="p-3 bg-light">
                    <div class="row g-2">
                        <?php if (!empty($photos)): ?>
                            <?php foreach ($photos as $photo): ?>
                                <div class="col-3">
                                    <img 
                                        src="<?php echo Security::escape($photo['url']); ?>"
                                        alt="Photo"
                                        class="img-thumbnail cursor-pointer"
                                        style="height: 80px; object-fit: cover; cursor: pointer;"
                                        onclick="document.getElementById('mainImage').src = this.src"
                                    >
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2"><?php echo Security::escape($annonce['titre']); ?></h2>
                            <p class="text-muted mb-3">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <?php echo Security::escape($annonce['adresse']); ?>, 
                                <?php echo Security::escape($annonce['code_postal']); ?> 
                                <?php echo Security::escape($annonce['ville']); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <h3 class="text-primary fw-bold">
                                €<?php echo number_format($annonce['prix_mensuel'], 2, ',', ' '); ?>/mois
                            </h3>
                            <?php if (!empty($annonce['charges'])): ?>
                                <small class="text-muted">
                                    + €<?php echo number_format($annonce['charges'], 2, ',', ' '); ?> charges
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Caractéristiques principales -->
                    <div class="row g-3 mb-4 pb-4 border-bottom">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-ruler-combined text-info" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo Security::escape($annonce['surface']); ?> m²</strong>
                                    <small class="text-muted d-block">Surface</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-door-open text-warning" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo Security::escape($annonce['nombre_pieces']); ?> pièces</strong>
                                    <small class="text-muted d-block">Pièces</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-bed text-success" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo Security::escape($annonce['nombre_chambres']); ?></strong>
                                    <small class="text-muted d-block">Chambre(s)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="fas fa-toilet text-danger" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong><?php echo Security::escape($annonce['nombre_sdb']); ?></strong>
                                    <small class="text-muted d-block">SDB</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Description</h5>
                        <p class="text-muted lh-lg">
                            <?php echo nl2br(Security::escape($annonce['description'])); ?>
                        </p>
                    </div>

                    <!-- Équipements -->
                    <?php if (!empty($annonce['equipements'])): ?>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Équipements</h5>
                            <div class="row g-2">
                                <?php
                                $equipements = array_filter(explode(',', $annonce['equipements']));
                                foreach ($equipements as $equip):
                                ?>
                                    <div class="col-md-6">
                                        <span class="badge bg-light text-dark p-2">
                                            <i class="fas fa-check text-success"></i>
                                            <?php echo Security::escape(trim($equip)); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Localisation -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Localisation</h5>
                        <div class="alert alert-light rounded" style="height: 300px;">
                            <!-- Carte à intégrer (Google Maps) -->
                            <div class="h-100 d-flex align-items-center justify-content-center bg-light rounded">
                                <i class="fas fa-map" style="font-size: 3rem; color: rgba(0,0,0,0.1);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avis -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 p-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-star text-warning"></i> 
                        Avis et évaluations
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($avis)): ?>
                        <?php foreach ($avis as $review): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong><?php echo Security::escape($review['auteur']); ?></strong>
                                        <small class="text-muted d-block">
                                            <?php echo date('d/m/Y', strtotime($review['date_avis'])); ?>
                                        </small>
                                    </div>
                                    <div>
                                        <span class="text-warning">
                                            <?php for ($i = 0; $i < $review['note']; $i++): ?>
                                                <i class="fas fa-star"></i>
                                            <?php endfor; ?>
                                        </span>
                                    </div>
                                </div>
                                <p class="mb-0 text-muted"><?php echo Security::escape($review['contenu']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucun avis pour le moment</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar (30%) -->
        <div class="col-lg-4">
            <!-- Infos propriétaire -->
            <div class="card border-0 shadow-sm rounded-4 sticky-top mb-4" style="top: 100px;">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img 
                            src="<?php echo Security::escape($owner['photo_url'] ?? '/img/default-avatar.jpg'); ?>"
                            alt="<?php echo Security::escape($owner['prenom']); ?>"
                            class="rounded-circle"
                            style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #2563eb;"
                        >
                        <h6 class="mt-3 mb-1 fw-bold"><?php echo Security::escape($owner['prenom'] . ' ' . $owner['nom']); ?></h6>
                        <small class="text-muted">Propriétaire depuis 2021</small>
                    </div>

                    <!-- Étoiles -->
                    <div class="text-center mb-4 pb-4 border-bottom">
                        <div class="text-warning mb-1">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <small class="text-muted">4.5/5 (12 avis)</small>
                    </div>

                    <!-- CTA -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/message/create?recipient=<?php echo $annonce['propriétaire_id']; ?>" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="fas fa-envelope"></i> Contacter
                        </a>
                        <a href="<?php echo URLROOT; ?>/candidature/create?annonce=<?php echo $annonce['id']; ?>" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check"></i> Postuler
                        </a>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </a>
                        <p class="text-center text-muted small mt-3">Connectez-vous pour contacter le propriétaire</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Infos légales -->
            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Informations supplémentaires</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2">
                            <i class="fas fa-calendar text-muted"></i>
                            Publié le <?php echo date('d/m/Y', strtotime($annonce['date_creation'])); ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-eye text-muted"></i>
                            <?php echo number_format($annonce['nombre_vues'] ?? 0); ?> consultations
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            Annonce vérifiée
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function toggleFavorite(id) {
            console.log('Ajouter aux favoris:', id);
        }
    </script>
<?php endif; ?>

<?php require APPROOT . '/views/layout/footer.php'; ?>

