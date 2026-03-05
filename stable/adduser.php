<?php
require_once 'db_config.php';

// Handle add patient POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['last_name'])) {
    $name = trim($_POST['name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    
    if (!empty($name) && !empty($last_name)) {
        $sql = "INSERT INTO patients (name, last_name) VALUES (?, ?)";
        executeQuery($sql, [$name, $last_name]);
        header('Location: dashboard.php?patient_added=1');
        exit;
    }
}
?>