<?php require APPROOT . '/views/layout/header.php'; ?>

<style>
    :root {
        --dorocho-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --dorocho-teal: #00a699;
    }
    .hero-reg {
        background: var(--dorocho-gradient);
        padding: 80px 0;
        color: white;
        text-align: center;
        border-radius: 0 0 50px 50px;
    }
    .btn-outline-white {
        border: 2px solid white;
        color: white;
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-outline-white:hover {
        background: white;
        color: #764ba2;
    }
</style>

<div class="hero-reg">
    <div class="container">
        <h1 class="fw-bold mb-4">Prêt à trouver votre logement ?</h1>
        <p class="lead mb-5 opacity-75">Créez votre compte Dorocho pour accéder à toutes nos annonces vérifiées.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= URLROOT ?>/auth/register/etudiant" class="btn btn-outline-white">Inscription Étudiant</a>
            <a href="<?= URLROOT ?>/auth/register/bailleur" class="btn btn-outline-white">Espace Bailleur</a>
        </div>
    </div>
</div>

<div class="container my-5 text-center">
    <p class="text-muted">Déjà inscrit sur Dorocho ? <a href="<?= URLROOT ?>/auth/login" class="fw-bold text-decoration-none" style="color: #764ba2;">Connectez-vous ici</a></p>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>