<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .reply-box {
            background: #F3F4F6;
            border-left: 4px solid #4F46E5;
            padding: 15px;
            margin: 20px 0;
            white-space: pre-wrap;
        }
        .metadata {
            color: #6B7280;
            font-size: 14px;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background: #4F46E5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .original-message {
            border-top: 2px solid #E5E7EB;
            padding-top: 15px;
            margin-top: 20px;
            color: #6B7280;
            font-size: 14px;
        }
        .footer {
            border-top: 2px solid #E5E7EB;
            padding-top: 15px;
            margin-top: 30px;
            color: #9CA3AF;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0; color: #1F2937;">Nytt svar från {{ $originalMessage->name }}</h2>
    </div>

    <p>Hej,</p>
    <p>{{ $originalMessage->name }} har svarat på konversationen:</p>

    <div class="reply-box">{{ $customerReply->message }}</div>

    <div class="metadata">
        <strong>Från:</strong> {{ $originalMessage->name }} &lt;{{ $originalMessage->email }}&gt;<br>
        <strong>Mottaget:</strong> {{ $customerReply->created_at->format('Y-m-d H:i') }}
    </div>

    <a href="{{ $conversationUrl }}" class="button">Visa hela konversationen</a>

    <p style="margin-top: 20px;">Du kan svara på detta email direkt eller klicka på knappen ovan för att öppna admin-panelen.</p>

    <div class="original-message">
        <strong>Konversationens ursprung:</strong><br>
        <blockquote style="margin: 10px 0; padding-left: 15px; border-left: 3px solid #E5E7EB;">
            {{ \Illuminate\Support\Str::limit($originalMessage->message, 200) }}
        </blockquote>
        <em>Skickat: {{ $originalMessage->created_at->format('Y-m-d H:i') }}</em>
    </div>

    <div class="footer">
        ATDev Meddelandesystem
    </div>
</body>
</html>
