<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <?php require APPROOT . '/views/user/sidebar.php'; ?>
        </div>
        <div class="col-md-9">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex align-items-center">
                    <a href="<?= URLROOT ?>/message/inbox" class="btn btn-link text-decoration-none p-0 me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            <?= strtoupper(substr($otherUser['prenom'], 0, 1) . substr($otherUser['nom'], 0, 1)) ?>
                        </div>
                        <h5 class="mb-0"><?= Security::escape($otherUser['prenom'] . ' ' . $otherUser['nom']) ?></h5>
                    </div>
                </div>
                
                <div class="card-body bg-light" id="message-container" style="height: 400px; overflow-y: auto; display: flex; flex-direction: column;">
                    <?php if (empty($messages)): ?>
                        <div class="text-center my-auto">
                            <p class="text-muted">Aucun message dans cette conversation.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <?php $isMe = ($msg['idExpediteur'] == $_SESSION['user_id']); ?>
                            <div class="mb-3 <?= $isMe ? 'text-end ms-auto' : 'me-auto' ?>" style="max-width: 75%;">
                                <div class="p-3 rounded <?= $isMe ? 'bg-primary text-white rounded-end-0' : 'bg-white shadow-sm rounded-start-0' ?>">
                                    <p class="mb-1"><?= Security::escape($msg['contenu']) ?></p>
                                    <small class="<?= $isMe ? 'text-white-50' : 'text-muted' ?>" style="font-size: 0.75rem;">
                                        <?= date('d/m H:i', strtotime($msg['dateEnvoi'])) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-white border-top-0 p-3">
                    <form action="<?= URLROOT ?>/message/send" method="POST" id="replyForm">
                        <input type="hidden" name="csrf_token" value="<?= Security::csrfToken() ?>">
                        <input type="hidden" name="idDestinataire" value="<?= $otherUser['idUtilisateur'] ?>">
                        <div class="input-group">
                            <textarea name="contenu" class="form-control" placeholder="Écrivez votre message..." rows="1" required style="resize: none;"></textarea>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('message-container');
    container.scrollTop = container.scrollHeight;
    
    // Auto-resize textarea
    const textarea = document.querySelector('textarea[name="contenu"]');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
