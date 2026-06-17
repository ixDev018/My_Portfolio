<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\ToolItem;
use App\Models\Experience;
use App\Models\Achievement;
use App\Models\IntroSlide;

echo "--- Local Media Analysis ---\n";

$count = 0;

$profile = Profile::first();
if ($profile) {
    if ($profile->avatar_path && !str_starts_with($profile->avatar_path, 'http')) { echo "Profile Avatar: " . $profile->avatar_path . "\n"; $count++; }
    if ($profile->cv_path && !str_starts_with($profile->cv_path, 'http')) { echo "Profile CV: " . $profile->cv_path . "\n"; $count++; }
    if ($profile->hero_video_path && !str_starts_with($profile->hero_video_path, 'http')) { echo "Profile Hero Video: " . $profile->hero_video_path . "\n"; $count++; }
    if ($profile->exp_default_bg_media_path && !str_starts_with($profile->exp_default_bg_media_path, 'http')) { echo "Profile Exp BG: " . $profile->exp_default_bg_media_path . "\n"; $count++; }
}

$projects = Project::all();
foreach ($projects as $project) {
    if ($project->main_image_path && !str_starts_with($project->main_image_path, 'http')) { echo "Project Main Img: " . $project->main_image_path . "\n"; $count++; }
    if ($project->main_video_path && !str_starts_with($project->main_video_path, 'http')) { echo "Project Main Vid: " . $project->main_video_path . "\n"; $count++; }
    if ($project->featured_thumbnail && !str_starts_with($project->featured_thumbnail, 'http')) { echo "Project Thumb: " . $project->featured_thumbnail . "\n"; $count++; }
    if (is_array($project->gallery_images)) {
        foreach ($project->gallery_images as $img) {
            if (!str_starts_with($img, 'http')) { echo "Project Gallery: " . $img . "\n"; $count++; }
        }
    }
}

echo "Total Local Media Files Found: " . $count . "\n";
