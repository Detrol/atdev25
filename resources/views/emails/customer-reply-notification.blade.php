<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px;">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 24px 32px; border-bottom: 1px solid #e5e7eb;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <div style="font-size: 18px; font-weight: 600; color: #1f2937;">{{ $originalMessage->name }}</div>
                                        <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                            <a href="mailto:{{ $originalMessage->email }}" style="color: #6366f1; text-decoration: none;">{{ $originalMessage->email }}</a>
                                            <span style="color: #d1d5db; margin: 0 8px;">•</span>
                                            {{ $customerReply->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Message -->
                    <tr>
                        <td style="padding: 32px; color: #1f2937; font-size: 15px; line-height: 1.6;">
                            <div style="white-space: pre-wrap;">{{ $customerReply->message }}</div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 32px; background-color: #f9fafb; border-top: 1px solid #e5e7eb; border-radius: 0 0 8px 8px;">
                            <div style="font-size: 13px; color: #6b7280; text-align: center;">
                                Svara på detta email för att fortsätta konversationen
                            </div>
                            <div style="font-size: 12px; color: #9ca3af; text-align: center; margin-top: 8px;">
                                <a href="{{ $conversationUrl }}" style="color: #6366f1; text-decoration: none;">Visa i admin-panelen</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
