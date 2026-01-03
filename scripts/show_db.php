<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "config database.default = " . config('database.default') . PHP_EOL;
echo "env DB_CONNECTION = " . env('DB_CONNECTION') . PHP_EOL;
try {
    $connection = \DB::connection();
    echo "DB connection name: " . $connection->getName() . PHP_EOL;
    echo "DB database name: " . $connection->getDatabaseName() . PHP_EOL;
    echo "DB driver: " . $connection->getDriverName() . PHP_EOL;
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage() . PHP_EOL;
}
