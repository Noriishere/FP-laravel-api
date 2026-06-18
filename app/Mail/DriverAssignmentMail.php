<?php

namespace App\Mail;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DriverAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Schedule $schedule
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penugasan Jadwal Baru GASSIN',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.driver-assignment',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}