<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Chat;
use App\Models\GdprDataRequest;
use Illuminate\Support\Facades\DB;

class GdprDataDeletionService
{
    /**
     * Anonymisera användardata för given email
     *
     * SHOWCASE: Demo implementation av GDPR right to be forgotten.
     * Anonymiserar data istället för att radera helt för att behålla statistik.
     */
    public function anonymizeUserData(string $email): array
    {
        $stats = [
            'contact_messages_anonymized' => 0,
            'chat_sessions_anonymized' => 0,
        ];

        DB::transaction(function () use ($email, &$stats) {
            // Anonymisera kontaktmeddelanden
            $contactMessages = ContactMessage::where('email', $email)->get();
            foreach ($contactMessages as $message) {
                $message->update([
                    'name' => 'Anonymiserad användare',
                    'email' => 'deleted@example.com',
                    'message' => '[Meddelandet har raderats enligt GDPR]',
                    'ip_address' => '0.0.0.0',
                    'user_agent' => 'Deleted',
                ]);
                $stats['contact_messages_anonymized']++;
            }

            // Anonymisera chat-historik
            $chats = Chat::where('question', 'LIKE', "%{$email}%")
                ->orWhere('answer', 'LIKE', "%{$email}%")
                ->get();

            foreach ($chats as $chat) {
                $chat->update([
                    'question' => '[Raderad enligt GDPR]',
                    'answer' => '[Raderad enligt GDPR]',
                ]);
                $stats['chat_sessions_anonymized']++;
            }
        });

        return $stats;
    }

    /**
     * Permanent deletion av användardata
     *
     * SHOWCASE: Alternativ till anonymization - permanent radering
     * OBS: Använd med försiktighet i produktionssystem!
     */
    public function deleteUserData(string $email): array
    {
        $stats = [
            'contact_messages_deleted' => 0,
            'chat_sessions_deleted' => 0,
        ];

        DB::transaction(function () use ($email, &$stats) {
            // Radera kontaktmeddelanden
            $stats['contact_messages_deleted'] = ContactMessage::where('email', $email)->delete();

            // Radera chat-historik
            $stats['chat_sessions_deleted'] = Chat::where('question', 'LIKE', "%{$email}%")
                ->orWhere('answer', 'LIKE', "%{$email}%")
                ->delete();
        });

        return $stats;
    }

    /**
     * Skapa deletion request och returnera token för bekräftelse
     */
    public function createDeletionRequest(string $email, string $ipAddress = null): GdprDataRequest
    {
        return GdprDataRequest::createRequest($email, 'delete', $ipAddress);
    }

    /**
     * Processa deletion request med token
     */
    public function processDeletionRequest(string $token, bool $fullDeletion = false): array
    {
        $request = GdprDataRequest::findByToken($token);

        if (!$request) {
            throw new \Exception('Invalid or expired token');
        }

        // Utför radering/anonymization
        $stats = $fullDeletion
            ? $this->deleteUserData($request->email)
            : $this->anonymizeUserData($request->email);

        // Markera request som processad
        $request->markAsProcessed(json_encode($stats));

        return [
            'success' => true,
            'method' => $fullDeletion ? 'full_deletion' : 'anonymization',
            'stats' => $stats,
            'processed_at' => $request->processed_at->toIso8601String(),
        ];
    }

    /**
     * Hämta data summary före deletion
     */
    public function getPreDeletionSummary(string $email): array
    {
        $contactMessages = ContactMessage::where('email', $email)->count();
        $chatSessions = Chat::where('question', 'LIKE', "%{$email}%")
            ->orWhere('answer', 'LIKE', "%{$email}%")
            ->count();

        return [
            'email' => $email,
            'data_found' => [
                'contact_messages' => $contactMessages,
                'chat_sessions' => $chatSessions,
            ],
            'total_records' => $contactMessages + $chatSessions,
        ];
    }

    /**
     * SHOWCASE: Mockad email preview för deletion confirmation
     */
    public function getDeletionConfirmationEmailPreview(string $token, string $email): array
    {
        return [
            'subject' => 'Bekräfta radering av dina personuppgifter',
            'to' => $email,
            'from' => 'andreas@atdev.me',
            'body' => "Hej,\n\nDu har begärt att få dina personuppgifter raderade från ATDev.\n\n" .
                      "För att bekräfta radering, klicka på länken nedan:\n\n" .
                      url("/gdpr/confirm-deletion/{$token}") . "\n\n" .
                      "Denna länk är giltig i 24 timmar.\n\n" .
                      "Om du inte begärt denna radering, ignorera detta meddelande.\n\n" .
                      "Med vänliga hälsningar,\nATDev",
        ];
    }
}
