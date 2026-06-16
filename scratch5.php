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

    // Replace Storage::url($var) fallback with a check for images/embedded/
    // The current pattern is: (Str::startsWith($var, 'http') ? $var : Storage::url($var))
    
    // Let's use a regex to match: (Str::startsWith($var, 'http') ? $var : Storage::url($var))
    // And replace it with: (Str::startsWith($var, 'http') ? $var : (Str::startsWith($var, 'images/embedded/') ? asset($var) : Storage::url($var)))
    
    $content = preg_replace_callback('/\(Str::startsWith\(([^,]+), \'http\'\) \? \1 : Storage::url\(\1\)\)/', function($m) {
        $var = $m[1];
        return "(Str::startsWith($var, 'http') ? $var : (Str::startsWith($var, 'images/embedded/') ? asset($var) : Storage::url($var)))";
    }, $content);

    // Also fix the asset('storage/' . $var) fallback
    $content = preg_replace_callback('/\(Str::startsWith\(([^,]+), \'http\'\) \? \1 : asset\(\'storage\/\' \. \1\)\)/', function($m) {
        $var = $m[1];
        return "(Str::startsWith($var, 'http') ? $var : (Str::startsWith($var, 'images/embedded/') ? asset($var) : asset('storage/' . $var)))";
    }, $content);

    file_put_contents($file, $content);
}
echo "Done fixing embedded paths!\n";
