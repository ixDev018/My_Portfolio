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
use Illuminate\Support\Str;

echo "Starting Cloudinary Migration...\n\n";

function uploadAndReplace($path, $folder) {
    if (empty($path)) return $path;
    if (str_starts_with($path, 'http')) return $path; // Already uploaded

    // Locate file
    $realPath = public_path($path);
    if (!file_exists($realPath)) {
        $realPath = storage_path('app/public/' . $path);
    }
    
    if (!file_exists($realPath)) {
        echo "  [WARNING] File not found locally: $path\n";
        return $path; // Return original if not found
    }

    echo "  [UPLOADING] $path -> portfolio/$folder ... ";
    
    try {
        $uploaded = cloudinary()->uploadApi()->upload(
            $realPath,
            ['folder' => "portfolio/{$folder}", 'resource_type' => 'auto']
        );
        echo "SUCCESS\n";
        return $uploaded['secure_url'];
    } catch (\Exception $e) {
        echo "FAILED (" . $e->getMessage() . ")\n";
        return $path;
    }
}

// 1. Profile
echo "Processing Profile...\n";
$profile = Profile::first();
if ($profile) {
    $updated = false;
    
    $newAvatar = uploadAndReplace($profile->avatar_path, 'profiles/avatars');
    if ($newAvatar !== $profile->avatar_path) { $profile->avatar_path = $newAvatar; $updated = true; }
    
    $newCv = uploadAndReplace($profile->cv_path, 'profiles/documents');
    if ($newCv !== $profile->cv_path) { $profile->cv_path = $newCv; $updated = true; }
    
    $newVideo = uploadAndReplace($profile->hero_video_path, 'profiles/hero_videos');
    if ($newVideo !== $profile->hero_video_path) { $profile->hero_video_path = $newVideo; $updated = true; }
    
    $newExpBg = uploadAndReplace($profile->exp_default_bg_media_path, 'profiles/exp_bg');
    if ($newExpBg !== $profile->exp_default_bg_media_path) { $profile->exp_default_bg_media_path = $newExpBg; $updated = true; }
    
    // Arrays
    if (is_array($profile->exp_default_bg_gallery_images)) {
        $newGallery = [];
        foreach ($profile->exp_default_bg_gallery_images as $img) {
            $newGallery[] = uploadAndReplace($img, 'profiles/exp_bg_gallery');
        }
        if ($newGallery !== $profile->exp_default_bg_gallery_images) {
            $profile->exp_default_bg_gallery_images = $newGallery;
            $updated = true;
        }
    }
    
    if ($updated) {
        $profile->save();
        echo "  -> Profile updated in DB.\n";
    }
}

// 2. Projects
echo "\nProcessing Projects...\n";
$projects = Project::all();
foreach ($projects as $project) {
    echo "  - Project ID: {$project->id}\n";
    $updated = false;
    
    $newMainImg = uploadAndReplace($project->main_image_path, 'projects/main_images');
    if ($newMainImg !== $project->main_image_path) { $project->main_image_path = $newMainImg; $updated = true; }
    
    $newMainVid = uploadAndReplace($project->main_video_path, 'projects/main_videos');
    if ($newMainVid !== $project->main_video_path) { $project->main_video_path = $newMainVid; $updated = true; }
    
    $newThumb = uploadAndReplace($project->thumbnail_path, 'projects/featured_thumbnails');
    if ($newThumb !== $project->thumbnail_path) { $project->thumbnail_path = $newThumb; $updated = true; }

    $newThumbVid = uploadAndReplace($project->thumbnail_video_path, 'projects/featured_thumbnails');
    if ($newThumbVid !== $project->thumbnail_video_path) { $project->thumbnail_video_path = $newThumbVid; $updated = true; }

    $newFeatThumb = uploadAndReplace($project->featured_thumbnail, 'projects/featured_thumbnails');
    if ($newFeatThumb !== $project->featured_thumbnail) { $project->featured_thumbnail = $newFeatThumb; $updated = true; }

    if (is_array($project->gallery_images)) {
        $newGallery = [];
        foreach ($project->gallery_images as $img) {
            $newGallery[] = uploadAndReplace($img, 'projects/gallery');
        }
        if ($newGallery !== $project->gallery_images) {
            $project->gallery_images = $newGallery;
            $updated = true;
        }
    }

    if (is_array($project->main_images)) {
        $newMainImages = [];
        foreach ($project->main_images as $img) {
            $newMainImages[] = uploadAndReplace($img, 'projects/main_images');
        }
        if ($newMainImages !== $project->main_images) {
            $project->main_images = $newMainImages;
            $updated = true;
        }
    }
    
    if ($updated) {
        $project->save();
        echo "    -> DB Updated for Project ID: {$project->id}\n";
    }
}

// 3. Experiences
echo "\nProcessing Experiences...\n";
$experiences = Experience::all();
foreach ($experiences as $exp) {
    $updated = false;
    
    $newImg = uploadAndReplace($exp->image_path, 'experiences');
    if ($newImg !== $exp->image_path) { $exp->image_path = $newImg; $updated = true; }
    
    $newBg = uploadAndReplace($exp->bg_media_path, 'experiences/bg');
    if ($newBg !== $exp->bg_media_path) { $exp->bg_media_path = $newBg; $updated = true; }
    
    if (is_array($exp->bg_gallery_images)) {
        $newGallery = [];
        foreach ($exp->bg_gallery_images as $img) {
            $newGallery[] = uploadAndReplace($img, 'experiences/bg_gallery');
        }
        if ($newGallery !== $exp->bg_gallery_images) {
            $exp->bg_gallery_images = $newGallery;
            $updated = true;
        }
    }
    
    if ($updated) {
        $exp->save();
        echo "  -> DB Updated for Experience ID: {$exp->id}\n";
    }
}

// 4. Achievements
echo "\nProcessing Achievements...\n";
$achievements = Achievement::all();
foreach ($achievements as $ach) {
    $updated = false;
    $newImg = uploadAndReplace($ach->media_path, 'achievements');
    if ($newImg !== $ach->media_path) { $ach->media_path = $newImg; $updated = true; }
    if ($updated) { $ach->save(); echo "  -> DB Updated for Achievement ID: {$ach->id}\n"; }
}

// 5. IntroSlides
echo "\nProcessing IntroSlides...\n";
$slides = IntroSlide::all();
foreach ($slides as $slide) {
    $updated = false;
    $newImg = uploadAndReplace($slide->image_path, 'intro_slides');
    if ($newImg !== $slide->image_path) { $slide->image_path = $newImg; $updated = true; }
    if ($updated) { $slide->save(); echo "  -> DB Updated for IntroSlide ID: {$slide->id}\n"; }
}

// 6. Skills
echo "\nProcessing Skills...\n";
$skills = Skill::all();
foreach ($skills as $skill) {
    $updated = false;
    $newImg = uploadAndReplace($skill->image_path, 'skills');
    if ($newImg !== $skill->image_path) { $skill->image_path = $newImg; $updated = true; }
    if ($updated) { $skill->save(); echo "  -> DB Updated for Skill ID: {$skill->id}\n"; }
}

// 7. ToolItems
echo "\nProcessing ToolItems...\n";
$tools = ToolItem::all();
foreach ($tools as $tool) {
    $updated = false;
    $newImg = uploadAndReplace($tool->image_path, 'tools');
    if ($newImg !== $tool->image_path) { $tool->image_path = $newImg; $updated = true; }
    if ($updated) { $tool->save(); echo "  -> DB Updated for ToolItem ID: {$tool->id}\n"; }
}

echo "\nMigration Script Finished!\n";
