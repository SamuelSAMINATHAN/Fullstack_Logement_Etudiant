<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Messages</h5>
                <span class="badge bg-primary rounded-pill"><?php echo count($conversations ?? []); ?></span>
            </div>
            <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv): ?>
                        <a href="<?php echo URLROOT; ?>/message/conversation/<?php echo Security::escape($conv['interlocuteur_id']); ?>" class="list-group-item list-group-item-action <?php echo (isset($interlocuteur_id) && $interlocuteur_id == $conv['interlocuteur_id']) ? 'active' : ''; ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo Security::escape($conv['interlocuteur_prenom'] . ' ' . $conv['interlocuteur_nom']); ?></h6>
                                <small class="<?php echo (isset($interlocuteur_id) && $interlocuteur_id == $conv['interlocuteur_id']) ? 'text-white' : 'text-muted'; ?>"><?php echo date('d/m', strtotime($conv['dernier_message_date'])); ?></small>
                            </div>
                            <p class="mb-1 small text-truncate <?php echo $conv['non_lu'] ? 'fw-bold' : ''; ?>"><?php echo Security::escape($conv['dernier_message']); ?></p>
                            <?php if ($conv['non_lu']): ?>
                                <span class="badge bg-danger rounded-pill float-end">Nouveau</span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Votre boîte de réception est vide.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <?php if (isset($interlocuteur)): ?>
                <div class="card-header bg-white">
                    <h5 class="mb-0">Conversation avec <?php echo Security::escape($interlocuteur['prenom'] . ' ' . $interlocuteur['nom']); ?></h5>
                </div>
                <div class="card-body d-flex flex-column" style="height: 500px;">
                    <div class="flex-grow-1 overflow-auto mb-3 px-2" id="messages-container">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $msg): ?>
                                <?php $is_me = $msg['expediteur_id'] == $_SESSION['user_id']; ?>
                                <div class="d-flex mb-3 <?php echo $is_me ? 'justify-content-end' : 'justify-content-start'; ?>">
                                    <div class="p-3 rounded-3 <?php echo $is_me ? 'bg-primary text-white' : 'bg-light'; ?>" style="max-width: 75%;">
                                        <p class="mb-1" style="white-space: pre-wrap;"><?php echo Security::escape($msg['contenu']); ?></p>
                                        <small class="<?php echo $is_me ? 'text-white-50' : 'text-muted'; ?>" style="font-size: 0.7rem;">
                                            <?php echo date('d/m/Y H:i', strtotime($msg['date_envoi'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted mt-5">Envoyez votre premier message !</p>
                        <?php endif; ?>
                    </div>
                    
                    <form action="<?php echo URLROOT; ?>/message/send" method="POST" class="mt-auto">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                        <input type="hidden" name="destinataire_id" value="<?php echo Security::escape($interlocuteur['id']); ?>">
                        
                        <div class="input-group">
                            <textarea name="contenu" class="form-control" rows="2" placeholder="Écrivez votre message..." required></textarea>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <script>
                    // Scroll to bottom of messages container
                    document.addEventListener("DOMContentLoaded", function() {
                        var container = document.getElementById("messages-container");
                        container.scrollTop = container.scrollHeight;
                    });
                </script>
            <?php else: ?>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="text-center text-muted">
                        <i class="far fa-comments fa-4x mb-3"></i>
                        <h4>Sélectionnez une conversation</h4>
                        <p>Choisissez un contact dans la liste pour afficher les messages.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
