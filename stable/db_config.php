<?php

/**
 * Configuration SQLite Database
 * Connecte à la base de données SQLite
 */

// Chemin de la base de données
$db_path = __DIR__ . '/../BES-Hub/ketamine.db';

try {
    // Créer ou ouvrir la base de données SQLite
    $pdo = new PDO('sqlite:' . $db_path);
    
    // Configurer les modes d'erreur PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Activer les clés étrangères
    $pdo->exec('PRAGMA foreign_keys = ON');
    
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données: ' . $e->getMessage());
}

// Fonction utilitaire pour exécuter des requêtes
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        die('Erreur SQL: ' . $e->getMessage());
    }
}

// Fonction pour récupérer une seule ligne
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer toutes les lignes
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour insérer et récupérer l'ID
function insertAndGetId($sql, $params = []) {
    global $pdo;
    executeQuery($sql, $params);
    return $pdo->lastInsertId();
}
?>
