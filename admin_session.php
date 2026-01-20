<?php

/**
 * Gestion des sessions administrateur
 * À inclure en début de chaque page protégée
 */

session_start();

// Rediriger vers login si pas connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Optionnel : vérifier que l'utilisateur existe toujours
require_once 'db_config.php';
$admin = fetchOne("SELECT id, username FROM users WHERE id = ?", [$_SESSION['admin_id']]);

if (!$admin) {
    session_destroy();
    header('Location: admin_login.php');
    exit;
}

// Fonction pour logger les actions
function logAction($user_id, $action, $description = null) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    executeQuery(
        "INSERT INTO logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)",
        [$user_id, $action, $description, $ip]
    );
}

// Fonction pour déconnecter
function logoutAdmin() {
    logAction($_SESSION['admin_id'], 'logout', 'Déconnexion');
    session_destroy();
    header('Location: admin_login.php');
    exit;
}
?>
