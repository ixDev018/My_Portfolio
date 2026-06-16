<?php
$file = 'app/Http/Controllers/AdminController.php';
$content = file_get_contents($file);

// Replace lines 600-608 (Skill Store)
$content = preg_replace(
    '/if \(count\(\$imageParts\) == 2\) \{.*?Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$imagePath, \$imageBase64\);\s*\}/s',
    'if (count($imageParts) == 2) { $imagePath = $this->uploadMedia($validated[\'image_data\'], \'skills\'); }',
    $content
);

// Replace lines 635-647 (Skill Update)
$content = preg_replace(
    '/if \(count\(\$imageParts\) == 2\) \{.*?Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$imagePath, \$imageBase64\);\s*if \(\$skill->image_path\).*?\$skill->image_path = \$imagePath;\s*\}/s',
    'if (count($imageParts) == 2) { if ($skill->image_path) { $this->deleteMedia($skill->image_path); } $skill->image_path = $this->uploadMedia($validated[\'image_data\'], \'skills\'); }',
    $content
);

// Replace lines 719-724 (Tool Store)
$content = preg_replace(
    '/\$data = preg_replace\(\'\/\^data\:image[^;]+;base64,\/\', \'\', \$validated\[\'image_data\'\]\);\s*\$data = base64_decode\(\$data\);\s*\$filename = \'tools\/\' \. Str::uuid\(\) \. \'\.png\';\s*Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$filename, \$data\);\s*\$imagePath = \$filename;/s',
    '$imagePath = $this->uploadMedia($validated[\'image_data\'], \'tools\');',
    $content
);

// Replace lines 770-775 (Tool Update)
$content = preg_replace(
    '/\$data = preg_replace\(\'\/\^data\:image[^;]+;base64,\/\', \'\', \$validated\[\'image_data\'\]\);\s*\$data = base64_decode\(\$data\);\s*\$filename = \'tools\/\' \. Str::uuid\(\) \. \'\.png\';\s*Storage::disk\(config\(\'filesystems\.default\'\)\)->put\(\$filename, \$data\);\s*\$tool->image_path = \$filename;/s',
    '$tool->image_path = $this->uploadMedia($validated[\'image_data\'], \'tools\');',
    $content
);

file_put_contents($file, $content);
echo "Done scratch3!";
