<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Models\Chat;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AIAssistantController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle incoming chat request (synchronous)
     */
    public function chat(ChatRequest $request): JsonResponse
    {
        $sessionId = $request->input('session_id');
        $userMessage = $request->input('message');

        // Rate limiting check
        if (! $this->checkThrottling($sessionId)) {
            return response()->json([
                'error' => 'För många förfrågningar. Vänligen försök igen om en minut.',
            ], 429);
        }

        try {
            // Hämta chatthistorik
            $chatHistory = $this->aiService->getChatHistory($sessionId);

            // Anropa AI API synkront
            $aiResponse = $this->aiService->callAnthropicApi(
                userMessage: $userMessage,
                chatHistory: $chatHistory
            );

            // Spara i databas
            Chat::create([
                'session_id' => $sessionId,
                'question' => $userMessage,
                'answer' => $aiResponse,
            ]);

            Log::info('Chat processed successfully', [
                'sessionId' => $sessionId,
                'messageLength' => strlen($userMessage),
            ]);

            return response()->json([
                'success' => true,
                'response' => $aiResponse,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error processing chat', [
                'sessionId' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Ett fel uppstod när vi försökte behandla din förfrågan. Vänligen försök igen senare.',
            ], 500);
        }
    }

    /**
     * Check rate limiting for session
     */
    private function checkThrottling(string $sessionId): bool
    {
        $key = 'chat_throttle_'.$sessionId;

        return RateLimiter::attempt(
            $key,
            $maxAttempts = 5, // 5 requests
            function () {
                // Executed if rate limit not exceeded
            },
            $decaySeconds = 60 // Per minute
        );
    }

    /**
     * Get chat history for a session
     */
    public function getChatHistory(Request $request): JsonResponse
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return response()->json(['error' => 'Session-ID saknas'], 400);
        }

        try {
            $chats = Chat::where('session_id', $sessionId)
                ->orderBy('created_at', 'asc')
                ->get(['question', 'answer', 'created_at']);

            return response()->json([
                'success' => true,
                'history' => $chats,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error fetching chat history', [
                'sessionId' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Kunde inte hämta chatthistorik',
            ], 500);
        }
    }
}
