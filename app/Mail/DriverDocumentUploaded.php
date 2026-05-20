<?php

namespace App\Mail;

use App\Models\DriverDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DriverDocumentUploaded extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    public function __construct(DriverDocument $document)
    {
        $this->document = $document;
    }

    public function build()
    {
        return $this
            ->subject('New Driver Document Uploaded')
            ->view('emails.driver-document-uploaded');
    }
}
