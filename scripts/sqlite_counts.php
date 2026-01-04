<?php
require __DIR__ . '/../vendor/autoload.php';
use Illuminate\Support\Facades\DB;

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = [
    'users','genres','books','book_genre','reviews','borrows'
];
foreach ($tables as $t) {
    try {
        $c = DB::table($t)->count();
        echo "$t: $c\n";
    } catch (Exception $e) {
        echo "$t: error (" . $e->getMessage() . ")\n";
    }
}
