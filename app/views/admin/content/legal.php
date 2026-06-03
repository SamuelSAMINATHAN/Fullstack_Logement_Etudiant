<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">CGU & Mentions Légales</h1>
</div>

<div class="row g-4">
    <?php foreach ($infos as $info): ?>
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= \App\Core\Security::escape($info['titre']) ?></h5>
                <span class="badge bg-light text-dark small">Mis à jour le <?= date('d/m/Y', strtotime($info['dateMiseAJour'])) ?></span>
            </div>
            <div class="card-body">
                <div class="text-muted small mb-3" style="height: 100px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                    <?= strip_tags($info['contenu']) ?>
                </div>
                <div class="d-grid">
                    <a href="<?= URLROOT ?>/admin/editLegal/<?= $info['idInfo'] ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i> Modifier le contenu
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
