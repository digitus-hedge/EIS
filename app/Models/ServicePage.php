<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePage extends Model
{
    protected $fillable = [
        'banner_title', 'banner', 'our_service_title', 'our_service_description',
    ];
}