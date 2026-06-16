<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$filesToEmbed = [];

// Check IntroSlides
foreach (App\Models\IntroSlide::all() as $slide) {
    if ($slide->image_path && !str_starts_with($slide->image_path, 'images/embedded/')) {
        $filesToEmbed[] = [
            'model' => $slide,
            'type' => 'IntroSlide',
            'path' => $slide->image_path,
            'field' => 'image_path'
        ];
    }
}

$count = 0;
foreach ($filesToEmbed as $item) {
    $path = $item['path'];
    
    // Check if it exists in Google Drive
    if (Storage::disk('google')->exists($path)) {
        $filename = basename($path);
        $directory = dirname($path);
        
        $destDir = public_path('images/embedded/' . $directory);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        $destPath = $destDir . '/' . $filename;
        
        echo "Downloading $path...\n";
        $fileContents = Storage::disk('google')->get($path);
        file_put_contents($destPath, $fileContents);
        
        $newPath = 'images/embedded/' . $directory . '/' . $filename;
        
        $model = $item['model'];
        $model->{$item['field']} = $newPath;
        $model->save();
        
        echo "Embedded {$item['type']} ID {$model->id} {$item['field']}: $newPath\n";
        $count++;
    } else {
        echo "File not found on Google Drive: $path\n";
    }
}

echo "Embedded $count files successfully.\n";
