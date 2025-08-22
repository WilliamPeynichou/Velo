<?php
// ========================================
// FICHIER DE TEST COMPLET DE LA BASE DE DONNÉES
// ========================================
// Ce fichier effectue un test complet de la base de données
// avec affichage HTML pour une interface web

// Affiche le titre principal du test
echo "<h1>Test de la base de données</h1>";

try {
    // TEST 1: Vérification de la connexion
    echo "<h2>1. Test de connexion</h2>";
    // Inclut le fichier de configuration de la base de données
    require_once 'private/database.php';
    echo "✅ Connexion réussie<br>";
    
    // TEST 2: Vérification de la table users
    echo "<h2>2. Vérification de la table users</h2>";
    // Vérifie si la table 'users' existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table users existe<br>";
        
        // Affiche la structure détaillée de la table
        echo "<h3>Structure de la table:</h3>";
        $stmt = $pdo->query("DESCRIBE users");
        // Crée un tableau HTML pour afficher la structure
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
        // Parcourt chaque colonne et affiche ses propriétés
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";      // Nom du champ
            echo "<td>" . $row['Type'] . "</td>";       // Type de données
            echo "<td>" . $row['Null'] . "</td>";       // Si NULL est autorisé
            echo "<td>" . $row['Key'] . "</td>";        // Type de clé (PRIMARY, etc.)
            echo "<td>" . $row['Default'] . "</td>";    // Valeur par défaut
            echo "</tr>";
        }
        echo "</table>";
        
        // Compte le nombre total d'utilisateurs
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $count = $stmt->fetch();
        echo "<h3>Nombre d'utilisateurs: " . $count['total'] . "</h3>";
        
        // Si il y a des utilisateurs, affiche leur liste
        if ($count['total'] > 0) {
            echo "<h3>Liste des utilisateurs:</h3>";
            // Récupère tous les utilisateurs (sans les mots de passe pour la sécurité)
            $stmt = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY id");
            // Crée un tableau HTML pour afficher les utilisateurs
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Créé le</th></tr>";
            // Parcourt chaque utilisateur et affiche ses informations
            while ($row = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";           // Identifiant
                echo "<td>" . $row['name'] . "</td>";         // Nom
                echo "<td>" . $row['email'] . "</td>";        // Email
                echo "<td>" . $row['created_at'] . "</td>";   // Date de création
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "❌ Table users n'existe pas<br>";
    }
    
    // TEST 3: Test d'insertion et suppression
    echo "<h2>3. Test d'insertion</h2>";
    // Génère un email unique pour le test (utilise le timestamp actuel)
    $test_email = "test" . time() . "@test.com";
    $test_name = "Test User";
    // Hache le mot de passe de test de manière sécurisée
    $test_password = password_hash("test123", PASSWORD_DEFAULT);
    
    // Vérifie si l'email de test existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$test_email]);
    
    if ($stmt->rowCount() == 0) {
        // Si l'email n'existe pas, on peut faire le test d'insertion
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$test_name, $test_email, $test_password]);
        
        if ($result) {
            // Récupère l'ID de l'utilisateur créé
            $user_id = $pdo->lastInsertId();
            echo "✅ Test d'insertion réussi - ID: $user_id<br>";
            
            // Vérifie que l'insertion a bien fonctionné
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$test_email]);
            $user = $stmt->fetch();
            if ($user) {
                echo "✅ Utilisateur trouvé en base<br>";
            }
            
            // Nettoie en supprimant l'utilisateur de test
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
    // En cas d'erreur, affiche le message et la trace de l'erreur
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// AFFICHAGE DES INFORMATIONS SYSTÈME
echo "<h2>4. Informations système</h2>";
echo "PHP Version: " . phpversion() . "<br>";                                    // Version de PHP
echo "PDO Drivers: " . implode(", ", PDO::getAvailableDrivers()) . "<br>";       // Pilotes PDO disponibles
echo "Extensions chargées: " . implode(", ", get_loaded_extensions()) . "<br>";  // Extensions PHP chargées
?>
