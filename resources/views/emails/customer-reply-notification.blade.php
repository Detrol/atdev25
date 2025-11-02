Hej,

{{ $originalMessage->name }} har svarat på konversationen:

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

{{ $customerReply->message }}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Från: {{ $originalMessage->name }} <{{ $originalMessage->email }}>
Mottaget: {{ $customerReply->created_at->format('Y-m-d H:i') }}

Visa hela konversationen i admin-panelen:
{{ $conversationUrl }}

Du kan svara på detta email direkt eller logga in i admin-panelen.

───────────────────────────────────────────────────

Konversationens ursprung:
> {{ Str::limit($originalMessage->message, 200) }}
Skickat: {{ $originalMessage->created_at->format('Y-m-d H:i') }}

───────────────────────────────────────────────────

ATDev Meddelandesystem
