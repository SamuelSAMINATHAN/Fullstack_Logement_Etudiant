<?php require APPROOT . '/views/layout/admin_header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Messages reçus (Contact)</h1>
    
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

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <?php if (empty($messages)): ?>
                <div class="alert alert-info mb-0">Aucun message de contact reçu pour le moment.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Nom / Email</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <tr class="<?php echo !$msg['traite'] ? 'fw-bold table-warning' : ''; ?>">
                                    <td><?php echo date('d/m/Y H:i', strtotime($msg['dateEnvoi'])); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($msg['nom'] ?? 'Anonyme'); ?><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($msg['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($msg['sujet']); ?></td>
                                    <td>
                                        <?php if ($msg['traite']): ?>
                                            <span class="badge bg-success">Traité</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Non lu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo URLROOT; ?>/admin/viewMessage/<?php echo $msg['idContact']; ?>" class="btn btn-sm btn-primary">Lire</a>
                                        <a href="<?php echo URLROOT; ?>/admin/deleteMessage/<?php echo $msg['idContact']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce message ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/admin_footer.php'; ?>