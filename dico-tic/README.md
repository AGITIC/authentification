# Système de Connexion avec PHP et Bootstrap

Ce projet est un système de connexion complet avec inscription, connexion, et récupération de mot de passe utilisant PHP et Bootstrap.

## Prérequis

- XAMPP (avec PHP 8.0 ou plus récent)
- MySQL
- Navigateur web moderne

## Installation

1. Clonez ou téléchargez ce dépôt dans le répertoire `htdocs` de votre installation XAMPP.
2. Démarrez les services Apache et MySQL depuis le panneau de contrôle XAMPP.
3. Accédez à PHPMyAdmin en visitant `http://localhost/phpmyadmin` dans votre navigateur.
4. Importez le fichier `database.sql` pour créer la base de données et la table nécessaires:
   - Sélectionnez "Importer" dans le menu principal de PHPMyAdmin
   - Cliquez sur "Parcourir" et sélectionnez le fichier `database.sql`
   - Cliquez sur "Exécuter" en bas de la page

## Configuration

Le fichier `config.php` contient les paramètres de connexion à la base de données. Par défaut, il est configuré pour fonctionner avec les paramètres standard de XAMPP:

- **Serveur**: localhost
- **Utilisateur**: root
- **Mot de passe**: (vide)
- **Base de données**: login_system

Si votre configuration diffère, modifiez ces valeurs dans le fichier `config.php`.

## Fonctionnalités

- **Inscription**: Création de compte avec validation des données
- **Connexion**: Authentification sécurisée
- **Récupération de mot de passe**: Processus de réinitialisation par email (simulé)
- **Validation des formulaires**: Côté client et serveur
- **Design responsive**: Utilisation de Bootstrap 5

## Utilisation

1. Accédez au système en visitant `http://localhost/dico-tic` dans votre navigateur.
2. Vous pouvez vous connecter avec l'utilisateur de test:
   - **Nom d'utilisateur**: admin
   - **Mot de passe**: admin123
3. Vous pouvez également créer un nouveau compte via le formulaire d'inscription.

## Sécurité

Ce système implémente plusieurs mesures de sécurité:

- Hachage des mots de passe avec `password_hash()`
- Protection contre les injections SQL avec des requêtes préparées PDO
- Validation des données d'entrée
- Gestion des sessions
- Tokens sécurisés pour la réinitialisation des mots de passe

## Structure des fichiers

- **index.php**: Page de connexion
- **register.php**: Formulaire d'inscription
- **forgot-password.php**: Demande de réinitialisation de mot de passe
- **reset-password.php**: Formulaire de réinitialisation de mot de passe
- **config.php**: Configuration de la base de données
- **database.sql**: Script SQL pour initialiser la base de données

## Note

Ce système est conçu pour être éducatif et peut nécessiter des améliorations supplémentaires pour une utilisation en production, comme l'ajout de reCAPTCHA, la validation d'email, ou des mécanismes d'authentification à deux facteurs. 