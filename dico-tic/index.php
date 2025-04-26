<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
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
        <div class="login-container bg-white">
            <h2 class="form-title">Connexion</h2>
            <?php
            // Initialiser la session
            session_start();
            
            // Vérifier si l'utilisateur est déjà connecté, si oui le rediriger
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                header("location: welcome.php");
                exit;
            }
            
            // Inclure le fichier de configuration
            require_once "config.php";
            
            // Définir les variables et les initialiser avec des valeurs vides
            $username = $password = "";
            $username_err = $password_err = $login_err = "";
            
            // Traitement du formulaire lors de la soumission
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                
                // Vérifier si le nom d'utilisateur est vide
                if(empty(trim($_POST["username"]))){
                    $username_err = "Veuillez entrer votre nom d'utilisateur.";
                } else{
                    $username = trim($_POST["username"]);
                }
                
                // Vérifier si le mot de passe est vide
                if(empty(trim($_POST["password"]))){
                    $password_err = "Veuillez entrer votre mot de passe.";
                } else{
                    $password = trim($_POST["password"]);
                }
                
                // Valider les identifiants
                if(empty($username_err) && empty($password_err)){
                    // Préparer une instruction select
                    $sql = "SELECT id, username, password FROM users WHERE username = :username";
                    
                    if($stmt = $conn->prepare($sql)){
                        // Lier les variables à l'instruction préparée en tant que paramètres
                        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                        
                        // Définir les paramètres
                        $param_username = $username;
                        
                        // Tenter d'exécuter l'instruction préparée
                        if($stmt->execute()){
                            // Vérifier si le nom d'utilisateur existe, si oui alors vérifier le mot de passe
                            if($stmt->rowCount() == 1){
                                if($row = $stmt->fetch()){
                                    $id = $row["id"];
                                    $username = $row["username"];
                                    $hashed_password = $row["password"];
                                    if(password_verify($password, $hashed_password)){
                                        // Le mot de passe est correct, donc démarrer une nouvelle session
                                        session_start();
                                        
                                        // Stocker les données dans des variables de session
                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["id"] = $id;
                                        $_SESSION["username"] = $username;                            
                                        
                                        // Rediriger l'utilisateur vers la page d'accueil
                                        echo '<div class="alert alert-success">Connexion réussie! Redirection...</div>';
                                        echo '<script>setTimeout(function(){ window.location.href = "welcome.php"; }, 2000);</script>';
                                    } else{
                                        // Le mot de passe n'est pas valide
                                        $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                                    }
                                }
                            } else{
                                // Le nom d'utilisateur n'existe pas
                                $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                            }
                        } else{
                            echo '<div class="alert alert-danger">Oups! Quelque chose s\'est mal passé. Veuillez réessayer plus tard.</div>';
                        }

                        // Fermer le statement
                        unset($stmt);
                    }
                }
                
                // Fermer la connexion
                unset($conn);
            }
            ?>
            
            <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo $username; ?>" required>
                    <div class="invalid-feedback"><?php echo $username_err; ?></div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                    <div class="invalid-feedback"><?php echo $password_err; ?></div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>
            <div class="mt-3 text-center">
                <a href="register.php">Créer un compte</a> | <a href="forgot-password.php">Mot de passe oublié?</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 