<?php
// ========================================
// FICHIER DE CONFIGURATION DE LA BASE DE DONNÉES
// ========================================
// Ce fichier gère la connexion à la base de données MySQL
// et l'initialisation automatique de la base et des tables

// Fonction principale pour établir la connexion à la base de données
function connect_db() {
    // Configuration des paramètres de connexion MySQL
    $host = "localhost";        // Adresse du serveur MySQL (généralement localhost)
    $db_name = "velo";          // Nom de la base de données à utiliser
    $username = "root";         // Nom d'utilisateur MySQL (root par défaut avec Laragon)
    $password = "";             // Mot de passe MySQL (vide par défaut avec Laragon)
    $port = 3306;               // Port MySQL standard
    $charset = "utf8mb4";       // Encodage des caractères (supporte les emojis)

    try {
        // ÉTAPE 1: Test de connexion initial sans spécifier de base de données
        // On se connecte d'abord au serveur MySQL sans base spécifique
        $dsn_test = "mysql:host=$host;port=$port;charset=$charset";
        $pdo_test = new PDO($dsn_test, $username, $password);
        // Configure PDO pour lever des exceptions en cas d'erreur
        $pdo_test->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // ÉTAPE 2: Vérifier si la base de données 'velo' existe
        $stmt = $pdo_test->query("SHOW DATABASES LIKE '$db_name'");
        if ($stmt->rowCount() == 0) {
            // Si la base n'existe pas, on la crée automatiquement
            // utf8mb4_unicode_ci permet de supporter tous les caractères Unicode
            $pdo_test->exec("CREATE DATABASE `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }
        
        // ÉTAPE 3: Se connecter à la base de données spécifique
        $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset;port=$port";
        $pdo = new PDO($dsn, $username, $password);
        // Configure PDO pour lever des exceptions en cas d'erreur
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Configure PDO pour retourner les résultats sous forme de tableau associatif
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Retourne l'objet PDO connecté à la base de données
        return $pdo;

    } catch (PDOException $e) {
        // En cas d'erreur de connexion, on log l'erreur et on arrête l'exécution
        error_log("Erreur de connexion à la base de données : " . $e->getMessage());
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// ÉTAPE 4: Initialisation automatique de la base de données
try {
    // Établit la connexion en appelant la fonction
    $pdo = connect_db();
    
    // Vérifie si la table 'users' existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        // Si la table n'existe pas, on la crée automatiquement
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,           -- Clé primaire auto-incrémentée
            name VARCHAR(255) NOT NULL,                  -- Nom de l'utilisateur (obligatoire)
            email VARCHAR(255) NOT NULL UNIQUE,          -- Email unique (obligatoire)
            password VARCHAR(255) NOT NULL,              -- Mot de passe haché (obligatoire)
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,           -- Date de création automatique
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Date de modification automatique
        )";
        $pdo->exec($sql);
    }
} catch (Exception $e) {
    // En cas d'erreur lors de l'initialisation, on log l'erreur
    error_log("Erreur lors de l'initialisation de la base de données : " . $e->getMessage());
}

?>
