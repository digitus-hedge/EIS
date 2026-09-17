<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'banner_title',
        'slug',
        'banner_description',
        'banner_image',
        'overview_title',
        'overview_description',
        'overview_image',
        'process',
        'features_heading',
        'features',
        'show_on_home'
    ];

    protected $casts = [
        'process'  => 'array',
        'features' => 'array',
         'gallery' => 'array',
        'show_on_home' => 'boolean',

    ];
}