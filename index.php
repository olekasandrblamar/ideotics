<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__ . '/Application/storage/framework/maintenance.php')) {
    require __DIR__ . '/Application/storage/framework/maintenance.php';
}

require __DIR__ . '/Application/vendor/autoload.php';

$app = require_once __DIR__ . '/Application/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
