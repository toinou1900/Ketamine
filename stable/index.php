<?php
require_once 'db_config.php';

// Récupérer tous les médicaments de la base de données
$medicines = fetchAll("SELECT * FROM medicines ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketamine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">DashBoard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<body>
    <div class="card" style="width: 20rem; margin: 1rem; padding: 1rem;">
        <div class="card-body">
            <h5 class="card-title">Stock</h5>
            <?php 
                if (empty($medicines)) {
                    echo '<li class="list-group-item text-danger"><span class="badge bg-danger me-2">⚠️</span>Aucun médicament trouvé. Lancez db_init.php d\'abord!</li>';
                } else {
                    foreach ($medicines as $medicine): 
                        $low_stock = $medicine['stock'] < 20;
                        $badge_color = $low_stock ? 'bg-danger' : 'bg-primary';
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $low_stock ? 'bg-warning bg-opacity-10' : ''; ?>">
                    <div>
                        <strong><?php echo htmlspecialchars($medicine['name']); ?></strong>
                        <?php if ($low_stock): ?>
                            <br><small class="text-danger fw-bold">⚠️ Stock faible!</small>
                        <?php endif; ?>
                    </div>
                </li>
                <?php 
                    endforeach;
                }
                ?>
        </div>
    </div>
</body>
</html>