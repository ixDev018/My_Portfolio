<?php

use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$localFiles = Storage::disk('public')->allFiles('');
$count = 0;

foreach ($localFiles as $path) {
    // Skip .gitignore
    if (str_ends_with($path, '.gitignore')) continue;
    
    if (!Storage::disk('google')->exists($path)) {
        echo "Uploading: " . $path . "\n";
        Storage::disk('google')->put($path, Storage::disk('public')->get($path));
        $count++;
    } else {
        echo "Skipping (already exists): " . $path . "\n";
    }
}

echo "Successfully uploaded $count files to Google Drive!\n";
