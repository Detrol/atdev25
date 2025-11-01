<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Jobs\SendContactEmail;
use App\Models\ContactMessage;
use App\Models\PriceEstimation;

class ContactController extends Controller
{
    /**
     * Store a contact message.
     */
    public function store(ContactRequest $request)
    {
        $message = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Link price estimation if provided
        if ($request->filled('price_estimation_id')) {
            PriceEstimation::where('id', $request->price_estimation_id)
                ->update(['contact_message_id' => $message->id]);
        }

        // Dispatch email job
        SendContactEmail::dispatch($message);

        return redirect()->back()->with('success', 'Tack för ditt meddelande! Vi återkommer så snart som möjligt.');
    }
}
