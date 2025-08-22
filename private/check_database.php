<?php
// ========================================
// FICHIER DE VÉRIFICATION DE LA BASE DE DONNÉES
// ========================================
// Ce fichier vérifie et crée la table 'users' si elle n'existe pas
// et affiche sa structure

// Inclut le fichier de configuration de la base de données
require_once 'private/database.php';

try {
    // Vérifie si la table 'users' existe dans la base de données
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    
    if ($stmt->rowCount() == 0) {
        // Si la table n'existe pas, on la crée
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
        echo "Table 'users' créée avec succès !\n";
    } else {
        // Si la table existe déjà, affiche un message
        echo "La table 'users' existe déjà.\n";
    }
    
    // Affiche la structure détaillée de la table 'users'
    $stmt = $pdo->query("DESCRIBE users");
    echo "\nStructure de la table 'users' :\n";
    // Parcourt chaque colonne et affiche son nom et son type
    while ($row = $stmt->fetch()) {
        echo "- " . $row['Field'] . " : " . $row['Type'] . "\n";
    }
    
} catch (PDOException $e) {
    // En cas d'erreur, affiche le message d'erreur
    echo "Erreur : " . $e->getMessage() . "\n";
}
?>
