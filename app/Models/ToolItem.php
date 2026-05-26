<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolItem extends Model
{
    protected $fillable = [
        'name',
        'row_label',
        'image_path',
        'sort_order',
    ];
}
