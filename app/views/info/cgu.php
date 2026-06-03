<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <h1 class="fw-bold text-primary mb-4"><?php echo $information['titre'] ?? 'Conditions Générales d\'Utilisation (CGU)'; ?></h1>

        <div class="card border-0 shadow-sm p-4 rounded-3 mb-5">
            <?php echo $information['contenu'] ?? 'Contenu indisponible'; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
