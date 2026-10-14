<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email sent to the website owner each time someone submits the Contact form.
 *
 * - "To"       : config('mail.contact_to')   -> sales@eisltd.tech
 * - "Reply-To" : the visitor, so hitting Reply writes straight to them
 */
class NewEnquiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Enquiry $enquiry;

    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    public function build()
    {
        // Recipient comes from config/mail.php, which reads CONTACT_MAIL_TO
        // from .env. Falls back to the global "from" address when empty.
        $to = config('mail.contact_to') ?: config('mail.from.address');

        $mail = $this
            ->to($to)
            ->subject('New Enquiry Received')
            ->view('emails.enquiry-received')
            ->with([
                'enquiry'  => $this->enquiry,
                'logoPath' => public_path('images/logo.png'),
            ]);

        // Reply goes straight to the visitor, if their email is present.
        if (! empty($this->enquiry->email)) {
            $name = trim((string) ($this->enquiry->name ?? ''));
            $mail->replyTo($this->enquiry->email, $name ?: null);
        }

        return $mail;
    }
}