<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSection extends Model
{
    protected $table = 'clients';

    protected $fillable = ['title', 'description', 'images'];

    protected $casts = [
        'images' => 'array',
    ];
}
