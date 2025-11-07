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
        // Hämta hela konversationen
        $parent = $this->originalMessage->parent_id ? $this->originalMessage->parent : $this->originalMessage;
        $conversation = $parent->conversation();

        return new Content(
            view: 'emails.reply',
            with: [
                'originalMessage' => $this->originalMessage,
                'replyMessage' => $this->replyMessage,
                'conversation' => $conversation,
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
            if ($this->replyMessage->email_message_id) {
                $headers->addTextHeader('Message-ID', $this->replyMessage->email_message_id);
            }

            // Sätt In-Reply-To till det ursprungliga meddelandet
            if ($this->originalMessage->email_message_id) {
                $headers->addTextHeader('In-Reply-To', $this->originalMessage->email_message_id);
            }

            // Sätt References till hela tråden
            $parent = $this->originalMessage->parent_id ? $this->originalMessage->parent : $this->originalMessage;
            $conversation = $parent->conversation();
            $references = $conversation
                ->filter(fn($msg) => $msg->email_message_id)
                ->pluck('email_message_id')
                ->implode(' ');

            if ($references) {
                $headers->addTextHeader('References', $references);
            }
        });
    }
}
