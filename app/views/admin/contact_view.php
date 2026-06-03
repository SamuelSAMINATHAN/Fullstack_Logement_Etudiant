<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid px-4">
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h3">Détail du message #<?php echo $message['idContact']; ?></h1>
        <a href="<?php echo URLROOT; ?>/admin/messages" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <strong>De :</strong> <?php echo htmlspecialchars($message['nom'] ?? 'Anonyme'); ?> <br>
                    <small class="text-muted"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($message['email']); ?></small>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <span class="text-muted">
                        <i class="fas fa-calendar-alt"></i> Reçu le : <?php echo date('d/m/Y à H:i', strtotime($message['dateEnvoi'])); ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <h5 class="card-title fw-bold text-primary mb-3">
                Sujet : <?php echo htmlspecialchars($message['sujet']); ?>
            </h5>
            <hr>
            <div class="card-text text-dark p-3 bg-light rounded" style="white-space: pre-wrap; font-family: inherit; min-height: 150px; line-height: 1.6;">
                <?php echo htmlspecialchars($message['message']); ?>
            </div>
        </div>
        <div class="card-footer bg-light p-3 text-end">
            <a href="<?php echo URLROOT; ?>/admin/deleteMessage/<?php echo $message['idContact']; ?>" class="btn btn-danger" onclick="return confirm('Supprimer définitivement ce message ?')">
                <i class="fas fa-trash"></i> Supprimer le message
            </a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>