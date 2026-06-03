<div class="list-group shadow-sm">
    <a href="<?php echo URLROOT; ?>/profil/dashboard" class="list-group-item list-group-item-action">
        <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
    </a>
    <a href="<?php echo URLROOT; ?>/profil/profile" class="list-group-item list-group-item-action">
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
        <a href="<?php echo URLROOT; ?>/annonce/mesAnnonces" class="list-group-item list-group-item-action">
            <i class="fas fa-building me-2"></i> Mes Annonces
        </a>
    <?php endif; ?>
    <a href="<?php echo URLROOT; ?>/profil/changePassword" class="list-group-item list-group-item-action">
        <i class="fas fa-lock me-2"></i> Sécurité
    </a>
</div>
