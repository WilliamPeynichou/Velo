<?php
// ========================================
// FICHIER DE TEST RAPIDE DE LA BASE DE DONNÉES
// ========================================
// Ce fichier effectue un test rapide de connexion, insertion et suppression
// pour vérifier que tout fonctionne correctement

// Affiche le message de début de test
echo "Test rapide de connexion...\n";

try {
    // Inclut le fichier de configuration de la base de données
    require_once 'private/database.php';
    echo "✅ Connexion réussie !\n";
    
    // TEST D'INSERTION: Insère un utilisateur de test
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    // Exécute la requête avec des données de test
    // password_hash() hache le mot de passe de manière sécurisée
    $stmt->execute(['Test User', 'test@test.com', password_hash('test123', PASSWORD_DEFAULT)]);
    echo "✅ Test d'insertion réussi !\n";
    
    // NETTOYAGE: Supprime l'utilisateur de test pour ne pas polluer la base
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
    $stmt->execute(['test@test.com']);
    echo "✅ Test de suppression réussi !\n";
    
} catch (Exception $e) {
    // En cas d'erreur, affiche le message d'erreur
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
