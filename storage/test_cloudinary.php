<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $admin = app('cloudinary')->adminApi();
    $resources = $admin->assets(['max_results' => 10]);
    echo "Successfully connected. Found " . count($resources['resources']) . " resources.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
