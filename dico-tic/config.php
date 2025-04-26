<?php
// Paramètres de connexion à la base de données
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');     // Nom d'utilisateur par défaut de XAMPP
define('DB_PASSWORD', '');         // Mot de passe par défaut de XAMPP (vide)
define('DB_NAME', 'login_system'); // Nom de la base de données à créer dans PHPMyAdmin

// Tentative de connexion à la base de données MySQL
try {
    $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Définir le mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERREUR: Impossible de se connecter à la base de données. " . $e->getMessage());
}
?> 