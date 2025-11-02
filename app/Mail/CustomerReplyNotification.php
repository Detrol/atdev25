<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerReplyNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public ContactMessage $originalMessage,
        public ContactMessage $customerReply
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nytt svar från {$this->originalMessage->name}",
            // Reply-To till kundens email så admin kan svara direkt
            replyTo: [$this->originalMessage->email],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-reply-notification',
            with: [
                'originalMessage' => $this->originalMessage,
                'customerReply' => $this->customerReply,
                'conversationUrl' => route('admin.messages.show', $this->originalMessage),
            ],
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
