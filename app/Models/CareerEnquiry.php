<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One application sent from the Career page of the website:
 * name, email, phone, the career applied for, nationality, location,
 * an optional message and the uploaded CV.
 * Shown (view only) under Admin > Career > Career Enquiries.
 */
class CareerEnquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'career_id',
        'apply_for',
        'nationality',
        'location',
        'message',
        'cv',          // path on the public disk, e.g. career-cvs/xxxxxxxx.pdf
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}