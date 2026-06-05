<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'tooltip_info',
        'category',
        'proficiency',
        'image_path',
    ];
}
