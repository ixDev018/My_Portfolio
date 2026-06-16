<?php
$files = [
    __DIR__ . '/../resources/views/home.blade.php',
    __DIR__ . '/../resources/views/outputs.blade.php',
    __DIR__ . '/../resources/views/components/project-card.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Replace the previous fix:
    // (Str::startsWith($var, 'http') ? $var : Storage::url($var))
    // with:
    // (Str::startsWith($var, 'http') ? $var : ((Str::startsWith($var, 'images/') || Str::startsWith($var, 'videos/')) ? asset($var) : Storage::url($var)))
    
    $content = preg_replace_callback(
        '/\(Str::startsWith\(([^,]+), \'http\'\) \? \1 : Storage::url\(\1\)\)/',
        function ($matches) {
            $var = $matches[1];
            return "(Str::startsWith($var, 'http') ? $var : ((Str::startsWith($var, 'images/') || Str::startsWith($var, 'videos/')) ? asset($var) : Storage::url($var)))";
        },
        $content
    );

    file_put_contents($file, $content);
    echo "Fixed $file\n";
}
