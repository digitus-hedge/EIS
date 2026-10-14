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
 *
 * - "To"       : config('mail.career_to')   -> sales@eisltd.tech
 * - "Cc"       : config('mail.career_cc')   -> epochwebservice@gmail.com
 * - "Reply-To" : the applicant, so hitting Reply writes straight to them
 * - Attachment : the applicant's CV from the "public" disk, renamed nicely
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

        // Recipients come from config/mail.php, which reads the .env values
        // CAREER_MAIL_TO and CAREER_MAIL_CC. Falls back to the global
        // "from" address when CAREER_MAIL_TO is empty.
        $to = config('mail.career_to') ?: config('mail.from.address');
        $cc = config('mail.career_cc');

        $mail = $this
            ->to($to)
            ->subject(Str::limit('New career application: ' . $post . ' - ' . $name, 150))
            ->replyTo($this->enquiry->email, $name)
            ->view('emails.career-enquiry');

        if (! empty($cc)) {
            $mail->cc($cc);
        }

        // Attach the uploaded CV, if any
        $cv = $this->enquiry->cv;
        if ($cv && Storage::disk('public')->exists($cv)) {
            $extension = strtolower(pathinfo($cv, PATHINFO_EXTENSION));
            $fileName  = 'CV-' . (Str::slug($name) ?: 'applicant') . ($extension ? '.' . $extension : '');

            $mail->attachFromStorageDisk('public', $cv, $fileName);
        }

        return $mail;
    }
}