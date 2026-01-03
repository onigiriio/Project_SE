<?php
$env = parse_ini_file(__DIR__ . '/../.env', false, INI_SCANNER_RAW);
$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? 'Project_SE';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->query('SELECT COUNT(*) as c FROM `books`');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "PDO books count: " . ($row['c'] ?? 'n/a') . PHP_EOL;
$stmt = $pdo->query('SELECT id,title FROM books ORDER BY id LIMIT 5');
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "id={$r['id']} title={$r['title']}\n";
}
