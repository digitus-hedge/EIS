<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RegionalOffice extends Model
{
    protected $fillable = [
        'regional_location_id', 'title', 'description', 'image', 'sort_order',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(RegionalLocation::class, 'regional_location_id');
    }

    protected static function booted()
    {
        static::deleting(function (RegionalOffice $office) {
            if ($office->image) {
                Storage::disk('public')->delete($office->image);
            }
        });
    }
}