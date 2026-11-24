<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'icon',
        'image_path',
        'badge_text',
        'features',
        'action_text',
        'action_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
