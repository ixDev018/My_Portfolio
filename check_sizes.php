<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$files = Storage::disk('google')->files('projects/main_videos');
foreach ($files as $f) {
    echo $f . ': ' . round(Storage::disk('google')->size($f) / 1024 / 1024, 2) . " MB\n";
}
