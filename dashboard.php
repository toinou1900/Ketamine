<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ketamine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true" href="log.php">Log (coming soon)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<body>
    <div class="card" style="width: 20rem; margin: 1rem; padding: 1rem;">
        <h5 class="card-title">Dashboard</h5>
        <p class="card-text">Welcome to your dashboard.</p>
    </div>
    <div class="card" style="width: 20rem; margin: 1rem; padding: 1rem;">
        <h5 class="card-title">Statistics</h5>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Doliprane
                <span class="badge text-bg-primary rounded-pill" id="doliprane">Loading...</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Paracetamol 500mg
                <span class="badge text-bg-primary rounded-pill" id="paracetamol">Loading...</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Ibuprofen 200mg
                <span class="badge text-bg-primary rounded-pill" id="ibuprofen">Loading...</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                Aspirin 100mg
                <span class="badge text-bg-primary rounded-pill" id="aspirin">Loading...</span>
            </li>
        </ul>
    </div>
</body>
</html>