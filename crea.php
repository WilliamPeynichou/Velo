<?php
require_once 'private/database.php';

$error = [];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : "";
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "";
    $password = isset($_POST['password']) ? ($_POST['password']) : "";
    $confirm_password = isset($_POST['confirm_password']) ? ($_POST['confirm_password']) : "";

    if (empty($name)) {
        $error[] = "Le nom est requis";
    }elseif(strlen($name) < 3) {
        $error[] = "Le nom doit contenir au moins 3 caractères";
    }

    if (empty($email)) {
        $error[] = "L'email est requis";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "L'email n'est pas valide";
    }
    
    if (empty($password)) {
        $error[] = "Le mot de passe est requis";
    }elseif(strlen($password) < 8) {
        $error[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    // } elseif(!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
    //     $error[] = "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial";
    // }

    if (empty($confirm_password)) {
        $error[] = "La confirmation du mot de passe est requise";
    }elseif($password !== $confirm_password) {
        $error[] = "Les mots de passe ne correspondent pas";
    }

    if (empty($error)) {
        $message = "Compte créé avec succès";
    }
}

   

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velo - Créer un compte</title>
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
            <h1>Créer un compte</h1>
            <p>Rejoignez la communauté Velo</p>
            
            <form class="form" action="#" method="POST">
                <?php if (!empty($error)): ?>
                    <div class="form-message error">
                        <?php foreach ($error as $err): ?>
                            <div><?php echo $err; ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($message)): ?>
                    <div class="form-message success">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Nom -->
                <div class="form-group has-icon user">
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Votre nom" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                </div>
                
                <!-- Email -->
                <div class="form-group has-icon email">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <!-- Mot de passe -->
                <div class="form-group has-icon password">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Minimum 8 caractères" required>
                </div>
                
                <!-- Confirmation mot de passe -->
                <div class="form-group has-icon password">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Répétez votre mot de passe" required>
                </div>
                
                <!-- Bouton de soumission -->
                <button type="submit" class="form-submit">Créer mon compte</button>
                
                <!-- Liens utiles -->
                <div class="form-links">
                    Déjà un compte ? <a href="login.php">Se connecter</a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'footer.html'; ?>
</body>
</html>
