<?php
$file = 'app/Http/Controllers/AdminController.php';
$content = file_get_contents($file);

// Replace delete calls
$content = preg_replace(
    "/Storage::disk\(config\('filesystems\.default'\)\)->delete\((.*?)\);/",
    "if (!Str::startsWith($1, 'http')) { Storage::disk(config('filesystems.default'))->delete($1); }",
    $content
);

// We need to replace puts!
// Instead of simple preg_replace, let's write a helper function to top of AdminController class.
$helper = '
    protected function deleteMedia($path) {
        if (!$path) return;
        if (Str::startsWith($path, \'http\')) return;
        Storage::disk(config(\'filesystems.default\'))->delete($path);
    }

    protected function uploadMedia($data, $folder) {
        try {
            $uploaded = cloudinary()->upload($data, [\'folder\' => "portfolio/{$folder}"]);
            return $uploaded->getSecurePath();
        } catch (\Exception $e) {
            \Log::error("Cloudinary upload failed: " . $e->getMessage());
            return "";
        }
    }
';

// Insert helper methods at the start of the class
if (strpos($content, 'protected function deleteMedia') === false) {
    $content = preg_replace('/class AdminController extends Controller\s*\{/', "class AdminController extends Controller\n{\n$helper", $content);
}

// Replace deletes
$content = preg_replace(
    "/if\s*\((.*?)\)\s*Storage::disk\(config\('filesystems\.default'\)\)->delete\((.*?)\);/",
    '$this->deleteMedia($2);',
    $content
);
$content = preg_replace(
    "/Storage::disk\(config\('filesystems\.default'\)\)->delete\((.*?)\);/",
    '$this->deleteMedia($1);',
    $content
);

// Replace PUTs (this is trickier, because it's generating file names and then assigning them.
// Currently:
// $fileName = 'skill_' . time() . '_' . uniqid() . '.png';
// $imagePath = 'skills/' . $fileName;
// Storage::disk(config('filesystems.default'))->put($imagePath, $imageBase64);
// $skill->image_path = $imagePath;
//
// We want to just:
// $skill->image_path = $this->uploadMedia("data:image/png;base64," . base64_encode($imageBase64), 'skills');
// But base64 images from cropper usually ALREADY have the data uri part!
// Let's inspect the code for base64 uploads.

file_put_contents($file, $content);
echo "Done replacing deletes!\n";
