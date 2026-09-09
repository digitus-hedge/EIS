<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OperationVideo extends Model
{
    protected $fillable = [
        'title', 'description', 'thumbnail', 'video', 'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function (OperationVideo $video) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            if ($video->video) {
                Storage::disk('public')->delete($video->video);
            }
        });
    }
}