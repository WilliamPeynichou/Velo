<?php
// ========================================
// FICHIER DE DÉBOGAGE POUR LA CRÉATION DE COMPTE
// ========================================
// Ce fichier simule et teste le processus de création de compte
// pour diagnostiquer les problèmes potentiels

// Affiche le titre du débogage
echo "=== DÉBOGAGE CRÉATION DE COMPTE ===\n\n";

// TEST 1: Vérification de la connexion à la base de données
echo "1. Test de connexion...\n";
try {
    // Inclut le fichier de configuration de la base de données
    require_once 'private/database.php';
    echo "✅ Connexion réussie\n";
} catch (Exception $e) {
    // Si la connexion échoue, affiche l'erreur et arrête le script
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    exit;
}

// TEST 2: Vérification de l'existence de la table users
echo "\n2. Vérification de la table users...\n";
try {
    // Vérifie si la table 'users' existe dans la base de données
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table users existe\n";
        
        // Affiche la structure détaillée de la table
        $stmt = $pdo->query("DESCRIBE users");
        echo "Structure:\n";
        // Parcourt chaque colonne et affiche son nom et type
        while ($row = $stmt->fetch()) {
            echo "  - " . $row['Field'] . " : " . $row['Type'] . "\n";
        }
    } else {
        echo "❌ Table users n'existe pas\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// TEST 3: Test d'insertion d'un utilisateur de test
echo "\n3. Test d'insertion...\n";
try {
    // Prépare les données de test
    $test_name = "Test User";
    $test_email = "test" . time() . "@test.com";  // Email unique avec timestamp
    $test_password = password_hash("test123", PASSWORD_DEFAULT);  // Mot de passe haché
    
    // Vérifie si l'email de test existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$test_email]);
    echo "Email existe déjà: " . ($stmt->rowCount() > 0 ? "Oui" : "Non") . "\n";
    
    if ($stmt->rowCount() == 0) {
        // Si l'email n'existe pas, on peut faire le test d'insertion
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$test_name, $test_email, $test_password]);
        
        if ($result) {
            echo "✅ Insertion réussie\n";
            echo "ID généré: " . $pdo->lastInsertId() . "\n";
            
            // Vérifie que l'insertion a bien fonctionné
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$test_email]);
            $user = $stmt->fetch();
            echo "Utilisateur trouvé: " . ($user ? "Oui" : "Non") . "\n";
            
            // Nettoie en supprimant l'utilisateur de test
            $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
            $stmt->execute([$test_email]);
            echo "✅ Test nettoyé\n";
        } else {
            echo "❌ Échec de l'insertion\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur d'insertion: " . $e->getMessage() . "\n";
}

// TEST 4: Simulation complète du processus de création de compte
echo "\n4. Test avec données POST simulées...\n";
// Simule les données qui seraient envoyées par le formulaire
$_POST['name'] = "Test User";
$_POST['email'] = "test" . time() . "@test.com";
$_POST['password'] = "test123456";
$_POST['confirm_password'] = "test123456";

// Traite les données comme dans le vrai formulaire
$name = htmlspecialchars($_POST['name']);        // Nettoie le nom
$email = htmlspecialchars($_POST['email']);      // Nettoie l'email
$password = $_POST['password'];                  // Mot de passe brut
$confirm_password = $_POST['confirm_password'];  // Confirmation du mot de passe

// Affiche les données simulées
echo "Données simulées:\n";
echo "  - Nom: $name\n";
echo "  - Email: $email\n";
echo "  - Mot de passe: " . strlen($password) . " caractères\n";

// VALIDATION: Vérifie les données comme dans le vrai formulaire
$error = [];
if (empty($name)) $error[] = "Nom vide";
if (empty($email)) $error[] = "Email vide";
if (empty($password)) $error[] = "Mot de passe vide";
if ($password !== $confirm_password) $error[] = "Mots de passe différents";

if (empty($error)) {
    echo "✅ Validation réussie\n";
    
    try {
        // Vérifie si l'email est unique (comme dans le vrai formulaire)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            echo "❌ Email déjà utilisé\n";
        } else {
            // Procède à l'insertion avec les données simulées
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hache le mot de passe
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $result = $stmt->execute([$name, $email, $hashed_password]);
            
            if ($result) {
                echo "✅ Insertion réussie avec données simulées\n";
                echo "ID: " . $pdo->lastInsertId() . "\n";
                
                // Nettoie en supprimant l'utilisateur de test
                $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
                $stmt->execute([$email]);
                echo "✅ Nettoyage réussi\n";
            } else {
                echo "❌ Échec insertion données simulées\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    // Affiche les erreurs de validation
    echo "❌ Erreurs de validation: " . implode(", ", $error) . "\n";
}

// Affiche le message de fin de débogage
echo "\n=== FIN DU DÉBOGAGE ===\n";
?>
