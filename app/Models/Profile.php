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
        'location',
        'tool_rows',
    ];

    protected $casts = [
        'tool_rows' => 'array',
    ];
}
