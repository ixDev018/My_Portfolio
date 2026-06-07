<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'title',
        'issuer',
        'year',
        'type',
        'description',
        'media_path',
    ];
}
