<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Certificate extends Mailable
{
    use Queueable, SerializesModels;

    private string $student;
    private string $supervisor;
    private string $title;
    private string $duration;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $supervisor, $title, $duration)
    {
        $this->student = $student;
        $this->supervisor = $supervisor;
        $this->title = $title;
        $this->duration = $duration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Internship Certificate',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        $student = $this->student;
        $supervisor = $this->supervisor;
        $title = $this->title;
        $duration = $this->duration;

        return new Content(
            view: 'view.name',
            with: compact('student', 'supervisor', 'title', 'duration')
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
