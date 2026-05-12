<?php require APPROOT . '/views/layout/header.php'; ?>

<style>
    :root {
        --dorocho-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --dorocho-teal: #00a699;
    }

    .hero-select {
        background: var(--dorocho-gradient);
        padding: 60px 0 100px 0;
        color: white;
        text-align: center;
        border-radius: 0 0 50px 50px;
    }

    .selection-container {
        margin-top: -60px;
    }

    .type-card {
        border: none;
        border-radius: 25px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .type-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(118, 75, 162, 0.2);
    }

    .icon-box {
        width: 80px;
        height: 80px;
        background: #f0f4ff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px auto;
        font-size: 30px;
        color: #764ba2;
    }

    .btn-select {
        background-color: var(--dorocho-teal);
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        border: none;
        margin-top: 15px;
    }
</style>

<section class="hero-select">
    <div class="container">
        <h1 class="fw-bold">Rejoignez Dorocho ✨</h1>
        <p class="lead opacity-75">Choisissez le type de compte qui vous correspond pour commencer.</p>
    </div>
</section>

<div class="container selection-container mb-5">
    <div class="row justify-content-center g-4">
        <div class="col-md-5 col-lg-4">
            <a href="<?= URLROOT ?>/auth/register/etudiant" class="card type-card p-4 text-center">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Je suis Étudiant</h3>
                    <p class="text-muted">Je recherche un logement, une colocation ou je souhaite contacter des bailleurs.</p>
                    <div class="btn btn-select">Choisir ce profil</div>
                </div>
            </a>
        </div>

        <div class="col-md-5 col-lg-4">
            <a href="<?= URLROOT ?>/auth/register/bailleur" class="card type-card p-4 text-center">
                <div class="card-body">
                    <div class="icon-box" style="color: var(--dorocho-teal); background: #e6f7f6;">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Je suis Bailleur</h3>
                    <p class="text-muted">Je souhaite publier des annonces de logement et gérer mes locations facilement.</p>
                    <div class="btn btn-select">Choisir ce profil</div>
                </div>
            </a>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="text-muted">Vous avez déjà un compte ? <a href="<?= URLROOT ?>/auth/login" class="fw-bold text-decoration-none" style="color: #764ba2;">Connectez-vous</a></p>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>