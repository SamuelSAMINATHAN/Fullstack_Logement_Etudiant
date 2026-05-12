<?php require APPROOT . '/views/layout/header.php'; ?>

<style>
    :root {
        --dorocho-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --dorocho-teal: #00a699;
    }
    .hero-reg {
        background: var(--dorocho-gradient);
        padding: 50px 0 80px 0;
        color: white;
        text-align: center;
        border-radius: 0 0 50px 50px;
    }
    .auth-card {
        margin-top: -60px;
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .btn-dorocho {
        background-color: var(--dorocho-teal);
        color: white;
        border-radius: 50px;
        padding: 12px;
        font-weight: 600;
        border: none;
        transition: 0.3s;
    }
    .btn-dorocho:hover { background-color: #00847a; transform: translateY(-2px); }
</style>

<div class="hero-reg">
    <div class="container">
        <h1 class="fw-bold">Rejoindre Dorocho ✨</h1>
        <p class="opacity-75">Créez votre profil étudiant pour trouver votre futur chez-vous.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card auth-card p-4">
                <div class="card-body">
                    <?php if (isset($_SESSION['flash'])): ?>
                        <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                            <div class="alert alert-<?= $type === 'error' ? 'danger' : 'success' ?> shadow-sm"><?= $message ?></div>
                        <?php endforeach; unset($_SESSION['flash']); ?>
                    <?php endif; ?>

                    <form action="<?= URLROOT ?>/auth/registerHandler" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">
                        <input type="hidden" name="role" value="etudiant">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Prénom</label>
                                <input type="text" name="prenom" class="form-control rounded-3" placeholder="Ex: Lucas" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Nom</label>
                                <input type="text" name="nom" class="form-control rounded-3" placeholder="Ex: Martin" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Adresse Email</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="nom@etudiant.univ.fr" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Date de naissance</label>
                                <input type="date" name="dateNaissance" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Ville de recherche</label>
                                <input type="text" name="localisation" class="form-control rounded-3" placeholder="Ex: Paris, Lyon...">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Mot de passe</label>
                                <input type="password" name="password" class="form-control rounded-3" minlength="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirm" class="form-control rounded-3" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dorocho w-100 shadow-sm mb-3">Créer mon compte étudiant</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted small">Vous avez déjà un compte ? <a href="<?= URLROOT ?>/auth/login" class="text-decoration-none fw-bold" style="color: #764ba2;">Connectez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>