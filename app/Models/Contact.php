<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'banner_title',
        'banner_image',
        'phone',
        'email',
        'address',
        'contact_image',
    ];
}