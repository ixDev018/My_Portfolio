<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'category',
        'title',
        'subtitle',
        'slug',
        'description',
        'body_content',
        'thumbnail_path',
        'media_type',
        'video_url',
        'tags',
        'client',
        'role',
        'year',
        'medium',
        'collaborators',
        'demo_url',
        'github_url',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];
}
