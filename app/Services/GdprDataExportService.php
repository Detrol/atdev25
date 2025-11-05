<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ContactMessage;

class GdprDataExportService
{
    /**
     * Exportera all användardata för given email
     *
     * SHOWCASE: Detta är en demo-implementation som visar hur GDPR data export
     * skulle fungera i en riktig applikation med användarregistrering.
     */
    public function exportUserData(string $email): array
    {
        return [
            'export_date' => now()->toIso8601String(),
            'email' => $email,
            'data' => [
                'contact_messages' => $this->getContactMessages($email),
                'chat_history' => $this->getChatHistory($email),
            ],
            'metadata' => [
                'data_retention_policy' => 'Data sparas i 2 år från senaste aktivitet',
                'data_controller' => 'ATDev - Andreas Thun',
                'contact_email' => 'andreas@atdev.me',
            ],
            'your_rights' => [
                'right_to_access' => 'Du har rätt att få tillgång till dina personuppgifter',
                'right_to_rectification' => 'Du har rätt att korrigera felaktiga uppgifter',
                'right_to_erasure' => 'Du har rätt att få dina uppgifter raderade',
                'right_to_portability' => 'Du har rätt att få dina uppgifter i maskinläsbart format',
                'right_to_object' => 'Du har rätt att invända mot behandling',
            ],
        ];
    }

    /**
     * Hämta alla kontaktmeddelanden från given email
     */
    private function getContactMessages(string $email): array
    {
        $messages = ContactMessage::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();

        return $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'message' => $message->message,
                'status' => $message->status,
                'ip_address' => $message->ip_address,
                'user_agent' => $message->user_agent,
                'created_at' => $message->created_at->toIso8601String(),
                'read_at' => $message->read_at?->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Hämta all chat-historik (baserat på email-sökning i meddelanden)
     *
     * NOTE: I produktionssystem skulle chat kunna kopplas direkt till användare
     */
    private function getChatHistory(string $email): array
    {
        // Sök efter chat-sessioner där email nämnts i meddelanden
        $chats = Chat::where('question', 'LIKE', "%{$email}%")
            ->orWhere('answer', 'LIKE', "%{$email}%")
            ->orderBy('created_at', 'desc')
            ->get();

        return $chats->map(function ($chat) {
            return [
                'id' => $chat->id,
                'session_id' => $chat->session_id,
                'question' => $chat->question,
                'answer' => $chat->answer,
                'created_at' => $chat->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Generera JSON-fil för nedladdning
     */
    public function generateExportFile(string $email): string
    {
        $data = $this->exportUserData($email);

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Skapa GDPR-compliant data export summary
     */
    public function getDataSummary(string $email): array
    {
        $contactCount = ContactMessage::where('email', $email)->count();
        $chatCount = Chat::where('question', 'LIKE', "%{$email}%")
            ->orWhere('answer', 'LIKE', "%{$email}%")
            ->count();

        return [
            'email' => $email,
            'summary' => [
                'contact_messages' => $contactCount,
                'chat_sessions' => $chatCount,
            ],
            'last_activity' => $this->getLastActivity($email),
        ];
    }

    /**
     * Hämta senaste aktivitet för email
     */
    private function getLastActivity(string $email): ?string
    {
        $latestContact = ContactMessage::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();

        return $latestContact?->created_at->toIso8601String();
    }
}
