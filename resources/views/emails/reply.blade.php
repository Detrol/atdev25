Hej {{ $originalMessage->name }},

{{ $replyMessage->message }}

───────────────────────────────────────────────────

Detta är ett svar på ditt meddelande:

> {{ $originalMessage->message }}

Skickat: {{ $originalMessage->created_at->format('Y-m-d H:i') }}

───────────────────────────────────────────────────

Du kan svara på detta email för att fortsätta konversationen.

Vänliga hälsningar,
ATDev
https://atdev.me
