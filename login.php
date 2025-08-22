<?php
require_once 'private/database.php';

$error = [];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $remember = isset($_POST['remember']) ? true : false;

    // Validation des données
    if (empty($email)) {
        $error[] = "L'email est requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "L'email n'est pas valide";
    }

    if (empty($password)) {
        $error[] = "Le mot de passe est requis";
    }

    if (empty($error)) {
        try {
            // Vérifier si l'utilisateur existe avec cet email
            $stmt = $pdo->prepare("SELECT id, nom, email, mdp FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                
                // Vérifier le mot de passe
                if (password_verify($password, $user['mdp'])) {
                    // Connexion réussie
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    
                    // Option "Se souvenir de moi"
                    if ($remember) {
                        // Créer un cookie sécurisé pour "se souvenir"
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 jours
                        
                        // Stocker le token en base de données (optionnel)
                        // $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                        // $stmt->execute([$token, $user['id']]);
                    }
                    
                    $message = "Connexion réussie ! Redirection...";
                    
                    // Rediriger vers la page d'accueil ou dashboard
                    header("refresh:2;url=home.php");
                } else {
                    $error[] = "Email ou mot de passe incorrect";
                }
            } else {
                $error[] = "Email ou mot de passe incorrect";
            }
        } catch (PDOException $e) {
            $error[] = "Erreur de base de données : " . $e->getMessage();
        } catch (Exception $e) {
            $error[] = "Erreur générale : " . $e->getMessage();
        }
    }
}
?>

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
                
                <!-- Email -->
                <div class="form-group has-icon email">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="votre@email.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <!-- Mot de passe -->
                <div class="form-group has-icon password">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Votre mot de passe" required>
                </div>
                
                <!-- Checkbox "Se souvenir de moi" -->
                <div class="form-checkbox">
                    <input type="checkbox" id="remember" name="remember" <?php echo isset($_POST['remember']) ? 'checked' : ''; ?>>
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
