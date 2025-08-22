<?php
// ========================================
// FICHIER DE TEST DE CONNEXION À LA BASE DE DONNÉES
// ========================================
// Ce fichier permet de diagnostiquer les problèmes de connexion
// et de vérifier que tout fonctionne correctement

// Affiche le titre du test
echo "=== Test de connexion à la base de données ===\n\n";

// TEST 1: Vérifier si le serveur MySQL est accessible
echo "1. Test de connexion MySQL...\n";
try {
    // Tente de se connecter au serveur MySQL sans spécifier de base de données
    $pdo_test = new PDO("mysql:host=localhost;port=3306", "root", "");
    echo "✅ Connexion MySQL réussie\n";
} catch (PDOException $e) {
    // Si la connexion échoue, affiche l'erreur et arrête le script
    echo "❌ Erreur MySQL: " . $e->getMessage() . "\n";
    exit;
}

// TEST 2: Vérifier si la base de données 'velo' existe
echo "\n2. Vérification de la base de données 'velo'...\n";
try {
    // Vérifie si la base de données 'velo' existe sur le serveur
    $stmt = $pdo_test->query("SHOW DATABASES LIKE 'velo'");
    if ($stmt->rowCount() > 0) {
        // Si la base existe, affiche un message de succès
        echo "✅ Base de données 'velo' existe\n";
    } else {
        // Si la base n'existe pas, on l'affiche et on la crée
        echo "❌ Base de données 'velo' n'existe pas\n";
        echo "Création de la base de données...\n";
        // Crée la base de données avec l'encodage UTF-8
        $pdo_test->exec("CREATE DATABASE velo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Base de données 'velo' créée\n";
    }
} catch (PDOException $e) {
    // En cas d'erreur, affiche le message d'erreur
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// TEST 3: Tester la connexion avec le fichier database.php principal
echo "\n3. Test avec votre configuration database.php...\n";
try {
    // Inclut le fichier de configuration principal
    require_once 'private/database.php';
    echo "✅ Connexion réussie avec database.php\n";
    
    // TEST 4: Vérifier si la table 'users' existe
    echo "\n4. Vérification de la table 'users'...\n";
    // Vérifie si la table 'users' existe dans la base de données
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        // Si la table existe, affiche un message de succès
        echo "✅ Table 'users' existe\n";
        
        // Affiche la structure détaillée de la table
        $stmt = $pdo->query("DESCRIBE users");
        echo "Structure de la table:\n";
        // Parcourt chaque colonne de la table et affiche ses informations
        while ($row = $stmt->fetch()) {
            echo "  - " . $row['Field'] . " : " . $row['Type'] . "\n";
        }
    } else {
        // Si la table n'existe pas, on l'affiche et on la crée
        echo "❌ Table 'users' n'existe pas\n";
        echo "Création de la table...\n";
        
        // Définit la structure de la table 'users'
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,           -- Identifiant unique auto-incrémenté
            name VARCHAR(255) NOT NULL,                  -- Nom de l'utilisateur (obligatoire)
            email VARCHAR(255) NOT NULL UNIQUE,          -- Email unique (obligatoire)
            password VARCHAR(255) NOT NULL,              -- Mot de passe haché (obligatoire)
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,           -- Date de création automatique
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Date de modification automatique
        )";
        
        // Exécute la requête de création de la table
        $pdo->exec($sql);
        echo "✅ Table 'users' créée\n";
    }
    
} catch (PDOException $e) {
    // En cas d'erreur avec le fichier database.php, affiche l'erreur
    echo "❌ Erreur avec database.php: " . $e->getMessage() . "\n";
}

// Affiche le message de fin de test
echo "\n=== Test terminé ===\n";
?>
