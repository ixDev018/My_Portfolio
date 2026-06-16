<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dirs = ['achievements', 'documents', 'experiences', 'intro_slides', 'profiles', 'projects', 'skills', 'tools', 'videos'];
foreach ($dirs as $dir) {
    $size = 0;
    foreach (Storage::disk('local')->allFiles('public/' . $dir) as $f) {
        $size += Storage::disk('local')->size($f);
    }
    echo $dir . ': ' . round($size / 1024 / 1024, 2) . " MB\n";
}
