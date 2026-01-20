# 📊 Structure Base de Données SQLite

## Qu'est-ce que SQLite ?

SQLite est un système de gestion de base de données relationnelle léger qui ne nécessite pas de serveur dédié. Parfait pour les projets PHP petits/moyens et le développement local.

### SQL vs SQLite
- **SQL** = Langage pour interroger les BDD
- **SQLite** = Système de BDD qui utilise SQL (fichier autonome)

## 📁 Fichiers de Configuration

### 1. `db_config.php`
Fichier de **connexion** et **fonctions utilitaires**
- Crée la connexion PDO à SQLite
- Fournit des fonctions helper (fetchOne, fetchAll, etc.)

### 2. `db_init.php`
Script d'**initialisation** des tables
- À lancer une seule fois : `php db_init.php` (ou accéder via navigateur)
- Crée automatiquement toutes les tables

### 3. `db.sqlite`
La **base de données** (auto-créée après db_init.php)

## 📋 Tables Disponibles

### `users` - Utilisateurs
```sql
id (INT PK)
username (TEXT UNIQUE)
email (TEXT UNIQUE)
password (TEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### `contacts` - Messages de contact
```sql
id (INT PK)
name (TEXT)
email (TEXT)
subject (TEXT)
message (TEXT)
status (TEXT) - 'unread', 'read', 'replied'
created_at (TIMESTAMP)
```

### `logs` - Historique/Actions
```sql
id (INT PK)
user_id (INT FK) → users.id
action (TEXT) - type d'action
description (TEXT)
ip_address (TEXT)
created_at (TIMESTAMP)
```

### `statistics` - Statistiques Dashboard
```sql
id (INT PK)
drug_name (TEXT) - ex: "Doliprane"
count (INT)
updated_at (TIMESTAMP)
```

## 🚀 Démarrage Rapide

### 1️⃣ Initialiser la BDD
```bash
cd /workspaces/Ketamine
php db_init.php
```
Ou visiter : `http://localhost/Ketamine/db_init.php`

### 2️⃣ Utiliser dans tes pages PHP
```php
<?php
require_once 'db_config.php';

// Récupérer un contact
$contact = fetchOne("SELECT * FROM contacts WHERE id = ?", [1]);

// Récupérer tous les contacts
$contacts = fetchAll("SELECT * FROM contacts ORDER BY created_at DESC");

// Insérer et récupérer l'ID
$id = insertAndGetId(
    "INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)",
    ['Jean', 'jean@mail.com', 'Test', 'Hello']
);

// Exécuter une requête
executeQuery("UPDATE statistics SET count = count + 1 WHERE drug_name = ?", ['Doliprane']);
?>
```

## 📝 Exemples d'Utilisation

### Ajouter un contact (depuis contact.php)
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    insertAndGetId(
        "INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)",
        [$_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message']]
    );
    echo "Message envoyé!";
}
```

### Afficher les statistiques (pour dashboard.php)
```php
$stats = fetchAll("SELECT * FROM statistics");
foreach ($stats as $stat) {
    echo "<li>" . $stat['drug_name'] . ": " . $stat['count'] . "</li>";
}
```

## 🔐 Sécurité

✓ Utilise **prepared statements** (protection contre les injections SQL)
✓ Clés étrangères activées
✓ Timestamps automatiques

## 📚 Ressources
- [Documentation PDO PHP](https://www.php.net/manual/fr/book.pdo.php)
- [SQLite Official](https://www.sqlite.org/)
