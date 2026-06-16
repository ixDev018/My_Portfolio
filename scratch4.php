<?php
$files = [
    'resources/views/home.blade.php',
    'resources/views/outputs.blade.php',
    'resources/views/project-show.blade.php',
    'resources/views/admin/projects/partials/media_upload.blade.php',
    'resources/views/admin/skills/index.blade.php',
    'resources/views/admin/tools/index.blade.php',
    'resources/views/admin/experiences/index.blade.php',
    'resources/views/admin/experiences/edit.blade.php',
    'resources/views/admin/intro_slides/index.blade.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Replace Storage::url($variable) with (Str::startsWith($variable, 'http') ? $variable : Storage::url($variable))
    // We only want to replace it where it isn't already inside a Str::startsWith
    
    // Simple logic: we'll match Storage::url($abc)
    // To prevent double replacing, first we can do a dummy replace or just check if it's already safe.
    
    // Actually, looking at the code, sometimes it's Storage::url($item['image_path']), sometimes $proj->thumbnail_images[0]
    $content = preg_replace_callback('/Storage::url\(([^)]+)\)/', function($matches) {
        $var = $matches[1];
        // If it's a hardcoded string like Storage::url('path'), ignore.
        if (strpos($var, "'") !== false && strpos($var, "$") === false) return $matches[0];
        
        return "(Str::startsWith($var, 'http') ? $var : Storage::url($var))";
    }, $content);

    // Some existing Str::startsWith checks might now look like:
    // Str::startsWith($var, 'http') ? $var : (Str::startsWith($var, 'http') ? $var : Storage::url($var))
    // This is syntactically fine and works, just a bit redundant.
    
    // We should also replace asset('storage/' . $variable) 
    $content = preg_replace_callback('/asset\(\'storage\/\'\s*\.\s*([^)]+)\)/', function($matches) {
        $var = $matches[1];
        return "(Str::startsWith($var, 'http') ? $var : asset('storage/' . $var))";
    }, $content);

    file_put_contents($file, $content);
}
echo "Done replacing URLs!\n";
