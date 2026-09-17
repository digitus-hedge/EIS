<?php

namespace App\Http\Controllers;

use App\Mail\NewEnquiryNotification;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'full_name'    => 'required|string|max:255',
        'email'        => 'required|email:rfc,dns|max:255',
        'address'      => 'required|string|max:255',
        'phone_number' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/'],
        'country'      => 'nullable|string|max:255',
        'comments'     => 'nullable|string|max:5000',
    ], [
        'full_name.required'    => 'Please enter your full name.',
        'email.required'        => 'Please enter your email address.',
        'email.email'           => 'Please enter a valid email address.',
        'address.required'      => 'Please enter your address.',
        'phone_number.required' => 'Please enter your phone number.',
        'phone_number.regex'    => 'Please enter a valid phone number.',
    ]);

    $enquiry = Enquiry::create($validated);

    // Notify the admin/team of the new enquiry
    Mail::to(config('mail.from.address'))->send(new NewEnquiryNotification($enquiry));

    return back()->with('success', 'Thank you — your enquiry has been received. We will get back to you shortly.');
}
}