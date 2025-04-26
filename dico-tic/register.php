<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container bg-white">
            <h2 class="form-title">Créer un compte</h2>
            <?php
            // Initialiser la session
            session_start();
            
            // Inclure le fichier de configuration
            require_once "config.php";
            
            // Définir les variables et les initialiser avec des valeurs vides
            $username = $password = $confirm_password = $email = "";
            $username_err = $password_err = $confirm_password_err = $email_err = "";
            
            // Traitement des données du formulaire lors de la soumission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                // Valider le nom d'utilisateur
                if (empty(trim($_POST["username"]))) {
                    $username_err = "Veuillez entrer un nom d'utilisateur.";
                } else {
                    // Préparer une requête SELECT
                    $sql = "SELECT id FROM users WHERE username = :username";
                    
                    if ($stmt = $conn->prepare($sql)) {
                        // Lier les variables à la requête préparée en tant que paramètres
                        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                        
                        // Définir les paramètres
                        $param_username = trim($_POST["username"]);
                        
                        // Tenter d'exécuter la requête préparée
                        if ($stmt->execute()) {
                            if ($stmt->rowCount() == 1) {
                                $username_err = "Ce nom d'utilisateur est déjà pris.";
                            } else {
                                $username = trim($_POST["username"]);
                            }
                        } else {
                            echo '<div class="alert alert-danger">Oups! Quelque chose s\'est mal passé. Veuillez réessayer plus tard.</div>';
                        }
                        
                        // Fermer la déclaration
                        unset($stmt);
                    }
                }
                
                // Valider l'email
                if (empty(trim($_POST["email"]))) {
                    $email_err = "Veuillez entrer une adresse email.";
                } else {
                    $email = trim($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $email_err = "Format d'email invalide.";
                    }
                }
                
                // Valider le mot de passe
                if (empty(trim($_POST["password"]))) {
                    $password_err = "Veuillez entrer un mot de passe.";     
                } elseif (strlen(trim($_POST["password"])) < 6) {
                    $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
                } else {
                    $password = trim($_POST["password"]);
                }
                
                // Valider la confirmation du mot de passe
                if (empty(trim($_POST["confirm_password"]))) {
                    $confirm_password_err = "Veuillez confirmer le mot de passe.";     
                } else {
                    $confirm_password = trim($_POST["confirm_password"]);
                    if (empty($password_err) && ($password != $confirm_password)) {
                        $confirm_password_err = "Les mots de passe ne correspondent pas.";
                    }
                }
                
                // Vérifier les erreurs avant d'insérer dans la base de données
                if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {
                    
                    // Préparer une requête d'insertion
                    $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
                    
                    if ($stmt = $conn->prepare($sql)) {
                        // Lier les variables à la requête préparée en tant que paramètres
                        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                        $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                        
                        // Définir les paramètres
                        $param_username = $username;
                        $param_password = password_hash($password, PASSWORD_DEFAULT); // Hacher le mot de passe
                        $param_email = $email;
                        
                        // Tenter d'exécuter la requête préparée
                        if ($stmt->execute()) {
                            // Rediriger vers la page de connexion
                            echo '<div class="alert alert-success">Inscription réussie! Vous pouvez maintenant vous <a href="index.php">connecter</a>.</div>';
                        } else {
                            echo '<div class="alert alert-danger">Oups! Quelque chose s\'est mal passé. Veuillez réessayer plus tard.</div>';
                        }
                        
                        // Fermer la déclaration
                        unset($stmt);
                    }
                }
                
                // Fermer la connexion
                unset($conn);
            }
            ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo $username; ?>" required>
                    <div class="invalid-feedback"><?php echo $username_err; ?></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>" required>
                    <div class="invalid-feedback"><?php echo $email_err; ?></div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                    <div class="invalid-feedback"><?php echo $password_err; ?></div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                    <div class="invalid-feedback"><?php echo $confirm_password_err; ?></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                    <a href="index.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
            <div class="mt-3 text-center">
                <p>Vous avez déjà un compte? <a href="index.php">Connectez-vous ici</a>.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 