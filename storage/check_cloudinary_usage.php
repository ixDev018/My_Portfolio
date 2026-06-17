<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $api = cloudinary()->adminApi();
    $usage = $api->usage();
    
    echo json_encode($usage, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo "Error checking usage: " . $e->getMessage() . "\n";
}
