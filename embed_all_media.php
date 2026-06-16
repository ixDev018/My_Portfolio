<?php
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$disk = Storage::disk('google');

// Helper to embed a single file path
function embedFile($path) {
    global $disk;
    if (empty($path)) return $path;
    if (str_starts_with($path, 'http')) return $path;
    if (str_starts_with($path, 'images/embedded/')) return $path;

    if ($disk->exists($path)) {
        $filename = basename($path);
        $directory = dirname($path);
        $destDir = public_path('images/embedded/' . ltrim($directory, '/'));
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $destPath = $destDir . '/' . $filename;
        
        if (!file_exists($destPath)) {
            echo "Downloading: $path...\n";
            file_put_contents($destPath, $disk->get($path));
        }
        return 'images/embedded/' . ltrim($directory, '/') . '/' . $filename;
    }
    
    return $path;
}

// Helper to embed an array of file paths
function embedArray($pathsArray) {
    if (!is_array($pathsArray)) return $pathsArray;
    $newArray = [];
    foreach ($pathsArray as $path) {
        $newArray[] = embedFile($path);
    }
    return $newArray;
}

// Helper to parse and embed body_content JSON blocks
function embedBodyContent($bodyContentJson) {
    if (empty($bodyContentJson)) return $bodyContentJson;
    
    $isString = is_string($bodyContentJson);
    $blocks = $isString ? json_decode($bodyContentJson, true) : $bodyContentJson;
    
    if (!is_array($blocks)) return $bodyContentJson;

    $changed = false;
    foreach ($blocks as &$block) {
        if (in_array($block['type'] ?? '', ['image', 'video'])) {
            if (!empty($block['src'])) {
                $newSrc = embedFile($block['src']);
                if ($newSrc !== $block['src']) {
                    $block['src'] = $newSrc;
                    $changed = true;
                }
            }
        }
    }
    
    if (!$changed) return $bodyContentJson;
    return $isString ? json_encode($blocks) : $blocks;
}

// 1. PROJECTS
echo "\n--- Processing Projects ---\n";
foreach (App\Models\Project::all() as $project) {
    $project->thumbnail_path = embedFile($project->thumbnail_path);
    $project->main_image_path = embedFile($project->main_image_path);
    $project->main_video_path = embedFile($project->main_video_path);
    $project->thumbnail_video_path = embedFile($project->thumbnail_video_path);
    
    $project->thumbnail_images = embedArray($project->thumbnail_images);
    $project->main_images = embedArray($project->main_images);
    $project->gallery_images = embedArray($project->gallery_images);
    
    $project->body_content = embedBodyContent($project->body_content);
    
    if ($project->isDirty()) {
        $project->save();
        echo "Updated Project: {$project->title}\n";
    }
}

// 2. EXPERIENCES
echo "\n--- Processing Experiences ---\n";
foreach (App\Models\Experience::all() as $exp) {
    $exp->image_path = embedFile($exp->image_path);
    $exp->bg_media_path = embedFile($exp->bg_media_path);
    $exp->bg_gallery_images = embedArray($exp->bg_gallery_images);
    $exp->body_content = embedBodyContent($exp->body_content);
    
    if ($exp->isDirty()) {
        $exp->save();
        echo "Updated Experience: {$exp->company}\n";
    }
}

// 3. PROFILE
echo "\n--- Processing Profile ---\n";
$profile = App\Models\Profile::first();
if ($profile) {
    $profile->avatar_path = embedFile($profile->avatar_path);
    $profile->cv_path = embedFile($profile->cv_path);
    $profile->hero_video_path = embedFile($profile->hero_video_path);
    $profile->exp_default_bg_media_path = embedFile($profile->exp_default_bg_media_path);
    $profile->exp_default_bg_gallery_images = embedArray($profile->exp_default_bg_gallery_images);
    
    if ($profile->isDirty()) {
        $profile->save();
        echo "Updated Profile Settings\n";
    }
}

echo "\n--- Migration Complete ---\n";
