<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolItem extends Model
{
    protected $fillable = [
        'name',
        'tooltip_info',
        'row_label',
        'image_path',
        'sort_order',
    ];
}
