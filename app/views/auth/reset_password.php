<?php require APPROOT . '/views/layout/header.php'; ?>

<style>
    :root {
        --dorocho-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --dorocho-teal: #00a699;
    }
    .hero-auth {
        background: var(--dorocho-gradient);
        padding: 50px 0 80px 0;
        color: white;
        text-align: center;
        border-radius: 0 0 50px 50px;
    }
    .auth-card {
        margin-top: -50px;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .btn-teal {
        background-color: var(--dorocho-teal);
        color: white;
        border-radius: 50px;
        padding: 12px;
        font-weight: 600;
        border: none;
        transition: 0.3s;
    }
</style>

<section class="hero-auth">
    <div class="container">
        <h1 class="fw-bold">Dorocho</h1>
        <p class="lead opacity-75">Sécurisez votre compte</p>
    </div>
</section>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card auth-card p-4">
                <div class="card-body">
                    <h3 class="text-center mb-4">Nouveau mot de passe</h3>

                    <?php if (isset($_SESSION['flash'])): ?>
                        <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                            <div class="alert alert-<?= $type === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show">
                                <?= $message ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endforeach; unset($_SESSION['flash']); ?>
                    <?php endif; ?>

                    <form action="<?= URLROOT ?>/password/resetHandler" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $data['csrf_token'] ?>">
                        <input type="hidden" name="token" value="<?= $data['token'] ?>">

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="8 caractères minimum" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Confirmez le mot de passe</label>
                            <input type="password" name="password_confirm" class="form-control form-control-lg" placeholder="Répétez le mot de passe" required>
                        </div>

                        <button type="submit" class="btn btn-teal w-100 shadow-sm">
                            Mettre à jour mon mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>