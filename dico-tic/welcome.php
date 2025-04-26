<?php
// Initialiser la session
session_start();

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .welcome-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .welcome-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Système de Connexion</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="welcome.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reset-password.php">Changer de mot de passe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Se déconnecter</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="welcome-container bg-white">
            <h2 class="welcome-title">Bienvenue, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>!</h2>
            
            <div class="user-info">
                <h4>Informations de compte</h4>
                <p><strong>Nom d'utilisateur:</strong> <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
                <p><strong>ID utilisateur:</strong> <?php echo htmlspecialchars($_SESSION["id"]); ?></p>
            </div>
            
            <div class="d-flex justify-content-center">
                <a href="reset-password.php" class="btn btn-warning me-2">Changer de mot de passe</a>
                <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 