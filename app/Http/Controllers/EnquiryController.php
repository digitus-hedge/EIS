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
            'full_name' => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string|max:255',
            'town_city' => 'nullable|string|max:255',
            'country'   => 'nullable|string|max:255',
            'comments'  => 'nullable|string',
        ]);

        $enquiry = Enquiry::create($validated);

        // Notify the admin/team of the new enquiry
        Mail::to(config('mail.from.address'))->send(new NewEnquiryNotification($enquiry));

        return back()->with('success', 'Thank you — your enquiry has been received. We will get back to you shortly.');
    }
}