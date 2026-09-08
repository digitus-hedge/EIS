<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    protected $table = 'why_choose_us';

    protected $fillable = [
        'heading', 'description',
        'mission_title', 'mission_description', 'mission_image',
        'vision_title', 'vision_description', 'vision_image',
        'values_title', 'values_description', 'values_image',
        'commitment_title', 'commitment_description',
    ];
}
