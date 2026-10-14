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

        // Notify the owner. The enquiry is already saved, so a mail problem
        // must not lose it: it is written to the log and the visitor still
        // sees the success message.
        try {
            $to = config('mail.contact_to') ?: config('mail.from.address');

            if ($to) {
                Mail::to($to)->send(new NewEnquiryNotification($enquiry));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Thank you — your enquiry has been received. We will get back to you shortly.');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search  = $request->input('search');

        $enquiries = Enquiry::query()
            ->when($search, function ($query, $search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('comments', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.enquiry.list', compact('enquiries', 'search', 'perPage'));
    }

    public function show(Enquiry $enquiry)
    {
        if (! $enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }

        return view('admin.enquiry.show', compact('enquiry'));
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()
            ->route('admin.home.enquiries')
            ->with('success', 'Enquiry deleted successfully.');
    }
}