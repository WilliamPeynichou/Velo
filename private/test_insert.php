<?php
echo "<h1>Test d'insertion avec les bons noms de colonnes</h1>";

try {
    require_once 'private/database.php';
    echo "✅ Connexion réussie<br><br>";
    
    // Afficher la structure
    echo "<h2>Structure de la table</h2>";
    $stmt = $pdo->query("DESCRIBE users");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Test d'insertion avec les bons noms de colonnes
    echo "<h2>Test d'insertion</h2>";
    $test_email = "test" . time() . "@test.com";
    $test_name = "Test User";
    $test_password = password_hash("test123", PASSWORD_DEFAULT);
    
    echo "Données de test:<br>";
    echo "- Nom: $test_name<br>";
    echo "- Email: $test_email<br>";
    echo "- Mot de passe: " . strlen($test_password) . " caractères (haché)<br><br>";
    
    // Vérifier si l'email existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$test_email]);
    
    if ($stmt->rowCount() == 0) {
        // Insérer avec les bons noms de colonnes
        $stmt = $pdo->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$test_name, $test_email, $test_password]);
        
        if ($result) {
            $user_id = $pdo->lastInsertId();
            echo "✅ Insertion réussie - ID: $user_id<br>";
            
            // Vérifier l'insertion
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$test_email]);
            $user = $stmt->fetch();
            
            if ($user) {
                echo "✅ Utilisateur trouvé en base:<br>";
                echo "- ID: " . $user['id'] . "<br>";
                echo "- Nom: " . $user['nom'] . "<br>";
                echo "- Email: " . $user['email'] . "<br>";
                echo "- Mot de passe: " . substr($user['password'], 0, 20) . "...<br>";
            }
            
            // Nettoyer
            $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
            $stmt->execute([$test_email]);
            echo "✅ Test nettoyé<br>";
        } else {
            echo "❌ Échec de l'insertion<br>";
        }
    } else {
        echo "❌ Email de test existe déjà<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><h2>✅ Test terminé</h2>";
echo "<p>Si le test a réussi, votre formulaire de création de compte devrait maintenant fonctionner !</p>";
?>
