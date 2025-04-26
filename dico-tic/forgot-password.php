<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récupération de mot de passe</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .password-container {
            max-width: 400px;
            margin: 100px auto;
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
        <div class="password-container bg-white">
            <h2 class="form-title">Récupération de mot de passe</h2>
            <?php
            // Initialiser la session
            session_start();
            
            // Inclure le fichier de configuration
            require_once "config.php";
            
            // Définir les variables et les initialiser avec des valeurs vides
            $email = $email_err = "";
            
            // Traitement des données du formulaire lors de la soumission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                // Valider l'email
                if (empty(trim($_POST["email"]))) {
                    $email_err = "Veuillez entrer votre adresse email.";
                } else {
                    $email = trim($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $email_err = "Format d'email invalide.";
                    }
                }
                
                // Vérifier les erreurs avant de continuer
                if (empty($email_err)) {
                    // Vérifier si l'email existe dans la base de données
                    $sql = "SELECT id FROM users WHERE email = :email";
                    
                    if ($stmt = $conn->prepare($sql)) {
                        // Lier les variables à la requête préparée en tant que paramètres
                        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                        
                        // Définir les paramètres
                        $param_email = $email;
                        
                        // Tenter d'exécuter la requête préparée
                        if ($stmt->execute()) {
                            if ($stmt->rowCount() == 1) {
                                // L'email existe, générer un token de réinitialisation
                                $token = bin2hex(random_bytes(50));
                                
                                // Stocker le token dans la base de données
                                $reset_sql = "UPDATE users SET reset_token = :token, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email";
                                
                                if ($reset_stmt = $conn->prepare($reset_sql)) {
                                    // Lier les paramètres
                                    $reset_stmt->bindParam(":token", $token, PDO::PARAM_STR);
                                    $reset_stmt->bindParam(":email", $email, PDO::PARAM_STR);
                                    
                                    // Exécuter la requête
                                    if ($reset_stmt->execute()) {
                                        // Dans une application réelle, envoyer un email avec le lien de réinitialisation
                                        // Pour ce projet, nous allons simplement afficher le lien
                                        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password.php?token=" . $token;
                                        echo '<div class="alert alert-success">Un lien de réinitialisation a été généré. Dans une application réelle, un email vous serait envoyé.<br><br>';
                                        echo 'Lien de réinitialisation: <a href="' . $reset_link . '">' . $reset_link . '</a></div>';
                                    } else {
                                        echo '<div class="alert alert-danger">Oups! Quelque chose s\'est mal passé. Veuillez réessayer plus tard.</div>';
                                    }
                                    
                                    // Fermer la déclaration
                                    unset($reset_stmt);
                                }
                            } else {
                                echo '<div class="alert alert-danger">Aucun compte trouvé avec cette adresse email.</div>';
                            }
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
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>" required>
                    <div class="invalid-feedback"><?php echo $email_err; ?></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
                    <a href="index.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
            <div class="mt-3 text-center">
                <p>Retour à la <a href="index.php">page de connexion</a>.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 