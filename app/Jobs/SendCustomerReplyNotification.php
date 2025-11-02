<?php

namespace App\Jobs;

use App\Mail\CustomerReplyNotification;
use App\Models\ContactMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCustomerReplyNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContactMessage $originalMessage,
        public ContactMessage $customerReply
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Skicka notifikation till admin
        $adminEmail = config('mail.from.address');

        Mail::to($adminEmail)
            ->send(new CustomerReplyNotification($this->originalMessage, $this->customerReply));
    }
}
