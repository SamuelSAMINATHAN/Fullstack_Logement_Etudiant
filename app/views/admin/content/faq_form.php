<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($faq) ? 'Modifier la FAQ' : 'Ajouter une FAQ' ?></h1>
    <a href="<?= URLROOT ?>/adminfaq/index" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow-sm max-width-800">
    <div class="card-body">
        <form action="<?= isset($faq) ? URLROOT . '/adminfaq/update/' . $faq['idFAQ'] : URLROOT . '/adminfaq/store' ?>" method="POST">
            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <input type="text" class="form-label form-control" id="question" name="question" 
                       value="<?= isset($faq) ? \App\Core\Security::escape($faq['question']) : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="reponse" class="form-label">Réponse</label>
                <textarea class="form-control" id="reponse" name="reponse" rows="6" required><?= isset($faq) ? \App\Core\Security::escape($faq['reponse']) : '' ?></textarea>
                <div class="form-text">Le HTML simple est autorisé.</div>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> <?= isset($faq) ? 'Mettre à jour' : 'Enregistrer' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
