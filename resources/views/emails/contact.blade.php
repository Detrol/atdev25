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
            background: #4F46E5;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #F9FAFB;
            padding: 20px;
            border: 1px solid #E5E7EB;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .metadata {
            background: white;
            padding: 15px;
            border-left: 4px solid #4F46E5;
            margin: 15px 0;
        }
        .metadata-item {
            margin: 8px 0;
        }
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            white-space: pre-wrap;
        }
        .footer {
            color: #9CA3AF;
            font-size: 13px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #E5E7EB;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0;">Nytt kontaktmeddelande från {{ $contactMessage->name }}</h2>
    </div>

    <div class="content">
        <div class="metadata">
            <div class="metadata-item"><strong>Namn:</strong> {{ $contactMessage->name }}</div>
            <div class="metadata-item"><strong>E-post:</strong> <a href="mailto:{{ $contactMessage->email }}" style="color: #4F46E5;">{{ $contactMessage->email }}</a></div>
            <div class="metadata-item"><strong>IP:</strong> {{ $contactMessage->ip_address }}</div>
        </div>

        <h3 style="color: #1F2937;">Meddelande:</h3>
        <div class="message-box">{{ $contactMessage->message }}</div>

        <div class="footer">
            Skickat: {{ $contactMessage->created_at->format("Y-m-d H:i:s") }}
        </div>
    </div>
</body>
</html>
