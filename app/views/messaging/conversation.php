<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="<?php echo URLROOT; ?>/message/inbox" class="btn btn-sm btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h5 class="mb-0">
                        <?php echo Security::escape($interlocuteur['prenom'] . ' ' . $interlocuteur['nom']); ?>
                    </h5>
                </div>
            </div>
            
            <div class="card-body d-flex flex-column" style="height: 60vh; background-color: #f8f9fa;">
                <div class="flex-grow-1 overflow-auto mb-3 px-2" id="messages-container">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $msg): ?>
                            <?php $is_me = $msg['expediteur_id'] == $_SESSION['user_id']; ?>
                            <div class="d-flex mb-3 <?php echo $is_me ? 'justify-content-end' : 'justify-content-start'; ?>">
                                <div class="p-3 rounded-3 shadow-sm <?php echo $is_me ? 'bg-primary text-white' : 'bg-white border'; ?>" style="max-width: 75%;">
                                    <p class="mb-1" style="white-space: pre-wrap;"><?php echo Security::escape($msg['contenu']); ?></p>
                                    <small class="<?php echo $is_me ? 'text-white-50' : 'text-muted'; ?> d-block text-end" style="font-size: 0.7rem;">
                                        <?php echo date('d/m/Y H:i', strtotime($msg['date_envoi'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted mt-5">
                            <i class="far fa-comments fa-3x mb-3"></i>
                            <p>Aucun message. Commencez la discussion !</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <form action="<?php echo URLROOT; ?>/message/send" method="POST" class="mt-auto bg-white p-3 border rounded-3 shadow-sm">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    <input type="hidden" name="destinataire_id" value="<?php echo Security::escape($interlocuteur['id']); ?>">
                    
                    <div class="input-group">
                        <textarea name="contenu" class="form-control border-0" rows="2" placeholder="Votre message..." required style="resize: none;"></textarea>
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Faire défiler automatiquement vers le bas pour voir le dernier message
    document.addEventListener("DOMContentLoaded", function() {
        var container = document.getElementById("messages-container");
        container.scrollTop = container.scrollHeight;
    });
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
