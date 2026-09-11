<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    protected $fillable = ['title', 'image'];

    protected static function booted()
    {
        static::deleting(function (Certificate $certificate) {
            if ($certificate->image) {
                Storage::disk('public')->delete($certificate->image);
            }
        });
    }
}