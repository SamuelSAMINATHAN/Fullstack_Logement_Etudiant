<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Modifier : <?= \App\Core\Security::escape($info['titre']) ?></h1>
    <a href="<?= URLROOT ?>/admin/legal" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= URLROOT ?>/admin/editLegal/<?= $info['idInfo'] ?>" method="POST">
            <div class="mb-3">
                <label for="contenu" class="form-label">Contenu (HTML autorisé)</label>
                <textarea class="form-control" id="contenu" name="contenu" rows="20" required><?= \App\Core\Security::escape($info['contenu']) ?></textarea>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Dernière mise à jour : <?= date('d/m/Y H:i', strtotime($info['dateMiseAJour'])) ?></span>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
