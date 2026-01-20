<?php
require_once 'db_config.php';

// Récupérer tous les médicaments de la base de données
$medicines = fetchAll("SELECT * FROM medicines ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketamine - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="index.php">💊 Ketamine</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">🏠 Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="dashboard.php">📊 Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">📧 Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true" href="log.php">📋 Logs (coming soon)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-fluid py-4">
        <div class="row g-4">
            <!-- Dashboard Info Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">📊 Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Bienvenue sur le dashboard de gestion d'inventaire.</p>
                        <p class="card-text"><small class="text-muted">Gérez vos médicaments et stocks facilement.</small></p>
                    </div>
                </div>
            </div>

            <!-- Inventaire Card -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">💊 Inventaire des Médicaments</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php 
                            if (empty($medicines)) {
                                echo '<li class="list-group-item text-danger"><span class="badge bg-danger me-2">⚠️</span>Aucun médicament trouvé. Lancez db_init.php d\'abord!</li>';
                            } else {
                                foreach ($medicines as $medicine): 
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($medicine['name']); ?></strong>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($medicine['category']); ?></small>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?php echo $medicine['stock']; ?></span>
                            </li>
                            <?php 
                                endforeach;
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="card-footer bg-light text-center">
                        <small class="text-muted">💊 Total: <strong><?php echo count($medicines); ?></strong> médicaments</small>
                    </div>
                </div>
            </div>

            <!-- Admin Link Card -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">🔐 Administration</h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-muted">Accès administrateur réservé</p>
                        <a href="admin_login.php" class="btn btn-dark w-100">
                            🔒 Espace Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2026 Ketamine - Gestion d'Inventaire</p>
    </footer>
</body>
</html>