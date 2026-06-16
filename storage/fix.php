<?php
$file = __DIR__ . '/../resources/views/home.blade.php';
$content = file_get_contents($file);

// Replace Storage::url($var) with (Str::startsWith($var, 'http') ? $var : Storage::url($var))
// Only match variables ($[a-zA-Z0-9_->\[\]]+)
$content = preg_replace_callback(
    '/Storage::url\(\s*(\$[a-zA-Z0-9_\->\[\]]+)\s*\)/',
    function ($matches) {
        $var = $matches[1];
        return "(Str::startsWith($var, 'http') ? $var : Storage::url($var))";
    },
    $content
);

file_put_contents($file, $content);
echo "Fixed home.blade.php\n";

// Also check components
$compFile = __DIR__ . '/../resources/views/components/project-card.blade.php';
if (file_exists($compFile)) {
    $content = file_get_contents($compFile);
    $content = preg_replace_callback(
        '/Storage::url\(\s*(\$[a-zA-Z0-9_\->\[\]]+)\s*\)/',
        function ($matches) {
            $var = $matches[1];
            return "(Str::startsWith($var, 'http') ? $var : Storage::url($var))";
        },
        $content
    );
    file_put_contents($compFile, $content);
    echo "Fixed project-card.blade.php\n";
}
