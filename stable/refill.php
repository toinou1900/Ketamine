<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php?refilled=0');
    exit;
}

$ids = $_POST['ids'] ?? [];
if (!is_array($ids) || count($ids) === 0) {
    header('Location: dashboard.php?refilled=0');
    exit;
}

$ids_filtered = array_map('intval', $ids);
$placeholders = implode(',', array_fill(0, count($ids_filtered), '?'));

$sql = "UPDATE medicines SET stock = 100 WHERE id IN ($placeholders)";
$stmt = executeQuery($sql, $ids_filtered);
$affected = $stmt->rowCount();

header('Location: dashboard.php?refilled=' . (int)$affected);
exit;

?>
