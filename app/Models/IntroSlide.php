<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntroSlide extends Model
{
    protected $fillable = [
        'chapter_label',
        'title',
        'subtitle',
        'description',
        'image_path',
        'sort_order',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];
}
