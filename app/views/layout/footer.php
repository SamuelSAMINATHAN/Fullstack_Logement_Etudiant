    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-5">
        <div class="container">
            <div class="row">
                <!-- À Propos -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-home"></i> Dorocho
                    </h5>
                    <p class="text-muted">Plateforme de logements étudiants sécurisée et fiable pour trouver votre logement parfait.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Liens utiles -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/" class="text-muted text-decoration-none">Accueil</a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/annonce" class="text-muted text-decoration-none">Annonces</a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/page/faq" class="text-muted text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/page/contact" class="text-muted text-decoration-none">Nous contacter</a></li>
                    </ul>
                </div>

                <!-- Informations légales -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Légal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/page/mentions-legales" class="text-muted text-decoration-none">Mentions légales</a></li>
                        <li class="mb-2"><a href="<?php echo URLROOT; ?>/page/cgu" class="text-muted text-decoration-none">Conditions générales</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Politique de confidentialité</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Politique de cookies</a></li>
                    </ul>
                </div>
            </div>

            <hr class="bg-secondary">

            <!-- Copyright -->
            <div class="row">
                <div class="col-md-6 text-muted">
                    <p>&copy; <?php echo date('Y'); ?> Dorocho. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end text-muted">
                    <p>Développé avec <i class="fas fa-heart text-danger"></i> pour les étudiants</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    <script>
        // Fermeture automatique des alertes après 5 secondes
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bootstrapAlert = new bootstrap.Alert(alert);
                setTimeout(function() {
                    bootstrapAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>
