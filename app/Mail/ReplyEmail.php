<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReplyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public ContactMessage $originalMessage,
        public ContactMessage $replyMessage
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: Ditt meddelande till ATDev',
            replyTo: [$this->originalMessage->getReplyAddress()],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reply',
            with: [
                'originalMessage' => $this->originalMessage,
                'replyMessage' => $this->replyMessage,
            ],
        );
    }
}
