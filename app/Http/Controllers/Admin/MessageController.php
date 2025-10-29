<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display all contact messages.
     *
     * Data contract:
     * - messages: Collection<ContactMessage> (paginated)
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
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
