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
        'thumbnail_type',
        'thumbnail_images',
        'thumbnail_video_path',
        'main_media_type',
        'main_video_path',
        'main_images',
        'main_image_path',
        'use_custom_thumbnail',
        'video_loop_start',
        'video_loop_end',
        'full_video_url',
        'media_type',
        'video_url',
        'tags',
        'client',
        'role',
        'year',
        'date_published',
        'medium',
        'collaborators',
        'demo_url',
        'github_url',
        'featured',
        'is_best_work',
        'featured_thumbnail',
        'gallery_images',
    ];

    protected $casts = [
        'featured'        => 'boolean',
        'is_best_work'    => 'boolean',
        'use_custom_thumbnail' => 'boolean',
        'gallery_images'  => 'array',
        'thumbnail_images'=> 'array',
        'main_images'     => 'array',
        'video_loop_start'=> 'float',
        'video_loop_end'  => 'float',
    ];
}
