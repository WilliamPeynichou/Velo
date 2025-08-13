<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velo - Accueil</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer-style.css">
    <link rel="stylesheet" href="assets/css/home-style.css">
</head>
<body>
    <!-- Header -->
    <?php include 'header.html'; ?>

    <!-- Main Content -->
    <main class="main">
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="title-line">Bienvenue sur</span>
                    <span class="title-highlight">Velo</span>
                </h1>
                <p class="hero-subtitle">Découvrez l'expérience cycliste moderne</p>
                <div class="hero-actions">
                    <a href="login.php" class="btn btn-primary btn-large">Commencer</a>
                    <a href="#features" class="btn btn-secondary btn-large">En savoir plus</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-elements">
                    <div class="floating-circle circle-1"></div>
                    <div class="floating-circle circle-2"></div>
                    <div class="floating-circle circle-3"></div>
                </div>
            </div>
        </div>

        <section id="features" class="features-section">
            <div class="container">
                <h2 class="section-title">Pourquoi choisir Velo ?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🚴</div>
                        <h3>Performance</h3>
                        <p>Suivez vos performances et améliorez votre technique</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🌍</div>
                        <h3>Écologique</h3>
                        <p>Contribuez à un avenir plus vert avec le vélo</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h3>Communauté</h3>
                        <p>Rejoignez une communauté passionnée de cyclistes</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include 'footer.html'; ?>

    <script src="assets/js/home-animations.js"></script>
</body>
</html>
