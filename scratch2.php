<?php
$file = 'app/Http/Controllers/AdminController.php';
$content = file_get_contents($file);

// Update the uploadMedia method
$newHelper = '
    protected function deleteMedia($path) {
        if (!$path) return;
        if (Str::startsWith($path, \'http\')) return;
        Storage::disk(config(\'filesystems.default\'))->delete($path);
    }

    protected function uploadMedia($data, $folder) {
        try {
            if ($data instanceof \Illuminate\Http\UploadedFile) {
                $uploaded = cloudinary()->upload($data->getRealPath(), [\'folder\' => "portfolio/{$folder}"]);
                return $uploaded->getSecurePath();
            } else if (is_string($data) && preg_match(\'/^data:image\/(\w+);base64,/\', $data)) {
                $uploaded = cloudinary()->upload($data, [\'folder\' => "portfolio/{$folder}"]);
                return $uploaded->getSecurePath();
            } else {
                return "";
            }
        } catch (\Exception $e) {
            \Log::error("Cloudinary upload failed: " . $e->getMessage());
            return "";
        }
    }
';

$content = preg_replace('/protected function deleteMedia\(.*?\}\s*protected function uploadMedia\(.*?\}\s*/s', $newHelper, $content);

// Now let's replace base64 puts.
// In projectsStore and projectsUpdate:
// if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) { ... }
// We can manually replace these using regex, but it's simpler to do them specifically.
// We have lines like:
// $data = substr($base64, strpos($base64, ',') + 1);
// $type = strtolower($type[1]);
// $data = base64_decode($data);
// $filename = 'projects/main_images/' . uniqid() . '.' . $type;
// Storage::disk(config('filesystems.default'))->put($filename, $data);
// $validated['main_image_path'] = $filename;
// Let's replace the content block:
$content = preg_replace(
    '/\$data = substr\(\$base64.*?Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$filename, \$data\);\s*\$validated\[\'main_image_path\'\] = \$filename;/s',
    '$validated[\'main_image_path\'] = $this->uploadMedia($base64, \'projects/main_images\');',
    $content
);

$content = preg_replace(
    '/\$data = substr\(\$base64.*?Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$filename, \$data\);\s*\$validated\[\'custom_thumbnail_path\'\] = \$filename;/s',
    '$validated[\'custom_thumbnail_path\'] = $this->uploadMedia($base64, \'projects/thumbnails\');',
    $content
);

$content = preg_replace(
    '/\$data = substr\(\$base64.*?Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$filename, \$data\);\s*\$validated\[\'featured_thumbnail\'\] = \$filename;/s',
    '$validated[\'featured_thumbnail\'] = $this->uploadMedia($base64, \'projects/featured_thumbnails\');',
    $content
);

// Now for $files[0]->store(...)
$content = preg_replace(
    '/\$validated\[\'([^\']+)\'\] = \$files\[0\]->store\(\'([^\']+)\', config\(\'filesystems\.default\'\)\);/',
    '$validated[\'$1\'] = $this->uploadMedia($files[0], \'$2\');',
    $content
);
$content = preg_replace(
    '/\$paths\[\] = \$f->store\(\'([^\']+)\', config\(\'filesystems\.default\'\)\);/',
    '$paths[] = $this->uploadMedia($f, \'$1\');',
    $content
);
// For profile avatars:
$content = preg_replace(
    '/\$avatarPath = \$request->file\(\'avatar\'\)->store\(\'avatars\', config\(\'filesystems\.default\'\)\);/',
    '$avatarPath = $this->uploadMedia($request->file(\'avatar\'), \'avatars\');',
    $content
);
$content = preg_replace(
    '/\$cvPath = \$request->file\(\'cv\'\)->store\(\'cvs\', config\(\'filesystems\.default\'\)\);/',
    '$cvPath = $this->uploadMedia($request->file(\'cv\'), \'cvs\');',
    $content
);
$content = preg_replace(
    '/\$heroVideoPath = \$request->file\(\'hero_video\'\)->store\(\'hero_videos\', config\(\'filesystems\.default\'\)\);/',
    '$heroVideoPath = $this->uploadMedia($request->file(\'hero_video\'), \'hero_videos\');',
    $content
);

// For inline media upload:
$content = preg_replace(
    '/\$path = \$request->file\(\'file\'\)->store\(\'body_media\', config\(\'filesystems\.default\'\)\);/',
    '$path = $this->uploadMedia($request->file(\'file\'), \'body_media\');',
    $content
);

// Now for skills and tools which use $imageParts = explode(";base64,", $validated['image_data']);
// ... Storage::disk(config('filesystems.default'))->put($imagePath, $imageBase64); ...
$skillToolRegex = '/\$imageParts = explode\("(?:\;base64\,)", \$validated\[\'image_data\'\]\);\s*if \(count\(\$imageParts\) == 2\) \{.*?Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$imagePath, \$imageBase64\);\s*\$this->deleteMedia\((\$\w+)->image_path\);\s*\1->image_path = \$imagePath;\s*\}/s';

$content = preg_replace_callback($skillToolRegex, function($matches) {
    return '$1->image_path = $this->uploadMedia($validated[\'image_data\'], \'icons\');';
}, $content);

// Wait, the regex for skill/tool insert might be slightly different.
// I will just use simple str_replace since it is cleaner or I can write a regex for it.

file_put_contents($file, $content);
echo "Done refactoring put calls!\n";
