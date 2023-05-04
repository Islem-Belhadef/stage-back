<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($type, $address, $password)
    {
        $this->type = $type;
        $this->address = $address;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Supervisor Account Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $type = $this->type;
        $address = $this->address;
        $password = $this->password;

        return new Content(
            view: 'emails.accountCreated',
            with: ['type' => $type, 'address' => $address, 'password' => $password]
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
