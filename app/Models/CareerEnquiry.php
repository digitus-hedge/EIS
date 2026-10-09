<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One application sent from the Career page of the website:
 * name, email, phone, nationality, the career applied for, a CV file and a message.
 * The CV is kept on the private "local" disk; only a logged-in admin can download it.
 * Shown (view only) under Admin > Career > Career Enquiries.
 */
class CareerEnquiry extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'nationality', 'career_id', 'apply_for', 'cv', 'message'];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
