<?php
require_once 'db_config.php';

// Récupérer tous les médicaments de la base de données
$medicines = fetchAll("SELECT * FROM medicines ORDER BY id ASC");
$patients = fetchAll("SELECT * FROM patients ORDER BY id ASC");
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
    <?php if (isset($_GET['refilled'])): ?>
        <?php $n = (int)$_GET['refilled']; ?>
        <?php if ($n > 0): ?>
            <div class="alert alert-success" role="alert">Refilled <?php echo $n; ?> item(s).</div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">No items were refilled.</div>
        <?php endif; ?>
    <?php endif; ?>
    <div style="display: flex;">
    <div class="card" style="width: auto; margin: 1rem; padding: 1rem;">
        <div class="card-body">
            <h5 class="card-title">Stock | Advanced viewer </h5>
            <small class="text-muted">💊 Total: <strong><?php echo count($medicines); ?></strong> médicaments</small>
            <?php 
            if (empty($medicines)) {
                echo '<li class="list-group-item text-danger"><span class="badge bg-danger me-2">⚠️</span>Aucun médicament trouvé. Lancez db_init.php d\'abord!</li>';
            } else {
                foreach ($medicines as $medicine): 
                    $low_stock = $medicine['stock'] < 20;
                    $badge_color = $low_stock ? 'bg-danger' : 'bg-primary';
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $low_stock ? 'bg-warning bg-opacity-10' : ''; ?>">
                <!-- MAKE THEM IN A BOX -->
                <!--<div class="card" style="width: 15rem; display: flex;">-->
                    <div>
                        <strong><?php echo htmlspecialchars($medicine['name']); ?></strong>
                        <br><small class="text-muted"><?php echo htmlspecialchars($medicine['category']); ?></small>
                        <br><small class="text-muted"><?php echo htmlspecialchars($medicine['description']); ?></small>
                        <?php if ($low_stock): ?>
                            <br><small class="text-danger fw-bold">⚠️ Stock faible!</small>
                        <?php endif; ?>
                    </div>
                    <span class="badge <?php echo $badge_color; ?> rounded-pill"><?php echo $medicine['stock']; ?></span>
                <!--</div>-->
            </li>
            <?php 
                endforeach;
            }
            ?>
        </div>
    </div>
    <div class="card" style="width: auto; margin: 1rem; padding: 1rem; text-align: center;">
        <div class="card-body">
            <h5 class="card-title">Patients | Advanced viewer </h5>
            <small class="text-muted">👨‍⚕️ Total: <strong><?php echo count($patients); ?></strong> patients</small>
            
            <?php 
            if (empty($patients)) {
                echo '<li class="list-group-item text-danger"><span class="badge bg-danger me-2">⚠️</span>Aucun patient trouvé. Lancez db_init.php d\'abord!</li>';
            } else {
                foreach ($patients as $patient): 
            ?>
                <div>
                    <strong><?php echo htmlspecialchars('id : ' . $patient['id']. ' name : ' . $patient['name'] . ' ' . $patient['last_name']); ?></strong>
                </div>
            </li>
            <?php 
                endforeach;
            }
            ?>
        </div>
    </div>
    </div>
    <div style="display: flex;">
    <div class="card" style="width: auto; margin: 1rem; padding: 1rem;">
        <div class="card-body">
            <h5 class="card-title">Stock | Refile </h5>
            <?php 
                if (empty($medicines)) {
                    echo '<li class="list-group-item text-danger"><span class="badge bg-danger me-2">⚠️</span>Aucun médicament trouvé. Lancez db_init.php d\'abord!</li>';
                } else {
            ?>
            <form method="post" action="refill.php">
                <ul class="list-group list-group-flush">
                <?php foreach ($medicines as $medicine): 
                    $low_stock = $medicine['stock'] < 20;
                    $badge_color = $low_stock ? 'bg-danger' : 'bg-primary';
                ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $low_stock ? 'bg-warning bg-opacity-10' : ''; ?>">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input me-2" type="checkbox" name="ids[]" value="<?php echo (int)$medicine['id']; ?>">
                            <div>
                                <strong><?php echo htmlspecialchars($medicine['name']); ?></strong>
                                <?php if ($low_stock): ?>
                                    <br><small class="text-danger fw-bold">⚠️ Stock faible!</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="badge <?php echo $badge_color; ?> rounded-pill"><?php echo $medicine['stock']; ?></span>
                    </li>
                <?php endforeach; ?>
                </ul>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Refill selected</button>
                </div>
            </form>
            <?php 
                }
            ?>
        </div>
    </div>
    </div>
</body>
</html>