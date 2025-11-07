<?php

namespace App\Http\Controllers;

use App\Jobs\SendCustomerReplyNotification;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle inbound email från Mailgun.
     */
    public function handleInbound(Request $request)
    {
        // Verifiera Mailgun signature
        if (! $this->verifySignature($request)) {
            Log::warning('Mailgun webhook: Ogiltig signature', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['error' => 'Invalid signature'], 401);
        }

        try {
            // Extrahera data från Mailgun payload
            $recipient = $request->input('recipient');
            $from = $request->input('from');
            $sender = $request->input('sender'); // Den faktiska email-adressen
            $subject = $request->input('subject');
            $bodyPlain = $request->input('body-plain'); // Plain text body
            $strippedText = $request->input('stripped-text'); // Text utan quotes/signatures

            // Extrahera reply_token från recipient
            // Format: reply-{token}@atdev.me
            if (! preg_match('/reply-([a-zA-Z0-9]+)@/', $recipient, $matches)) {
                Log::warning('Mailgun webhook: Kunde inte extrahera reply token', [
                    'recipient' => $recipient,
                ]);

                return response()->json(['error' => 'Invalid recipient format'], 400);
            }

            $replyToken = $matches[1];

            // Hitta original meddelande via token
            $originalMessage = ContactMessage::where('reply_token', $replyToken)
                ->first();

            if (! $originalMessage) {
                Log::warning('Mailgun webhook: Kunde inte hitta meddelande för token', [
                    'token' => $replyToken,
                ]);

                return response()->json(['error' => 'Message not found'], 404);
            }

            // Använd stripped-text om tillgängligt, annars body-plain
            $messageBody = ! empty($strippedText) ? $strippedText : $bodyPlain;

            // Extrahera email-adress från sender/from
            // Format kan vara: "Name <email@domain.com>" eller bara "email@domain.com"
            preg_match('/<(.+?)>|(.+)/', $sender ?: $from, $emailMatches);
            $senderEmail = $emailMatches[1] ?? $emailMatches[2] ?? $sender;
            $senderEmail = strtolower(trim($senderEmail));

            // Detektera om detta är ett svar från admin
            $adminEmail = strtolower(config('mail.from.address'));
            $isAdminReply = ($senderEmail === $adminEmail);

            if ($isAdminReply) {
                // Admin svarar från email-klient
                $reply = ContactMessage::create([
                    'parent_id' => $originalMessage->parent_id ?: $originalMessage->id,
                    'name' => config('mail.from.name', 'ATDev'),
                    'email' => $adminEmail,
                    'message' => trim($messageBody),
                    'ip_address' => $request->ip(),
                    'user_agent' => 'Mailgun Webhook (Admin)',
                    'is_admin_reply' => true,
                    'status' => 'pending',
                    'read' => true, // Admin har redan läst (skrev det själv)
                ]);

                // Skicka svaret till kunden
                \App\Jobs\SendReplyEmail::dispatch($originalMessage, $reply);

                // Uppdatera original meddelande som besvarad
                $parent = $originalMessage->parent_id ? $originalMessage->parent : $originalMessage;
                $parent->update(['status' => 'replied']);

                Log::info('Mailgun webhook: Admin-svar mottaget', [
                    'original_message_id' => $originalMessage->id,
                    'from' => $from,
                ]);
            } else {
                // Kund svarar
                $customerReply = ContactMessage::create([
                    'parent_id' => $originalMessage->parent_id ?: $originalMessage->id,
                    'name' => $originalMessage->name,
                    'email' => $originalMessage->email,
                    'message' => trim($messageBody),
                    'ip_address' => $request->ip(),
                    'user_agent' => 'Mailgun Webhook',
                    'is_admin_reply' => false,
                    'status' => 'pending',
                    'read' => false,
                ]);

                // Skicka notifikation till admin
                SendCustomerReplyNotification::dispatch($originalMessage, $customerReply);

                Log::info('Mailgun webhook: Kundsvar mottaget', [
                    'original_message_id' => $originalMessage->id,
                    'from' => $from,
                ]);
            }

            return response()->json(['message' => 'Reply received'], 200);
        } catch (\Exception $e) {
            Log::error('Mailgun webhook: Fel vid hantering av inbound email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Verifiera Mailgun signature.
     */
    private function verifySignature(Request $request): bool
    {
        $timestamp = $request->input('timestamp');
        $token = $request->input('token');
        $signature = $request->input('signature');

        // Kontrollera att alla required fields finns
        if (! $timestamp || ! $token || ! $signature) {
            return false;
        }

        // Kontrollera timestamp (max 15 minuter gammal)
        if (abs(time() - $timestamp) > 900) {
            return false;
        }

        // Beräkna expected signature
        $signingKey = config('services.mailgun.webhook_signing_key');

        if (empty($signingKey)) {
            Log::error('Mailgun webhook: Webhook signing key saknas i config');

            return false;
        }

        $data = $timestamp.$token;
        $expectedSignature = hash_hmac('sha256', $data, $signingKey);

        return hash_equals($expectedSignature, $signature);
    }
}
