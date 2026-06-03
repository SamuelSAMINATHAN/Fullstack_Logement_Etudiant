<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <?php require APPROOT . '/views/user/sidebar.php'; ?>
        </div>
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-comments me-2 text-primary"></i> Mes Conversations</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($conversations)): ?>
                        <div class="text-center py-5">
                            <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Vous n'avez pas encore de messages.</p>
                            <a href="<?= URLROOT ?>/annonce" class="btn btn-primary btn-sm">Rechercher un logement</a>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($conversations as $conv): 
                                $isMe = ($conv['idExpediteur'] == $_SESSION['user_id']);
                                $otherId = $isMe ? $conv['idDestinataire'] : $conv['idExpediteur'];
                                $otherName = $isMe ? $conv['prenom_destinataire'] . ' ' . $conv['nom_destinataire'] : $conv['prenom_expediteur'] . ' ' . $conv['nom_expediteur'];
                                $unread = (!$isMe && !$conv['estLu']);
                            ?>
                                <a href="<?= URLROOT ?>/message/conversation/<?= $otherId ?>" class="list-group-item list-group-item-action p-3 <?= $unread ? 'bg-light' : '' ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 <?= $unread ? 'fw-bold' : '' ?>">
                                            <?= Security::escape($otherName) ?>
                                            <?php if ($unread): ?>
                                                <span class="badge bg-danger rounded-pill ms-2">Nouveau</span>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($conv['dateEnvoi'])) ?></small>
                                    </div>
                                    <p class="text-muted small mb-0 text-truncate">
                                        <?= $isMe ? '<strong>Moi :</strong> ' : '' ?>
                                        <?= Security::escape($conv['contenu']) ?>
                                    </p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
