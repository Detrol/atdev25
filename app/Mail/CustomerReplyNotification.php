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
            // Reply-To till reply-token addressen för att fånga svar via webhook
            replyTo: [$this->originalMessage->getReplyAddress()],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Hämta hela konversationen
        $parent = $this->originalMessage->parent_id ? $this->originalMessage->parent : $this->originalMessage;
        $conversation = $parent->conversation();

        return new Content(
            view: 'emails.customer-reply-notification',
            with: [
                'originalMessage' => $this->originalMessage,
                'customerReply' => $this->customerReply,
                'conversation' => $conversation,
                'conversationUrl' => route('admin.messages.show', $parent),
            ],
        );
    }

    /**
     * Set email headers for proper threading.
     */
    public function build()
    {
        return $this->withSymfonyMessage(function ($message) {
            $headers = $message->getHeaders();

            // Sätt Message-ID för detta nya meddelande
            if ($this->customerReply->email_message_id) {
                $headers->addIdHeader('Message-ID', $this->customerReply->email_message_id);
            }

            // Sätt In-Reply-To till det ursprungliga meddelandet
            if ($this->originalMessage->email_message_id) {
                $headers->addIdHeader('In-Reply-To', $this->originalMessage->email_message_id);
            }

            // Sätt References till hela tråden
            $parent = $this->originalMessage->parent_id ? $this->originalMessage->parent : $this->originalMessage;
            $conversation = $parent->conversation();
            $references = $conversation
                ->filter(fn($msg) => $msg->email_message_id)
                ->pluck('email_message_id')
                ->toArray();

            if (!empty($references)) {
                $headers->addIdHeader('References', $references);
            }
        });
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
