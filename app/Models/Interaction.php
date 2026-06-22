<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    protected $fillable = [
        'type',
        'project_id',
        'meta_data',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'meta_data' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
