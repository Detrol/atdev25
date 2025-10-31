<?php

namespace App\Jobs;

use App\Mail\ReplyEmail;
use App\Models\ContactMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendReplyEmail implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContactMessage $originalMessage,
        public ContactMessage $replyMessage
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->originalMessage->email)
            ->send(new ReplyEmail($this->originalMessage, $this->replyMessage));
    }
}
