<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// login as user id 1
Auth::loginUsingId(1);

$controller = new BookController();
$request = Request::create('/catalogue', 'GET');
$response = $controller->catalogue($request);

// $response is a View
if (method_exists($response, 'render')) {
    $html = $response->render();
    if (strpos($html, 'The Great Gatsby') !== false) {
        echo "Found title 'The Great Gatsby' in rendered HTML\n";
    } else {
        echo "Title not found in rendered HTML\n";
        // dump first 2000 chars for inspection
        echo substr(strip_tags($html), 0, 2000) . PHP_EOL;
    }
}
