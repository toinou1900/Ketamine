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
$admin = fetchOne("SELECT id, username FROM websiteuser WHERE id = ?", [$_SESSION['admin_id']]);

if (!$admin) {
    session_destroy();
    header('Location: admin_login.php');
    exit;
}

// Fonction pour déconnecter
function logoutAdmin() {
    session_destroy();
    header('Location: admin_login.php');
    exit;
}

?>
