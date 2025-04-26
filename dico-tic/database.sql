-- Supprimer la base de données si elle existe déjà
DROP DATABASE IF EXISTS login_system;

-- Créer la base de données
CREATE DATABASE login_system;

-- Utiliser la base de données
USE login_system;

-- Créer la table utilisateurs
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insérer un utilisateur de test (mot de passe: admin123)
INSERT INTO users (username, password, email) VALUES
('admin', '$2y$10$Z3HO/qkGsNGD2T5QIj1N6um2Zb.ZBwTEl2s1Wl7R0YCQh6vjNHyQW', 'admin@example.com'); 