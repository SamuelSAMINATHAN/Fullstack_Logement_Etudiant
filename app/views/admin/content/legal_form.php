<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<?php 
    $isEdit = isset($info) && $info !== null;
    $actionUrl = $isEdit ? URLROOT . '/admin/editLegal/' . $info['idInfo'] : URLROOT . '/admin/addLegal';
    $title = $isEdit ? 'Modifier : ' . \App\Core\Security::escape($info['titre']) : 'Ajouter un texte légal';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= $title ?></h1>
    <a href="<?= URLROOT ?>/admin/legal" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <form action="<?= $actionUrl ?>" method="POST">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre du document</label>
                <input type="text" class="form-control" id="titre" name="titre" required 
                       value="<?= $isEdit ? \App\Core\Security::escape($info['titre']) : '' ?>"
                       placeholder="Ex: CGU, Mentions Légales...">
            </div>

            <div class="mb-4">
                <label for="contenu" class="form-label">Contenu (HTML autorisé)</label>
                <textarea class="form-control" id="contenu" name="contenu" rows="15" required 
                          placeholder="Saisissez le texte ici..."><?= $isEdit ? \App\Core\Security::escape($info['contenu']) : '' ?></textarea>
                <div class="form-text mt-2">
                    <i class="fas fa-info-circle me-1"></i> Vous pouvez utiliser des balises HTML pour la mise en forme (h2, p, ul, li...).
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <?php if ($isEdit): ?>
                    <span class="text-muted small">Dernière mise à jour : <?= date('d/m/Y H:i', strtotime($info['dateMiseAJour'])) ?></span>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
                
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i> <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le texte' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
