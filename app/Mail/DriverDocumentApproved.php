<?php

namespace App\Mail;

use App\Models\DriverDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DriverDocumentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    public function __construct(
        DriverDocument $document
    ) {
        $this->document = $document;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Driver Document Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.driver-document-approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}