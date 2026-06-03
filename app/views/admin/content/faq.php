<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestion de la FAQ</h1>
    <a href="<?= URLROOT ?>/adminfaq/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter une question
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Question</th>
                        <th>Réponse</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($faqs)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Aucune FAQ enregistrée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td><?= $faq['idFAQ'] ?></td>
                            <td class="fw-bold"><?= \App\Core\Security::escape($faq['question']) ?></td>
                            <td>
                                <div class="text-truncate" style="max-width: 400px;">
                                    <?= strip_tags($faq['reponse']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= URLROOT ?>/adminfaq/edit/<?= $faq['idFAQ'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="if(confirm('Supprimer cette FAQ ?')) window.location.href='<?= URLROOT ?>/adminfaq/delete/<?= $faq['idFAQ'] ?>'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>
