Nytt kontaktmeddelande från {{ $contactMessage->name }}

Namn: {{ $contactMessage->name }}
E-post: {{ $contactMessage->email }}
IP: {{ $contactMessage->ip_address }}

Meddelande:
{{ $contactMessage->message }}

---
Skickat: {{ $contactMessage->created_at->format("Y-m-d H:i:s") }}
