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

                    <!-- Nytt meddelande (tydligt markerat) -->
                    <tr>
                        <td style="padding: 24px 32px; border-bottom: 1px solid #e5e7eb;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <div style="font-size: 18px; font-weight: 600; color: #1f2937;">ATDev</div>
                                        <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">{{ $replyMessage->created_at->format('Y-m-d H:i') }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px 32px; border-left: 4px solid #6366f1; background-color: #fefefe;">
                            <div style="color: #1f2937; font-size: 15px; line-height: 1.6; white-space: pre-wrap;">{{ $replyMessage->message }}</div>
                        </td>
                    </tr>

                    <!-- Konversationshistorik -->
                    @if(isset($conversation) && $conversation->count() > 1)
                    <tr>
                        <td style="padding: 20px 32px; background-color: #f9fafb; border-top: 1px solid #e5e7eb;">
                            <div style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Tidigare meddelanden</div>
                        </td>
                    </tr>
                    @foreach($conversation as $msg)
                        @if($msg->id !== $replyMessage->id)
                        <tr>
                            <td style="padding: 16px 32px; border-left: 4px solid {{ $msg->is_admin_reply ? '#6366f1' : '#d1d5db' }}; background-color: #f9fafb;">
                                <div style="font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                                    {{ $msg->is_admin_reply ? 'ATDev' : $msg->name }}
                                </div>
                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                                    {{ $msg->created_at->format('Y-m-d H:i') }}
                                </div>
                                <div style="color: #4b5563; font-size: 14px; line-height: 1.5; white-space: pre-wrap;">{{ $msg->message }}</div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    @endif

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 32px; background-color: #f3f4f6; border-top: 1px solid #e5e7eb; border-radius: 0 0 8px 8px;">
                            <div style="font-size: 13px; color: #6b7280; text-align: center;">
                                Svara på detta email för att fortsätta konversationen
                            </div>
                            <div style="font-size: 12px; color: #9ca3af; text-align: center; margin-top: 8px;">
                                <a href="https://atdev.me" style="color: #6366f1; text-decoration: none;">atdev.me</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
