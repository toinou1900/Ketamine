<?php

/**
 * Page de connexion administrateur
 */

session_start();
require_once 'db_config.php';

// Si déjà connecté, aller à l'admin
if (isset($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}

$error = '';
$success = '';

// Traiter le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = '❌ Veuillez remplir tous les champs!';
    } else {
        // Chercher l'utilisateur
        $user = fetchOne("SELECT id, username, password FROM users WHERE username = ?", [$username]);

        if ($user && password_verify($password, $user['password'])) {
            // Authentification réussie
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            // Logger la connexion
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
            executeQuery(
                "INSERT INTO logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)",
                [$user['id'], 'login', 'Connexion admin', $ip]
            );
            
            header('Location: admin.php');
            exit;
        } else {
            $error = '❌ Identifiants incorrects!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 0 1rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header text-white">
                <h2 class="mb-0">🔐 Admin Login</h2>
                <p class="text-light mt-2 mb-0">Gestion d'Inventaire Ketamine</p>
            </div>
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="username" 
                            name="username" 
                            placeholder="admin"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••"
                            required
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            🔓 Se connecter
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="alert alert-info small" role="alert">
                    <strong>Demo:</strong><br>
                    User: <code>admin</code><br>
                    Pass: <code>admin123</code>
                </div>

                <p class="text-center mb-0">
                    <a href="index.php" class="text-decoration-none">← Retour à l'accueil</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
