<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4">Nous contacter</h2>
        <p class="text-center text-muted mb-5">Une question, un problème ou une suggestion ? N'hésitez pas à nous envoyer un message via le formulaire ci-dessous.</p>
        
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="<?php echo URLROOT; ?>/page/contactHandler" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::csrfToken(); ?>">
                    
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom complet</label>
                        <input type="text" class="form-control" id="nom" name="nom" required value="<?php echo isset($_SESSION['user_id']) ? Security::escape($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($_SESSION['user_id']) ? Security::escape($_SESSION['user_email']) : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="sujet" class="form-label">Sujet</label>
                        <select class="form-select" id="sujet" name="sujet" required>
                            <option value="">Choisissez un sujet...</option>
                            <option value="support">Problème technique / Support</option>
                            <option value="signalement">Signaler une annonce / un utilisateur</option>
                            <option value="partenariat">Partenariat</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="message" class="form-label">Votre message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
