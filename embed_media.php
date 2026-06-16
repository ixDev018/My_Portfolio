<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$filesToEmbed = [];

// Check Tools
foreach (App\Models\ToolItem::all() as $tool) {
    if ($tool->image_path) {
        $filesToEmbed[] = ['model' => clone $tool, 'type' => 'ToolItem', 'path' => $tool->image_path, 'field' => 'image_path'];
    }
}

// Check Skills
foreach (App\Models\Skill::all() as $skill) {
    if ($skill->image_path) {
        $filesToEmbed[] = ['model' => clone $skill, 'type' => 'Skill', 'path' => $skill->image_path, 'field' => 'image_path'];
    }
}

// Check Profile Hero Media
$profile = App\Models\Profile::first();
if ($profile) {
    if ($profile->hero_video_path) {
        $filesToEmbed[] = ['model' => clone $profile, 'type' => 'Profile', 'path' => $profile->hero_video_path, 'field' => 'hero_video_path'];
    }
    if ($profile->avatar_path) {
        $filesToEmbed[] = ['model' => clone $profile, 'type' => 'Profile', 'path' => $profile->avatar_path, 'field' => 'avatar_path'];
    }
}

$count = 0;
foreach ($filesToEmbed as $item) {
    $path = $item['path'];
    
    // Check if it exists in Google Drive
    if (Storage::disk('google')->exists($path)) {
        // We will move it to public/images/embedded/
        $filename = basename($path);
        $directory = dirname($path);
        
        $destDir = public_path('images/embedded/' . $directory);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        $destPath = $destDir . '/' . $filename;
        
        // Download from Google Drive to local embedded directory
        file_put_contents($destPath, Storage::disk('google')->get($path));
        
        // Update database to point to images/embedded/...
        $newPath = 'images/embedded/' . $directory . '/' . $filename;
        
        $model = $item['model'];
        $model->{$item['field']} = $newPath;
        $model->save();
        
        echo "Embedded {$item['type']} {$item['field']}: $newPath\n";
        $count++;
    } else {
        echo "File missing locally: $path\n";
    }
}

echo "Embedded $count files successfully.\n";
