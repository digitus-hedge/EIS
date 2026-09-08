<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    protected $table = 'why_choose_us';

    protected $fillable = [
        'heading', 'description', 'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}