<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One application sent from the Career page of the website:
 * name, email, phone and the career applied for.
 * Shown (view only) under Admin > Career > Career Enquiries.
 */
class CareerEnquiry extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'career_id', 'apply_for'];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
