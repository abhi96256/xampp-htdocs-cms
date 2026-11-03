<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserAgreement;

class DocumentUploadMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agreement;
    public $note;
    public $downloadUrl;

    public function __construct(UserAgreement $agreement, string $note, string $downloadUrl)
    {
        $this->agreement   = $agreement;
        $this->note        = $note;
        $this->downloadUrl = $downloadUrl;
    }

    public function build()
    {
        $mail = $this->subject('Important Document Shared — ' . config('app.name'))
                     ->view('emails.document_upload');

        // Attach the file if it exists
        $filePath = storage_path('app/public/' . $this->agreement->uploaded_document);
        if (file_exists($filePath)) {
            $mail->attach($filePath, [
                'as'   => basename($filePath),
                'mime' => mime_content_type($filePath),
            ]);
        }

        return $mail;
    }
}
