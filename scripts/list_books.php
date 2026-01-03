<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$books = \App\Models\Book::orderBy('id')->get();
echo "Total books: " . $books->count() . PHP_EOL;
foreach ($books as $b) {
    echo sprintf("id=%d title=%s price=%s published=%s\n", $b->id, $b->title, (string)$b->price, (string)$b->published_date);
}
