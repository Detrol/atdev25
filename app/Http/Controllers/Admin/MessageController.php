<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use App\Jobs\SendReplyEmail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display all contact messages.
     *
     * Data contract:
     * - messages: Collection<ContactMessage> (paginated, original messages only)
     */
    public function index()
    {
        $messages = ContactMessage::originalMessages()
            ->with(['replies', 'adminReplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show a message conversation.
     *
     * Data contract:
     * - message: ContactMessage (original message)
     * - conversation: Collection<ContactMessage> (all messages in thread)
     */
    public function show(ContactMessage $message)
    {
        // Hitta root-meddelandet om detta är ett svar
        $originalMessage = $message->parent_id ? $message->parent : $message;

        // Hämta hela konversationen
        $conversation = $originalMessage->conversation();

        // Markera som läst
        if (!$originalMessage->read) {
            $originalMessage->markAsRead();
        }

        return view('admin.messages.show', [
            'message' => $originalMessage,
            'conversation' => $conversation,
        ]);
    }

    /**
     * Reply to a message.
     */
    public function reply(ReplyRequest $request, ContactMessage $message)
    {
        // Hitta root-meddelandet om detta är ett svar
        $originalMessage = $message->parent_id ? $message->parent : $message;

        // Skapa reply
        $reply = $originalMessage->createReply(
            $request->message,
            auth()->id()
        );

        // Skicka email till användaren
        SendReplyEmail::dispatch($originalMessage, $reply);

        return redirect()
            ->route('admin.messages.show', $originalMessage)
            ->with('success', 'Svar skickat!');
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->markAsRead();

        return redirect()->back()
            ->with('success', 'Meddelande markerat som läst!');
    }

    /**
     * Delete a message.
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Meddelande raderat!');
    }
}
