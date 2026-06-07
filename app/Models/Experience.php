<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'company',
        'role',
        'duration',
        'description',
        'image_path',
        'sort_order',
        'body_content',
        'is_active',
        'bg_media_type',
        'bg_media_path',
        'bg_gallery_images',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'body_content' => 'array',
        'bg_gallery_images' => 'array',
    ];
}
