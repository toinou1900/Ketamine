<?php

/**
 * Initialisation de la base de données SQLite
 * Crée les tables nécessaires
 * 
 * Utilisation: php db_init.php (depuis la ligne de commande)
 * Ou accéder directement: http://localhost/Ketamine/db_init.php
 */

require_once __DIR__ . '/db_config.php';

echo "<h2>Initialisation de la base de données Ketamine</h2>";

try {
    
    // Table: users (utilisateurs)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✓ Table 'users' créée/vérifiée</p>";

    // Table: contacts (messages de contact)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            subject TEXT NOT NULL,
            message TEXT NOT NULL,
            status TEXT DEFAULT 'unread',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✓ Table 'contacts' créée/vérifiée</p>";

    // Table: logs (historique)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            action TEXT NOT NULL,
            description TEXT,
            ip_address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "<p>✓ Table 'logs' créée/vérifiée</p>";

    // Table: medicines (inventaire de médicaments)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS medicines (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL,
            stock INTEGER DEFAULT 0,
            category TEXT,
            description TEXT,
            price REAL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✓ Table 'medicines' créée/vérifiée</p>";

    // Insérer les données d'inventaire initiales
    $medicines = [
        ['Doliprane', 150, 'Analgésique', 'Paracétamol - Soulage la fièvre et la douleur', 5.50],
        ['Ibuprofen', 120, 'Anti-inflammatoire', 'Ibuprofène - Réduit l\'inflammation et la douleur', 6.75],
        ['Aspirin', 200, 'Anticoagulant', 'Aspirine - Fluidifiant sanguin et antalgique', 3.99],
        ['Spasmalgon', 90, 'Antispasmodique', 'Phloroglucinol/Paracétamol - Soulage les spasmes', 7.25],
        ['Lamaline', 110, 'Analgésique', 'Paracétamol/Caféine - Analgésique avec caféine', 4.80],
        ['Actilyse', 45, 'Anticoagulant', 'Altéplase - Thrombolytique d\'urgence', 450.00],
        ['Augmentin', 85, 'Antibiotique', 'Amoxicilline/Acide clavulanique - Antibiotique', 8.90],
        ['Voltarène', 130, 'Anti-inflammatoire', 'Diclofénac - Anti-inflammatoire puissant', 9.50],
        ['Nurofen', 140, 'Anti-inflammatoire', 'Ibuprofène - Ibuprofène pour enfants et adultes', 6.25],
        ['Smecta', 160, 'Antidiarrhéique', 'Diosmectite - Traite la diarrhée', 5.10]
    ];

    // Vérifier si les données existent déjà
    $count = $pdo->query("SELECT COUNT(*) FROM medicines")->fetchColumn();
    
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO medicines (name, stock, category, description, price) VALUES (?, ?, ?, ?, ?)");
        foreach ($medicines as $medicine) {
            $stmt->execute($medicine);
        }
        echo "<p>✓ 10 médicaments insérés dans l'inventaire</p>";
    } else {
        echo "<p>ℹ Inventaire déjà rempli (" . $count . " médicaments)</p>";
    }

    // Créer un utilisateur admin par défaut
    $admin_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    if ($admin_count == 0) {
        // Créer les utilisateurs de test
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $user_password = password_hash('user123', PASSWORD_DEFAULT);
        
        insertAndGetId(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)",
            ['admin', 'admin@ketamine.local', $admin_password]
        );
        echo "<p>✓ Utilisateur admin créé (user: <code>admin</code> | pass: <code>admin123</code>)</p>";
        
        insertAndGetId(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)",
            ['user', 'user@ketamine.local', $user_password]
        );
        echo "<p>✓ Utilisateur test créé (user: <code>user</code> | pass: <code>user123</code>)</p>";
    } else {
        echo "<p>ℹ Utilisateurs déjà présents (" . $admin_count . " utilisateurs)</p>";
    }

    echo "<hr>";
    echo "<h3>Base de données initialisée avec succès! ✓</h3>";
    echo "<p><strong>Inventaire:</strong> " . count($medicines) . " médicaments disponibles</p>";
    echo "<p><strong>Admin Panel:</strong> <a href='admin_login.php'>Accéder à l'espace admin</a></p>";
    
} catch (PDOException $e) {
    echo "<h3>❌ Erreur lors de l'initialisation:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}

?>
