<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; }
        .score-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #667eea; }
        .score { font-size: 48px; font-weight: bold; color: #667eea; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Din Webbplatsgranskning är Klar!</h1>
        </div>

        <div class="content">
            <p>Hej {{ $audit->name }}!</p>

            <p>Din granskning av <strong>{{ $audit->url }}</strong> är nu klar. Här är en snabb sammanfattning:</p>

            <div class="score-box">
                <div class="score">{{ $audit->overall_score }}/100</div>
                <p style="margin: 10px 0 0 0; color: #666;">Övergripande Betyg</p>
            </div>

            <table style="width: 100%; margin: 20px 0;">
                <tr>
                    <td style="padding: 10px; background: white; border-radius: 5px; margin-bottom: 5px;">
                        <strong>SEO:</strong> {{ $audit->seo_score }}/100
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: white; border-radius: 5px;">
                        <strong>Teknisk Optimering:</strong> {{ $audit->technical_score }}/100
                    </td>
                </tr>
            </table>

            <p>För att se den fullständiga rapporten med detaljerade förbättringsförslag, klicka på knappen nedan:</p>

            <center>
                <a href="{{ route('audits.status', $audit->token) }}" class="button">Se Fullständig Rapport</a>
            </center>

            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                <strong>Tips:</strong> Spara denna länk för framtida referens. Den är giltig i 90 dagar.
            </p>
        </div>

        <div class="footer">
            <p>Har du frågor? Kontakta mig på <a href="mailto:andreas@atdev.me">andreas@atdev.me</a></p>
            <p style="margin-top: 20px;">&copy; {{ date('Y') }} ATDev. Alla rättigheter förbehållna.</p>
        </div>
    </div>
</body>
</html>
