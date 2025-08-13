<?php

function connect_db() {

    $host = "localhost";

    $db_name = "velo";

    $username = "root";

    $password = "";

    $port = 3306;

    $charset = "utf8mb4";

    try {
        $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset;port=$port";

        $pdo = new PDO($dsn, $username, $password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;

    } catch (PDOException $e) {

        die("Erreur de connexion à la base de données : " . $e->getMessage());
    
}
}

$pdo = connect_db();


?>
