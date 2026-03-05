<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ContractSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $signingUrl = null;

    /**
     * Create a new message instance.
     */
    public function __construct($signingUrl)
    {
        $this->signingUrl = $signingUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@myenergy.co.uk', 'My Energy'),
            replyTo: [
                new Address('info@myenergy.co.uk', 'My Energy'),
            ],
            subject: 'MyEnergy - Sign Your Contract',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.external-contract',
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
