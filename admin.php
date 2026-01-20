<?php

/**
 * Page Admin - User Control Center (UCC)
 * Accès réservé aux administrateurs
 */

require_once 'admin_session.php';

// Traiter les actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'logout') {
        logoutAdmin();
    }

    elseif ($action === 'change_password') {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $current_user = fetchOne("SELECT password FROM websiteuser WHERE id = ?", [$_SESSION['admin_id']]);

        if (!password_verify($old_password, $current_user['password'])) {
            $message = '<div class="alert alert-danger">❌ Ancien mot de passe incorrect!</div>';
        } elseif (strlen($new_password) < 6) {
            $message = '<div class="alert alert-danger">❌ Le nouveau mot de passe doit faire au moins 6 caractères!</div>';
        } elseif ($new_password !== $confirm_password) {
            $message = '<div class="alert alert-danger">❌ Les mots de passe ne correspondent pas!</div>';
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            executeQuery("UPDATE websiteuser SET password = ? WHERE id = ?", [$hashed, $_SESSION['admin_id']]);
            $message = '<div class="alert alert-success">✅ Mot de passe changé avec succès!</div>';
        }
    }

    elseif ($action === 'add_user') {
        $new_username = trim($_POST['new_username'] ?? '');
        $new_email = trim($_POST['new_email'] ?? '');
        $new_password = $_POST['new_password'] ?? '';

        if (empty($new_username) || empty($new_email) || empty($new_password)) {
            $message = '<div class="alert alert-danger">❌ Tous les champs sont requis!</div>';
        } elseif (strlen($new_password) < 6) {
            $message = '<div class="alert alert-danger">❌ Le mot de passe doit faire au moins 6 caractères!</div>';
        } else {
            try {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                insertAndGetId(
                    "INSERT INTO websiteuser (username, email, password) VALUES (?, ?, ?)",
                    [$new_username, $new_email, $hashed]
                );
                $message = '<div class="alert alert-success">✅ Utilisateur créé avec succès!</div>';
            } catch (PDOException $e) {
                $message = '<div class="alert alert-danger">❌ Cet utilisateur existe déjà!</div>';
            }
        }
    }
}

// Récupérer les infos actuelles
$current_user = fetchOne("SELECT * FROM websiteuser WHERE id = ?", [$_SESSION['admin_id']]);
$all_users = fetchAll("SELECT id, username, email, created_at FROM websiteuser");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Control Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            margin: 0.5rem 0;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }
        .user-badge {
            background-color: rgba(255,255,255,0.2);
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            color: white;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <div class="ps-3">
                    <h4 class="text-white mb-3">⚙️ Admin Panel</h4>
                    <div class="user-badge">
                        <p class="mb-1"><strong>👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></p>
                        <small><?php echo htmlspecialchars($current_user['email']); ?></small>
                    </div>

                    <nav class="nav flex-column">
                        <a href="#dashboard" class="nav-link active" data-bs-toggle="tab">📊 Dashboard</a>
                        <a href="#users" class="nav-link" data-bs-toggle="tab">👥 Utilisateurs</a>
                        <a href="#settings" class="nav-link" data-bs-toggle="tab">⚡ Paramètres</a>
                    </nav>

                    <hr class="bg-light">

                    <a href="index.php" class="btn btn-light btn-sm w-100 mb-2">🏠 Retour accueil</a>
                    <form method="POST" class="d-inline w-100">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="btn btn-danger btn-sm w-100">🔓 Déconnexion</button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-9 p-4">
                <?php echo $message; ?>

                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <h2>📊 Dashboard Administrateur</h2>
                        <p class="text-muted">Bienvenue dans le User Control Center (UCC)</p>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <h3 class="text-primary"><?php echo count($all_users); ?></h3>
                                    <p class="text-muted mb-0">Utilisateurs</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <h3 class="text-success"><?php echo count($recent_logs); ?></h3>
                                    <p class="text-muted mb-0">Actions récentes</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <h3 class="text-info">✅</h3>
                                    <p class="text-muted mb-0">Système actif</p>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">🔐 Informations du compte</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>ID:</strong> <?php echo $current_user['id']; ?></p>
                                <p><strong>Utilisateur:</strong> <?php echo htmlspecialchars($current_user['username']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($current_user['email']); ?></p>
                                <p><strong>Créé:</strong> <?php echo date('d/m/Y H:i', strtotime($current_user['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Users Tab -->
                    <div class="tab-pane fade" id="users">
                        <h2>👥 Gestion des Utilisateurs</h2>

                        <div class="card mt-3 mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">➕ Créer un nouvel utilisateur</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="add_user">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" class="form-control mb-2" name="new_username" placeholder="Nom d'utilisateur" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="email" class="form-control mb-2" name="new_email" placeholder="Email" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="password" class="form-control mb-2" name="new_password" placeholder="Mot de passe (min 6)" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">✅ Créer</button>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">📋 Liste des utilisateurs</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Utilisateur</th>
                                            <th>Email</th>
                                            <th>Créé</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                                <?php if ($user['id'] == $_SESSION['admin_id']): ?>
                                                    <span class="badge bg-primary">MOI</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane fade" id="settings">
                        <h2>⚡ Paramètres</h2>

                        <div class="card mt-3">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">🔑 Changer le mot de passe</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="change_password">
                                    <div class="mb-3">
                                        <label for="old_password" class="form-label">Ancien mot de passe</label>
                                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="min 6 caractères" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning">🔄 Changer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
