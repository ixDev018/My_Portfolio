<?php
$file = 'config/filesystems.php';
$content = file_get_contents($file);

if (strpos($content, "'cloudinary' => [") === false) {
    $cloudinaryDisk = "
        'cloudinary' => [
            'driver' => 'cloudinary',
            'url' => env('CLOUDINARY_URL'),
        ],
";
    $content = preg_replace("/'disks'\s*=>\s*\[/", "'disks' => [" . $cloudinaryDisk, $content);
    file_put_contents($file, $content);
    echo "Cloudinary disk added!\n";
} else {
    echo "Cloudinary disk already exists.\n";
}
