<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'tags',
        'demo_url',
        'github_url',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];
}
