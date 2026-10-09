<?php

namespace App\Http\Controllers;

use App\Mail\CareerEnquiryMail;
use App\Models\Career;
use App\Models\CareerEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Public Career page of the website:
 * lists the careers added under Admin > Career and receives the "Apply Now" form.
 */
class CareerPageController extends Controller
{
    /** GET /career */
    public function index()
    {
        $careers = Career::query()->latest('id')->get();

        // options of the "Select Location" dropdown: every location used by a career
        $locations = $careers->pluck('location')
            ->map(fn ($location) => trim((string) $location))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('web.career', compact('careers', 'locations'));
    }

    /** POST /career/apply */
    public function store(Request $request)
    {
        // Spam trap: the hidden "website" field is only ever filled by bots.
        if ($request->filled('website')) {
            return $this->done($request);
        }

        $data = $request->validate([
            'career_id'   => ['nullable', 'integer', 'exists:careers,id'],
            'name'        => ['required', 'string', 'max:120'],
            'apply_for'   => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150'],
            'phone'       => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'location'    => ['nullable', 'string', 'max:150'],
            'message'     => ['nullable', 'string', 'max:2000'],
            'cv'          => ['required', 'file', 'mimes:pdf,doc,docx', 'max:3072'],   // 3 MB
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
            'cv.required' => 'Please attach your CV.',
            'cv.mimes'    => 'The CV must be a PDF or Word file (.pdf, .doc or .docx).',
            'cv.max'      => 'The CV must not be larger than 3 MB.',
        ], [
            'apply_for' => 'post',
            'cv'        => 'CV',
        ]);

        // If the visitor applied from a listed career, trust the title saved in the database.
        $career = ! empty($data['career_id']) ? Career::find($data['career_id']) : null;

        // Saved on the public disk so the "View" link in Admin > Career Enquiries opens it.
        // Laravel gives the file a long random name.
        $cvPath = $request->file('cv')->store('career-cvs', 'public');

        $enquiry = CareerEnquiry::create([
            'career_id'   => $career?->id,
            'apply_for'   => $career?->title ?? $data['apply_for'],
            'name'        => $data['name'],
            'email'       => $data['email'],
            'phone'       => $data['phone'],
            'nationality' => $data['nationality'] ?? null,
            'location'    => $data['location'] ?? null,
            'message'     => $data['message'] ?? null,
            'cv'          => $cvPath,
        ]);

        // Tell the owner. The application is already saved, so a mail problem must not lose it:
        // it is written to the log and the visitor still sees the success message.
        try {
            $to = config('mail.career_to') ?: config('mail.from.address');
            if ($to) {
                Mail::to($to)->send(new CareerEnquiryMail($enquiry));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $this->done($request);
    }

    private function done(Request $request)
    {
        $message = 'Thank you. We have received your application.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->to(route('career') . '#openings')->with('career_success', $message);
    }
}