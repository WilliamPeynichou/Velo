<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velo - Connexion</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer-style.css">
    <link rel="stylesheet" href="assets/css/form-style.css">
</head>
<body>
    <!-- Header -->
    <?php include 'header.html'; ?>

    <!-- Main Content -->
    <main class="main">
        <div class="form-container">
            <h1>Connexion</h1>
            <p>Connectez-vous à votre compte Velo</p>
            
            <form class="form" action="#" method="POST">
                <!-- Message d'exemple -->
                <div class="form-message error">
                    Email ou mot de passe incorrect
                </div>
                
                <!-- Email -->
                <div class="form-group has-icon email">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" required>
                </div>
                
                <!-- Mot de passe -->
                <div class="form-group has-icon password">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Votre mot de passe" required>
                </div>
                
                <!-- Checkbox "Se souvenir de moi" -->
                <div class="form-checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Se souvenir de moi</label>
                </div>
                
                <!-- Bouton de soumission -->
                <button type="submit" class="form-submit">Se connecter</button>
                
                <!-- Liens utiles -->
                <div class="form-links">
                    <a href="#">Mot de passe oublié ?</a> | 
                    <a href="crea.php">Créer un compte</a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'footer.html'; ?>
</body>
</html>
