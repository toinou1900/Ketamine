<?php
require_once 'db_config.php';

// Handle remove patient POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name'] ?? '');
    
    if (!empty($name)) {
        $sql = "DELETE FROM patients WHERE name = ?";
        executeQuery($sql, [$name]);
        header('Location: dashboard.php?patient_removed=1');
        exit;
    }
}
