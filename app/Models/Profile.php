<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'hero_top_text',
        'hero_title',
        'hero_subtitle',
        'avatar_path',
        'cv_path',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'email',
        'hero_video_path',
        'hero_blur_amount',
        'hero_html_content',
        'hero_gradient_enabled',
        'hero_gradient_type',
        'hero_gradient_angle',
        'hero_gradient_opacity',
        'hero_gradient_stops',
        'location',
        'tool_rows',
        'exp_default_bg_mode',
        'exp_default_bg_type',
        'exp_default_bg_media_path',
        'exp_default_bg_gallery_images',
        'disable_achievements_modal',
    ];

    protected $casts = [
        'hero_gradient_stops' => 'array',
        'tool_rows' => 'array',
        'exp_default_bg_gallery_images' => 'array',
        'disable_achievements_modal' => 'boolean',
    ];
}
