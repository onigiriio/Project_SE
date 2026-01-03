<?php
$path = __DIR__ . '/../database/database.sqlite';
if (!file_exists($path)) {
    echo "SQLite file not found: $path\n";
    exit(1);
}
$db = new PDO('sqlite:' . $path);
$tables = [
    'users','genres','books','book_genre','reviews','borrows'
];
foreach ($tables as $t) {
    try {
        $stmt = $db->query("SELECT COUNT(*) as c FROM $t");
        $c = $stmt->fetch(PDO::FETCH_ASSOC)['c'];
        echo "$t: $c\n";
    } catch (Exception $e) {
        echo "$t: error (" . $e->getMessage() . ")\n";
    }
}
