<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
        use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image_1',
        'image_2',
        'image_3',
        'video',
    ];
}