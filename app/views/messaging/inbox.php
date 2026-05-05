<?php
require APPROOT . '/views/layout/header.php';
?>

<div class="row h-75">
    <div class="col-lg-4 mb-4">
        <!-- Liste des conversations -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-envelope"></i> Messages
                    </h5>
                    <span class="badge bg-primary"><?php echo count($conversations ?? []); ?></span>
                </div>
                <input 
                    type="text" 
                    class="form-control form-control-sm"
                    placeholder="Rechercher une conversation..."
                    id="conversationSearch"
                >
            </div>
            <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv): ?>
                        <a 
                            href="<?php echo URLROOT; ?>/message/conversation/<?php echo $conv['id']; ?>"
                            class="list-group-item list-group-item-action py-3 px-4 <?php echo ($conv['id'] === $current_conversation_id ? 'active' : ''); ?>"
                        >
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <strong><?php echo Security::escape($conv['autre_utilisateur_nom']); ?></strong>
                                <small class="text-muted"><?php echo date('H:i', strtotime($conv['dernier_message_date'])); ?></small>
                            </div>
                            <p class="mb-1 text-muted text-truncate" style="font-size: 0.9rem;">
                                <?php echo Security::escape(substr($conv['dernier_message'], 0, 40)); ?>...
                            </p>
                            <?php if ($conv['non_lus'] > 0): ?>
                                <span class="badge bg-primary"><?php echo $conv['non_lus']; ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p class="mt-2 mb-0">Aucune conversation</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Conversation actuelle -->
    <div class="col-lg-8">
        <?php if (!empty($current_conversation)): ?>
            <div class="card border-0 shadow-sm rounded-4 d-flex flex-column" style="height: 100%;">
                <!-- Header -->
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0"><?php echo Security::escape($current_conversation['autre_utilisateur_nom']); ?></h5>
                        <small class="text-muted">Connecté il y a 5 min</small>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" title="Appel">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger ms-2" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <div class="card-body p-4 flex-grow-1" style="overflow-y: auto; background-color: #f8f9fa;">
                    <div id="messagesContainer">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $msg): ?>
                                <?php $is_mine = $msg['auteur_id'] === $_SESSION['user_id']; ?>
                                <div class="mb-3 d-flex <?php echo ($is_mine ? 'justify-content-end' : 'justify-content-start'); ?>">
                                    <div class="<?php echo ($is_mine ? 'bg-primary text-white' : 'bg-light'); ?> rounded-3 p-3" style="max-width: 70%;">
                                        <p class="mb-0"><?php echo Security::escape($msg['contenu']); ?></p>
                                        <small class="<?php echo ($is_mine ? 'text-white-50' : 'text-muted'); ?> d-block mt-1">
                                            <?php echo date('H:i', strtotime($msg['date_message'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Input -->
                <div class="card-footer bg-white border-top p-3">
                    <form method="POST" action="<?php echo URLROOT; ?>/message/send" class="d-flex gap-2">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                        <input type="hidden" name="conversation_id" value="<?php echo $current_conversation['id']; ?>">
                        
                        <input 
                            type="text" 
                            name="message" 
                            class="form-control rounded-pill"
                            placeholder="Écrivez votre message..."
                            required
                        >
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex align-items-center justify-content-center">
                <div class="text-center text-muted">
                    <i class="fas fa-comments" style="font-size: 4rem; opacity: 0.1;"></i>
                    <h5 class="mt-3 mb-2">Sélectionnez une conversation</h5>
                    <p>Ou commencez une nouvelle conversation</p>
                    <a href="<?php echo URLROOT; ?>/annonce" class="btn btn-primary btn-sm mt-3">
                        <i class="fas fa-plus"></i> Nouvelle conversation
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Auto-scroll vers le dernier message
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    // Refresh messages toutes les 3 secondes
    setInterval(function() {
        location.reload();
    }, 3000);
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
