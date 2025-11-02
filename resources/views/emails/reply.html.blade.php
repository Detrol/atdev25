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
        .reply-message {
            background: #F9FAFB;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            white-space: pre-wrap;
        }
        .original-message {
            border-top: 2px solid #E5E7EB;
            padding-top: 15px;
            margin-top: 20px;
            background: #F3F4F6;
            padding: 15px;
            border-left: 4px solid #9CA3AF;
        }
        .footer {
            border-top: 2px solid #E5E7EB;
            padding-top: 15px;
            margin-top: 30px;
            color: #6B7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0; color: #1F2937;">Hej {{ $originalMessage->name }},</h2>
    </div>

    <div class="reply-message">{{ $replyMessage->message }}</div>

    <div class="original-message">
        <strong style="color: #6B7280;">Detta är ett svar på ditt meddelande:</strong>
        <blockquote style="margin: 10px 0; padding-left: 15px; border-left: 3px solid #D1D5DB; color: #6B7280;">
            {{ $originalMessage->message }}
        </blockquote>
        <em style="color: #9CA3AF; font-size: 14px;">Skickat: {{ $originalMessage->created_at->format('Y-m-d H:i') }}</em>
    </div>

    <div class="footer">
        <p><strong>Du kan svara på detta email för att fortsätta konversationen.</strong></p>
        <p>Vänliga hälsningar,<br>
        ATDev<br>
        <a href="https://atdev.me" style="color: #4F46E5;">https://atdev.me</a></p>
    </div>
</body>
</html>
