<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'title',
        'bio_short',
        'bio_long',
        'avatar_path',
        'cv_path',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'email',
    ];
}
