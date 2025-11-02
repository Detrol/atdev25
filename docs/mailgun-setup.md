# Mailgun Konfiguration för Meddelandesystemet

Detta dokument beskriver hur man konfigurerar Mailgun för att hantera inkommande email-replies i meddelandesystemet.

## Översikt

Systemet använder Mailgun's **Inbound Email Routing** för att fånga svar från användare och automatiskt koppla dem till rätt konversation via webhook.

### Så fungerar det

1. Admin får ett meddelande från en användare
2. Systemet genererar en **unik reply-token** för meddelandet
3. När admin svarar (från panel eller email), får användaren ett email med Reply-To: `reply-{token}@atdev.me`
4. När användaren svarar, skickar Mailgun emailet till vår webhook
5. Webhook parsear emailet och skapar automatiskt ett svar i konversationen

---

## Steg 1: Konfigurera Environment Variables

Lägg till följande i `.env`:

```env
# Mailgun configuration
# VIKTIGT: Använd den subdomain där dina MX records pekar (t.ex. mg.atdev.me)
MAILGUN_DOMAIN=mg.atdev.me
MAILGUN_SECRET=your-mailgun-api-key
MAILGUN_ENDPOINT=api.mailgun.net
MAILGUN_WEBHOOK_SIGNING_KEY=your-webhook-signing-key

# Email settings
MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS=andreas@atdev.me
MAIL_FROM_NAME="ATDev"
```

**Viktigt om MAILGUN_DOMAIN:**
- Detta är domänen som används för **inbound mail** (reply-adressen)
- Den MÅSTE matcha där dina MX records pekar
- Om dina MX records pekar på `mg.atdev.me`, använd `mg.atdev.me`
- Om du vill använda huvuddomänen `atdev.me`, måste du lägga till MX records för den

### Hitta dina Mailgun credentials:

