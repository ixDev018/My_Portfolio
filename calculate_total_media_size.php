<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$disk = Storage::disk('google');
$totalSize = 0;
$fileCount = 0;

$directoriesToCheck = [
    'projects',
    'experiences',
    'profiles',
    'documents'
];

echo "Calculating total size of media in Google Drive...\n";

foreach ($directoriesToCheck as $dir) {
    try {
        $files = $disk->allFiles($dir);
        foreach ($files as $file) {
            $size = $disk->size($file);
            $totalSize += $size;
            $fileCount++;
        }
        echo "Checked directory: $dir\n";
    } catch (\Exception $e) {
        echo "Error checking directory $dir: " . $e->getMessage() . "\n";
    }
}

$sizeInMB = round($totalSize / 1024 / 1024, 2);
$sizeInGB = round($totalSize / 1024 / 1024 / 1024, 4);

echo "\n--- Results ---\n";
echo "Total files: $fileCount\n";
echo "Total size: $sizeInMB MB ($sizeInGB GB)\n";

