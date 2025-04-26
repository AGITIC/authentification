<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .reset-container {
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
        <div class="reset-container bg-white">
            <h2 class="form-title">Réinitialisation de mot de passe</h2>
            <?php
            // Initialiser la session
            session_start();
            
            // Inclure le fichier de configuration
            require_once "config.php";
            
            // Définir les variables et les initialiser avec des valeurs vides
            $new_password = $confirm_password = "";
            $new_password_err = $confirm_password_err = $token_err = "";
            
            // Traitement du jeton
            if (!isset($_GET["token"]) || empty(trim($_GET["token"]))) {
                $token_err = "Jeton de réinitialisation invalide ou manquant.";
            } else {
                $token = trim($_GET["token"]);
                
                // Vérifier si le jeton existe et n'a pas expiré
                $sql = "SELECT id FROM users WHERE reset_token = :token AND reset_expires > NOW()";
                
                if ($stmt = $conn->prepare($sql)) {
                    // Lier les variables à la requête préparée en tant que paramètres
                    $stmt->bindParam(":token", $param_token, PDO::PARAM_STR);
                    
                    // Définir les paramètres
                    $param_token = $token;
                    
                    // Tenter d'exécuter la requête préparée
                    if ($stmt->execute()) {
                        if ($stmt->rowCount() == 0) {
                            $token_err = "Le jeton de réinitialisation est invalide ou a expiré.";
                        }
                    } else {
                        echo '<div class="alert alert-danger">Oups! Quelque chose s\'est mal passé. Veuillez réessayer plus tard.</div>';
                    }
                    
                    // Fermer la déclaration
                    unset($stmt);
                }
            }
            
            // Traitement des données du formulaire lors de la soumission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($token_err)) {
                
                // Valider le nouveau mot de passe
                if (empty(trim($_POST["new_password"]))) {
                    $new_password_err = "Veuillez entrer le nouveau mot de passe.";     
                } elseif (strlen(trim($_POST["new_password"])) < 6) {
                    $new_password_err = "Le mot de passe doit contenir au moins 6 caractères.";
                } else {
                    $new_password = trim($_POST["new_password"]);
                }
                
                // Valider la confirmation du mot de passe
                if (empty(trim($_POST["confirm_password"]))) {
                    $confirm_password_err = "Veuillez confirmer le mot de passe.";     
                } else {
                    $confirm_password = trim($_POST["confirm_password"]);
                    if (empty($new_password_err) && ($new_password != $confirm_password)) {
                        $confirm_password_err = "Les mots de passe ne correspondent pas.";
                    }
                }
                
                // Vérifier les erreurs avant de mettre à jour la base de données
                if (empty($new_password_err) && empty($confirm_password_err) && empty($token_err)) {
                    // Préparer une requête de mise à jour
                    $sql = "UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE reset_token = :token";
                    
                    if ($stmt = $conn->prepare($sql)) {
                        // Lier les variables à la requête préparée en tant que paramètres
                        $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                        $stmt->bindParam(":token", $param_token, PDO::PARAM_STR);
                        
                        // Définir les paramètres
                        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $param_token = $token;
                        
                        // Tenter d'exécuter la requête préparée
                        if ($stmt->execute()) {
                            // Mot de passe mis à jour avec succès. Rediriger vers la page de connexion
                            echo '<div class="alert alert-success">Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous <a href="index.php">connecter</a>.</div>';
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
            
            <?php if (empty($token_err)) : ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?token=" . $token); ?>" method="post">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" id="new_password" name="new_password" required>
                        <div class="invalid-feedback"><?php echo $new_password_err; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback"><?php echo $confirm_password_err; ?></div>
                    </div>
                    <div class="d-grid gap-2">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
                        <a href="index.php" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            <?php else : ?>
                <div class="alert alert-danger"><?php echo $token_err; ?></div>
                <div class="d-grid gap-2">
                    <a href="index.php" class="btn btn-primary">Retour à la page de connexion</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 