<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    private string $type;
    private string $code;


    /**
     * Create a new message instance.
     */
    public function __construct($type,$code)
    {
        $this->type = $type;
        $this->code = $code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Confirmation Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        $type = $this->type;
        $code = $this->code;

        return new Content(
            view: 'emails.confirmEmail',
            with: ['type' => $type,"code" => $code]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
