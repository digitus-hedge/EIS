<?php

namespace App\Mail;

use App\Models\CareerEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Email sent to the website owner each time someone applies from the Career page.
 * The applicant's CV is attached and "Reply" goes straight to the applicant.
 */
class CareerEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public CareerEnquiry $enquiry;

    public function __construct(CareerEnquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function build()
    {
        $name = trim(preg_replace('/\s+/', ' ', (string) $this->enquiry->name));
        $post = trim(preg_replace('/\s+/', ' ', (string) $this->enquiry->apply_for));

        $mail = $this
            ->subject(Str::limit('New career application: ' . $post . ' - ' . $name, 150))
            ->replyTo($this->enquiry->email, $name)
            ->view('emails.career-enquiry');

        $cv = $this->enquiry->cv;
        if ($cv && Storage::disk('public')->exists($cv)) {
            $extension = strtolower(pathinfo($cv, PATHINFO_EXTENSION));
            $fileName  = 'CV-' . (Str::slug($name) ?: 'applicant') . ($extension ? '.' . $extension : '');

            $mail->attachFromStorageDisk('public', $cv, $fileName);
        }

        return $mail;
    }
}