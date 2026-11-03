<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserAgreement;

class AgreementOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agreement;
    public $signUrl;
    public $recipientName;

    public function __construct(UserAgreement $agreement, string $signUrl, string $recipientName)
    {
        $this->agreement    = $agreement;
        $this->signUrl      = $signUrl;
        $this->recipientName = $recipientName;
    }

    public function build()
    {
        $mail = $this->subject('Action Required: Sign Your Allotment Letter — ' . config('app.name'))
                     ->view('emails.agreement_otp');

        if ($this->agreement->uploaded_document) {
            $filePath = storage_path('app/public/' . $this->agreement->uploaded_document);
            if (file_exists($filePath)) {
                $mail->attach($filePath, [
                    'as'   => basename($filePath),
                    'mime' => mime_content_type($filePath),
                ]);
            }
        }

        return $mail;
    }
}
