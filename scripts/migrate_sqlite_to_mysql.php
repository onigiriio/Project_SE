<?php
// Simple SQLite -> MySQL migrator. Run from project root: php scripts/migrate_sqlite_to_mysql.php

$sqlitePath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($sqlitePath)) {
    echo "SQLite database not found at $sqlitePath\n";
    exit(1);
}
$sqlite = new PDO('sqlite:' . $sqlitePath);
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Load DB settings from .env if available
$envPath = __DIR__ . '/../.env';
$env = [];
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$k,$v] = explode('=', $line, 2);
        $env[trim($k)] = trim(trim($v), "\"'");
    }
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? 'laravel';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$mysqlDsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
echo "Connecting to MySQL at $host:$port database=$dbName user=$user\n";
$mysql = new PDO($mysqlDsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$tables = ['users','genres','books','book_genre','reviews','borrows'];

$mysql->exec('SET FOREIGN_KEY_CHECKS=0');

foreach ($tables as $table) {
    echo "Processing table: $table\n";
    // Check if table exists in MySQL
    try {
        $res = $mysql->query("SHOW TABLES LIKE '$table'")->fetchAll();
        if (empty($res)) {
            echo "  Skipping $table - table not found in MySQL.\n";
            continue;
        }
    } catch (Exception $e) {
        echo "  Error checking $table: " . $e->getMessage() . "\n";
        continue;
    }

    $rows = $sqlite->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        echo "  No rows to import for $table\n";
        continue;
    }

    // Prepare insert statement using column names
    $cols = array_keys($rows[0]);
    $colList = '`' . implode('`,`', $cols) . '`';
    $placeholders = implode(',', array_map(fn($c) => ':' . $c, $cols));
    $insertSql = "INSERT INTO `$table` ($colList) VALUES ($placeholders)";
    $stmt = $mysql->prepare($insertSql);

    $count = 0;
    foreach ($rows as $r) {
        // Skip if id exists already
        if (isset($r['id'])) {
            $exists = $mysql->prepare("SELECT 1 FROM `$table` WHERE `id` = ? LIMIT 1");
            $exists->execute([$r['id']]);
            if ($exists->fetch()) {
                continue; // skip
            }
        }

        // Normalize empty strings to null
        foreach ($r as $k => $v) {
            if ($v === '') $r[$k] = null;
        }

        // Ensure price not null if column exists
        if (in_array('price', $cols) && (!isset($r['price']) || $r['price'] === null)) {
            $r['price'] = 0.0;
        }

        // For boolean-like fields, convert 0/1 as needed (leave as-is)

        try {
            $stmt->execute($r);
            $count++;
        } catch (Exception $e) {
            echo "  Failed to import row id=" . ($r['id'] ?? 'n/a') . " : " . $e->getMessage() . "\n";
        }
    }

    // Reset auto-increment
    try {
        $maxIdRow = $mysql->query("SELECT MAX(id) AS m FROM `$table`")->fetch(PDO::FETCH_ASSOC);
        $maxId = $maxIdRow['m'] ?? 0;
        if ($maxId) {
            $mysql->exec("ALTER TABLE `$table` AUTO_INCREMENT = " . ($maxId + 1));
        }
    } catch (Exception $e) {
        // ignore
    }

    echo "  Imported $count rows into $table\n";
}

$mysql->exec('SET FOREIGN_KEY_CHECKS=1');

echo "Done.\n";
