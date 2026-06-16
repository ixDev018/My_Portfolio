<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 */

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';

// Ensure the storage directory exists in /tmp
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    mkdir($storagePath.'/app', 0777, true);
    mkdir($storagePath.'/framework', 0777, true);
    mkdir($storagePath.'/framework/cache', 0777, true);
    mkdir($storagePath.'/framework/sessions', 0777, true);
    mkdir($storagePath.'/framework/views', 0777, true);
    mkdir($storagePath.'/logs', 0777, true);
}

$app->useStoragePath($storagePath);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
