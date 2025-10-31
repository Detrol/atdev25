<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    private const CACHE_TTL = 3600; // 1 timme

    private const CHAT_HISTORY_LIMIT = 10;

    /**
     * Skapar portfolio-fokuserad kontextprompt
     */
    public function createPortfolioPrompt(): string
    {
        $profile = Profile::current();
        $projects = Project::published()->featured()->get();

        $prompt = $this->getAssistantIdentity($profile);
        $prompt .= "\n\n".$this->formatProfileInfo($profile);
        $prompt .= "\n\n".$this->formatProjectsInfo($projects);
        $prompt .= "\n\n".$this->getGeneralGuidelines();

        return $prompt;
    }

    /**
     * Anropar Anthropic API
     */
    public function callAnthropicApi(
        string $userMessage,
        string $chatHistory,
        string $model = 'claude-3-7-sonnet-20250219',
        int $maxTokens = 500,
        float $temperature = 0.7
    ): string {
        $apiKey = Config::get('services.anthropic.api_key');

        if (! $apiKey) {
            Log::error('Anthropic API key not configured');

            return 'AI-assistenten är inte korrekt konfigurerad. Kontakta administratören.';
        }

        $url = Config::get('services.anthropic.api_url', 'https://api.anthropic.com/v1/messages');

        // Skapa systemmeddelande med portfolio context
        $systemContent = [
            [
                'type' => 'text',
                'text' => $this->createPortfolioPrompt(),
            ],
        ];

        // Lägg till chatthistorik om den finns
        if (! empty($chatHistory)) {
            $systemContent[] = [
                'type' => 'text',
                'text' => "Tidigare konversation:\n".$chatHistory.
                    "\n\nAnvänd denna historik för kontext, men upprepa inte information som redan nämnts.",
            ];
        }

        // Lägg till HTML-formateringsinstruktioner
        $systemContent[] = [
            'type' => 'text',
            'text' => $this->getHtmlFormattingInstructions(),
        ];

        $data = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ],
            'system' => $systemContent,
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ];

        $headers = [
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ];

        try {
            $response = Http::withHeaders($headers)->timeout(30)->post($url, $data);

            if ($response->failed()) {
                Log::error('Anthropic API call failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return 'Tyvärr kunde jag inte besvara din fråga just nu. Vänligen försök igen senare.';
            }

            $responseData = $response->json();

            if (isset($responseData['error'])) {
                Log::error('Anthropic API returned an error', ['error' => $responseData['error']]);

                return 'Det uppstod ett problem när jag försökte svara. Kan du omformulera din fråga?';
            }

            if (! isset($responseData['content'][0]['text'])) {
                Log::error('Unexpected response format from Anthropic API', ['responseData' => $responseData]);

                return 'Jag kunde inte tolka svaret. Kan du ställa frågan på ett annat sätt?';
            }

            // Logga framgångsrikt anrop
            Log::info('Anthropic API call successful', [
                'model' => $model,
                'input_tokens' => $responseData['usage']['input_tokens'] ?? 0,
                'output_tokens' => $responseData['usage']['output_tokens'] ?? 0,
            ]);

            return $responseData['content'][0]['text'];
        } catch (\Throwable $e) {
            Log::error('Exception in callAnthropicApi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 'Ett oväntat fel inträffade. Vänligen försök igen senare.';
        }
    }

    /**
     * Hämtar chatthistorik för en session
     */
    public function getChatHistory(string $sessionId): string
    {
        $chats = Chat::where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->take(self::CHAT_HISTORY_LIMIT)
            ->get()
            ->reverse();

        return $chats->reduce(function ($carry, $chat) {
            return $carry."Användare: {$chat->question}\n".
                "Assistent: {$chat->answer}\n\n";
        }, '');
    }

    /**
     * Analyserar en webbplats med AI och genererar en rapport
     */
    public function analyzeWebsite(array $collectedData): array
    {
        Log::info('AIService: Starting website analysis', ['url' => $collectedData['url'] ?? 'unknown']);

        $apiKey = Config::get('services.anthropic.api_key');

        if (! $apiKey) {
            Log::error('AIService: Anthropic API key not configured for website analysis');
            throw new \Exception('AI-tjänsten är inte korrekt konfigurerad.');
        }

        $url = Config::get('services.anthropic.api_url', 'https://api.anthropic.com/v1/messages');

        // Skapa analysuppdraget
        Log::info('AIService: Creating analysis prompts...');
        $systemPrompt = $this->createWebsiteAnalysisPrompt();
        $userMessage = $this->formatCollectedDataForAnalysis($collectedData);
        Log::info('AIService: Prompts created', [
            'system_prompt_length' => strlen($systemPrompt),
            'user_message_length' => strlen($userMessage),
        ]);

        $data = [
            'model' => 'claude-3-7-sonnet-20250219',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ],
            'system' => [
                [
                    'type' => 'text',
                    'text' => $systemPrompt,
                ],
            ],
            'max_tokens' => 4000, // Längre för omfattande rapport
            'temperature' => 0.5, // Lägre för mer faktabaserad analys
        ];

        $headers = [
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ];

        try {
            Log::info('AIService: Calling Anthropic API...');
            $startTime = microtime(true);
            $response = Http::withHeaders($headers)->timeout(60)->post($url, $data);
            $apiCallDuration = microtime(true) - $startTime;
            Log::info('AIService: API call completed', [
                'duration' => round($apiCallDuration, 2),
                'status' => $response->status(),
            ]);

            if ($response->failed()) {
                Log::error('AIService: Anthropic API call failed for website analysis', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('Kunde inte analysera webbplatsen. Vänligen försök igen senare.');
            }

            $responseData = $response->json();
            Log::info('AIService: Response parsed', [
                'has_content' => isset($responseData['content'][0]['text']),
                'input_tokens' => $responseData['usage']['input_tokens'] ?? 0,
                'output_tokens' => $responseData['usage']['output_tokens'] ?? 0,
            ]);

            if (! isset($responseData['content'][0]['text'])) {
                Log::error('AIService: Unexpected response format from Anthropic API', ['responseData' => $responseData]);
                throw new \Exception('Fick ett oväntat svar från AI-tjänsten.');
            }

            $aiReport = $responseData['content'][0]['text'];
            Log::info('AIService: Report generated', ['report_length' => strlen($aiReport)]);

            // Extrahera scores från rapporten
            Log::info('AIService: Extracting scores from report...');
            $scores = $this->extractScoresFromReport($aiReport);
            Log::info('AIService: Scores extracted', $scores);

            Log::info('AIService: Website analysis completed successfully', [
                'url' => $collectedData['url'],
                'seo_score' => $scores['seo_score'],
                'performance_score' => $scores['performance_score'],
                'overall_score' => $scores['overall_score'],
            ]);

            return [
                'ai_report' => $aiReport,
                'seo_score' => $scores['seo_score'],
                'performance_score' => $scores['performance_score'],
                'overall_score' => $scores['overall_score'],
            ];
        } catch (\Throwable $e) {
            Log::error('AIService: Exception in analyzeWebsite', [
                'error' => $e->getMessage(),
                'url' => $collectedData['url'] ?? 'unknown',
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Skapar systemprompt för webbplatsanalys
     */
    private function createWebsiteAnalysisPrompt(): string
    {
        return <<<'PROMPT'
Du är en erfaren webbutvecklare och SEO-expert som granskar webbplatser professionellt.

Din uppgift är att analysera webbplatsdata och skapa en SVENSK rapport i Markdown-format.

RAPPORTSTRUKTUR:

## Sammanfattning
[2-3 meningar: Övergripande intryck, största styrkor, kritiska områden]

## SEO-Analys (Poäng: X/100)
Analysera och betygsätt:
- **Meta Tags**: Title, description, OG-tags (längd, kvalitet, relevans)
- **Heading-struktur**: H1-H6 hierarki, användning
- **Bildoptimering**: Alt-texter, antal bilder
- **Teknisk SEO**: Canonical, robots, schema markup

## Performance-Analys (Poäng: X/100)
Analysera och betygsätt:
- **Laddningstid**: Bedömning baserat på mätvärden
- **Sidstorlek**: HTML-storlek, totalt antal resurser
- **Resurser**: Scripts, stylesheets, bilder
- **Mobilvänlighet**: Viewport, responsivitet

## Övergripande Betyg (Poäng: X/100)
[Viktat medelvärde av SEO och Performance, med kort motivering]

## Förbättringsförslag
Prioriterad lista (1-8 förslag):

### 1. [Titel]

**Vad**: [Kort beskrivning]

**Varför**: [Business impact]

**Hur**: [Konkret steg]

**Svårighetsgrad**: Lätt/Medel/Svår

[Upprepa för varje förslag med tomma rader mellan Vad/Varför/Hur/Svårighetsgrad]

## Tekniska Rekommendationer
- Specifika verktyg eller tekniker
- Best practices för deras situation
- Långsiktiga förbättringsmöjligheter

VIKTIGT:
1. Poängen MÅSTE vara exakta tal (t.ex. "72/100"), inte intervall
2. Var konstruktiv, inte nedlåtande
3. Ge konkreta, genomförbara råd
4. Fokusera på affärsnytta, inte bara tekniska detaljer
5. Skriv på svenska med professionell ton
6. Använd Markdown för struktur (rubriker, listor, fetstil)
PROMPT;
    }

    /**
     * Formaterar insamlad data för AI-analys
     */
    private function formatCollectedDataForAnalysis(array $data): string
    {
        $url = $data['url'] ?? 'Okänd URL';

        $message = "Analysera följande webbplats:\n\n";
        $message .= "**URL**: {$url}\n\n";

        // Meta information
        $message .= "## META-INFORMATION\n";
        $message .= "**Title**: ".($data['meta']['title'] ?? 'Saknas')."\n";
        $message .= "**Description**: ".($data['meta']['description'] ?? 'Saknas')."\n";
        $message .= "**Meta Keywords**: ".($data['meta']['keywords'] ?: 'Saknas')."\n";
        $message .= "**Canonical**: ".($data['meta']['canonical'] ?: 'Saknas')."\n";
        $message .= "**Robots**: ".($data['meta']['robots'] ?: 'Saknas')."\n\n";

        // Open Graph
        if (! empty($data['meta']['og_tags'])) {
            $message .= "**Open Graph Tags**: ".count($data['meta']['og_tags'])." st\n";
        }

        // Headings
        $message .= "\n## RUBRIKER\n";
        if (! empty($data['headings'])) {
            $headingCounts = [];
            foreach ($data['headings'] as $heading) {
                $level = $heading['level'];
                $headingCounts["h{$level}"] = ($headingCounts["h{$level}"] ?? 0) + 1;
            }
            foreach ($headingCounts as $tag => $count) {
                $message .= "- {$tag}: {$count} st\n";
            }

            // Visa första rubriken av varje nivå
            $message .= "\nExempel:\n";
            $shown = [];
            foreach ($data['headings'] as $heading) {
                $level = $heading['level'];
                if (! isset($shown[$level])) {
                    $message .= "- h{$level}: {$heading['text']}\n";
                    $shown[$level] = true;
                }
            }
        } else {
            $message .= "Inga rubriker hittade\n";
        }

        // Images
        $message .= "\n## BILDER\n";
        $message .= "- Totalt: {$data['images']['total']} st\n";
        $message .= "- Med alt-text: {$data['images']['with_alt']} st\n";
        $message .= "- Utan alt-text: {$data['images']['without_alt']} st\n";
        $message .= "- Alt-text procent: {$data['images']['alt_percentage']}%\n";

        // Links
        $message .= "\n## LÄNKAR\n";
        $message .= "- Totalt: {$data['links']['total']} st\n";
        $message .= "- Interna: {$data['links']['internal']} st\n";
        $message .= "- Externa: {$data['links']['external']} st\n";

        // Performance
        $message .= "\n## PRESTANDA\n";
        $message .= "- Laddningstid: {$data['performance']['load_time']} sekunder\n";
        $message .= "- Sidstorlek: {$data['performance']['page_size_formatted']}\n";
        $message .= "- Antal scripts: {$data['performance']['scripts_count']} st\n";
        $message .= "- Antal stylesheets: {$data['performance']['stylesheets_count']} st\n";
        $message .= "- Antal bilder: {$data['performance']['images_count']} st\n";
        $message .= "- Totalt resurser: {$data['performance']['total_resources']} st\n";

        // Technical
        $message .= "\n## TEKNISKT\n";
        $message .= "- HTTPS: ".($data['technical']['has_ssl'] ? 'Ja' : 'Nej')."\n";
        $message .= "- Mobilvänlig (viewport): ".($data['technical']['mobile_friendly'] ? 'Ja' : 'Nej')."\n";
        $message .= "- Schema markup: ".($data['technical']['has_schema'] ? 'Ja' : 'Nej')."\n";

        if (! empty($data['technical']['technologies'])) {
            $message .= "- Identifierade teknologier: ".implode(', ', $data['technical']['technologies'])."\n";
        }

        // Content
        $message .= "\n## INNEHÅLL\n";
        $message .= "- Antal ord: {$data['content']['word_count']}\n";
        $message .= "- Antal paragrafer: {$data['content']['paragraph_count']}\n";
        $message .= "- Tillräckligt innehåll: ".($data['content']['has_sufficient_content'] ? 'Ja' : 'Nej (< 300 ord)')."\n";

        return $message;
    }

    /**
     * Extraherar scores från AI-rapporten
     */
    private function extractScoresFromReport(string $report): array
    {
        $scores = [
            'seo_score' => null,
            'performance_score' => null,
            'overall_score' => null,
        ];

        // Försök extrahera SEO-poäng
        if (preg_match('/SEO.*?Poäng:\s*(\d+)\/100/i', $report, $matches)) {
            $scores['seo_score'] = (int) $matches[1];
        }

        // Försök extrahera Performance-poäng
        if (preg_match('/Performance.*?Poäng:\s*(\d+)\/100/i', $report, $matches)) {
            $scores['performance_score'] = (int) $matches[1];
        }

        // Försök extrahera Övergripande betyg
        if (preg_match('/Övergripande.*?Poäng:\s*(\d+)\/100/i', $report, $matches)) {
            $scores['overall_score'] = (int) $matches[1];
        }

        // Fallback: beräkna scores om de inte kunde extraheras
        if ($scores['seo_score'] === null) {
            $scores['seo_score'] = 50; // Default neutral
        }
        if ($scores['performance_score'] === null) {
            $scores['performance_score'] = 50;
        }
        if ($scores['overall_score'] === null) {
            $scores['overall_score'] = (int) (($scores['seo_score'] + $scores['performance_score']) / 2);
        }

        return $scores;
    }

    /**
     * Assistentens identitet och grundläggande beteende
     */
    private function getAssistantIdentity(?Profile $profile): string
    {
        $name = $profile?->name ?? 'utvecklaren';

        return "Du är en AI-assistent som representerar {$name}s portfolio. ".
            'Din roll är att vara en teknisk rådgivare och besvara frågor om webbutveckling, arkitektur, bästa praxis och tekniska lösningar. '.
            'Du kan också berätta om projekten i portfolion och kompetensområden. '.
            "\n\n".
            'Kommunikationsstil:'.
            "\n".'- Var professionell men vänlig och tillgänglig'.
            "\n".'- Svara koncist och relevant på svenska'.
            "\n".'- Använd konkreta exempel från projekten när det är relevant'.
            "\n".'- Ge teknisk vägledning baserat på bästa praxis'.
            "\n".'- Undvik överdriven användning av utropstecken och emojis'.
            "\n".'- Formatera svar med HTML för bättre läsbarhet';
    }

    /**
     * Formaterar profilinformation
     */
    private function formatProfileInfo(?Profile $profile): string
    {
        if (! $profile) {
            return 'Profilinformation:\nIngen profilinformation tillgänglig ännu.';
        }

        $info = "Om {$profile->name}:\n";

        if ($profile->bio) {
            $info .= "Bio: {$profile->bio}\n";
        }

        if ($profile->email) {
            $info .= "E-post: {$profile->email}\n";
        }

        if ($profile->github_url) {
            $info .= "GitHub: {$profile->github_url}\n";
        }

        if ($profile->linkedin_url) {
            $info .= "LinkedIn: {$profile->linkedin_url}\n";
        }

        return $info;
    }

    /**
     * Formaterar projektinformation
     */
    private function formatProjectsInfo($projects): string
    {
        if ($projects->isEmpty()) {
            return "Projekt:\nInga projekt publicerade än.";
        }

        $info = "Projekt i portfolion:\n\n";

        foreach ($projects as $project) {
            $info .= "Projekt: {$project->title}\n";
            $info .= "Beskrivning: {$project->description}\n";

            if ($project->tech_stack) {
                $info .= "Tekniker: ".implode(', ', $project->tech_stack)."\n";
            }

            if ($project->live_url) {
                $info .= "Live-URL: {$project->live_url}\n";
            }

            $info .= "\n";
        }

        return $info;
    }

    /**
     * Allmänna riktlinjer för teknisk rådgivning
     */
    private function getGeneralGuidelines(): string
    {
        return "Riktlinjer för teknisk rådgivning:\n".
            "- Du kan ge allmän teknisk vägledning om webbutveckling, ramverk, arkitektur och bästa praxis\n".
            "- När du diskuterar tekniska lösningar, förklara varför något är en god praxis\n".
            "- Var ärlig om begränsningar och avvägningar mellan olika tekniska val\n".
            "- Uppmuntra användare att utforska och lära sig mer\n".
            "- Du kan referera till projekt i portfolion som exempel på tekniska lösningar\n".
            "- Om frågor går utanför ditt kompetensområde, var ärlig om det";
    }

    /**
     * HTML-formateringsinstruktioner (Tailwind CSS)
     */
    private function getHtmlFormattingInstructions(): string
    {
        return <<<'HTML'
Formatera ditt svar med HTML och Tailwind CSS-klasser för tydlighet:

RUBRIKER:
- <h3 class="text-lg font-bold mb-2">Huvudrubrik</h3>
- <h4 class="text-base font-semibold mb-2">Underrubrik</h4>

TEXT:
- <p class="mb-3">Normal text</p>
- <strong class="font-semibold">Viktig text</strong>
- <em class="italic">Kursiv text</em>

LISTOR:
- <ul class="list-disc pl-5 mb-3 space-y-1">
    <li>Punkt ett</li>
    <li>Punkt två</li>
  </ul>
- <ol class="list-decimal pl-5 mb-3 space-y-1">
    <li>Första steget</li>
    <li>Andra steget</li>
  </ol>

KOD:
- <code class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-sm font-mono">inline kod</code>
- <pre class="bg-gray-100 text-gray-800 p-3 rounded mb-3 overflow-x-auto text-sm"><code>kod block här</code></pre>

INFORMATIONSRUTOR:
- <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-3 text-sm">
    <strong class="text-blue-700">Info:</strong> Information här
  </div>
- <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 mb-3 text-sm">
    <strong class="text-yellow-700">Obs:</strong> Varning här
  </div>
- <div class="bg-green-50 border-l-4 border-green-500 p-3 mb-3 text-sm">
    <strong class="text-green-700">Tips:</strong> Tips här
  </div>

RIKTLINJER:
- Håll svaren koncisa och läsbara för chat-format
- Använd kod-formatering för tekniska termer, kommandon och filnamn
- Strukturera långa svar med rubriker och listor
- Använd informationsrutor för viktiga påpekanden
- Max 500 tokens per svar
HTML;
    }
}