1. Logga in på [Mailgun Dashboard](https://app.mailgun.com)
2. **API Key**: Settings → API Keys → Private API key
3. **Webhook Signing Key**: Settings → Webhooks → HTTP webhook signing key

---

## Steg 2: Verifiera DNS (MX Records)

För att ta emot inkommande emails behöver du MX records.

### Kontrollera dina nuvarande MX records:

```bash
dig mg.atdev.me MX
# eller
dig atdev.me MX
```

Du bör se något liknande:
```
mg.atdev.me.  1  IN  MX  10 mxa.eu.mailgun.org.
mg.atdev.me.  1  IN  MX  10 mxb.eu.mailgun.org.
```

**Vanliga scenarion:**

1. **MX records finns för subdomain (mg.atdev.me)** ← REKOMMENDERAT
   - Sätt `MAILGUN_DOMAIN=mg.atdev.me` i `.env`
   - Reply-adresser blir: `reply-ABC123@mg.atdev.me`

2. **MX records finns för huvuddomän (atdev.me)**
   - Sätt `MAILGUN_DOMAIN=atdev.me` i `.env`
   - Reply-adresser blir: `reply-ABC123@atdev.me`
   - **OBS**: ALL email till `*@atdev.me` går då till Mailgun!

3. **Inga MX records finns**
   - Du måste lägga till MX records i din DNS-leverantör (Cloudflare, etc.)
   - Rekommendation: Använd subdomain (mg.atdev.me) för att separera inbound mail

### Verifiera DNS i Mailgun:

Mailgun Dashboard → Sending → Domain Settings → Verify DNS Settings

---

## Steg 3: Skapa Mailgun Route

Routes är regler som talar om för Mailgun vad som ska hända när emails tas emot.

### Via Mailgun Dashboard:

1. Gå till **Receiving** → **Routes**
2. Klicka på **Create Route**
3. Fyll i följande:

**Priority**: `10` (lägre nummer = högre prioritet)

**Expression Type**: Match Recipient

**Expression** (använd den domän där dina MX records pekar):
```
match_recipient("reply-.*@mg.atdev.me")
```

**Actions**:
- **Store and Notify**: `https://webhooks.atdev.me/mailgun/inbound`

4. Klicka på **Create Route**

**Viktigt**: Expression måste matcha `MAILGUN_DOMAIN` i din `.env`!
- Om `MAILGUN_DOMAIN=mg.atdev.me` → `reply-.*@mg.atdev.me`
- Om `MAILGUN_DOMAIN=atdev.me` → `reply-.*@atdev.me`

### Via Mailgun API:

```bash
curl -s --user 'api:YOUR_API_KEY' \
    https://api.mailgun.net/v3/routes \
    -F priority=10 \
    -F description='ATDev Reply Handler' \
    -F expression='match_recipient("reply-.*@mg.atdev.me")' \
    -F action='forward("https://webhooks.atdev.me/mailgun/inbound")' \
    -F action='store(notify="https://webhooks.atdev.me/mailgun/inbound")'
```

---

## Steg 4: Verifiera Webhook

### Testa att webhook fungerar:

**Viktigt**: Webhook körs på en separat subdomain (`webhooks.atdev.me`) som INTE går genom Cloudflare proxy för att undvika Bot Fight Mode.

1. Verifiera DNS:
   ```bash
   dig webhooks.atdev.me
   # Ska peka direkt på din server IP (inte Cloudflare)
   ```

2. Testa endpoint:
   ```bash
   curl -X POST https://webhooks.atdev.me/mailgun/inbound \
     -d "timestamp=1234567890" \
     -d "token=test" \
     -d "signature=invalid"
   ```
   Expected: `{"error":"Invalid signature"}` (401)

3. Skicka ett testmail manuellt till `reply-test123@mg.atdev.me` (eller din MAILGUN_DOMAIN)
4. Kolla Laravel logs: `storage/logs/laravel.log`
5. Du borde se:
   - `Mailgun webhook: Kunde inte hitta meddelande för token` (normalt för test-token)
   - ELLER ett felmeddelande om signature är ogiltig

### Felsökning:

**Signature validation misslyckas:**
- Kontrollera att `MAILGUN_WEBHOOK_SIGNING_KEY` är korrekt i `.env`
- Verifiera att webhook URL:en är korrekt (HTTPS)

**Route matchar inte:**
- Kontrollera regex i route expression
- Testa med `match_recipient(".*@atdev.me")` temporärt

**Emails kommer inte fram:**
- Verifiera MX records med: `dig atdev.me MX`
- Kontrollera i Mailgun → Logs att emails tas emot

---

## Steg 5: Testa End-to-End

1. Skapa ett testmeddelande via kontaktformuläret på `https://atdev.me/contact`
2. Logga in på admin: `https://atdev.me/admin/login`
3. Gå till Meddelanden och svara på testmeddelandet
4. Kontrollera din email (den du använde i kontaktformuläret)
5. Svara på emailet
6. Verifiera att svaret dyker upp i admin-panelen under konversationen

---

## Security Considerations

### Webhook Signature Verification

Alla inkommande webhooks verifieras med HMAC-SHA256 signature. Detta förhindrar:
- Spam från obehöriga källor
- Replay attacks (timestamp max 15 min gammal)
- Man-in-the-middle attacks

### Rate Limiting

Webhook-endpoint har rate limiting på 100 requests/minut för att förhindra DoS.

### CSRF Protection

Webhook-endpoint är **undantagen från CSRF-skydd** eftersom Mailgun inte kan skicka CSRF tokens. Signature verification ersätter detta skydd.

---

## Monitoring & Logs

### Övervaka webhook-anrop:

```bash
tail -f storage/logs/laravel.log | grep "Mailgun webhook"
```

### Vanliga log-meddelanden:

- **Success**: `Mailgun webhook: Svar mottaget`
- **Invalid signature**: `Mailgun webhook: Ogiltig signature`
- **Token not found**: `Mailgun webhook: Kunde inte hitta meddelande för token`
- **Parse error**: `Mailgun webhook: Kunde inte extrahera reply token`

---

## Troubleshooting

### Problem: Emails kommer inte fram till webhook

**Lösning:**
1. Kontrollera att MX records är korrekt konfigurerade
2. Verifiera i Mailgun Logs att emails tas emot
3. Kontrollera att route är aktiv och matchar recipient

### Problem: Webhook returnerar 401 Unauthorized

**Lösning:**
- Verifiera `MAILGUN_WEBHOOK_SIGNING_KEY` i `.env`
- Kontrollera timestamp (serverns klocka måste vara synkroniserad)

### Problem: Reply skapas men länkas inte till rätt meddelande

**Lösning:**
- Kontrollera att token extraheras korrekt från recipient
- Verifiera att original meddelande har en reply_token i databasen

---

## Production Checklist

- [ ] MX records konfigurerade och verifierade
- [ ] Mailgun domain verifierad
- [ ] Route skapad och testad
- [ ] Environment variables satta i production
- [ ] HTTPS aktiverat på webhook URL
- [ ] End-to-end test genomfört
- [ ] Monitoring setup för webhook logs
- [ ] Rate limiting verifierad

---

## Additional Resources

- [Mailgun Inbound Routing Documentation](https://documentation.mailgun.com/en/latest/user_manual.html#receiving-messages)
- [Webhook Security](https://documentation.mailgun.com/en/latest/user_manual.html#webhooks)
- [Routes API Reference](https://documentation.mailgun.com/en/latest/api-routes.html)
