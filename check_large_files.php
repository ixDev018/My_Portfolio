<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$disk = Storage::disk('google');
$files = $disk->allFiles();
$tooBig = [];

foreach ($files as $file) {
    $size = $disk->size($file);
    if ($size > 100 * 1024 * 1024) { // 100MB limit
        $tooBig[] = ['path' => $file, 'size' => round($size / 1024 / 1024, 2) . ' MB'];
    }
}

if (empty($tooBig)) {
    echo "SUCCESS: All files are under 100MB!\n";
} else {
    echo "WARNING: The following files are too large for GitHub:\n";
    print_r($tooBig);
}
