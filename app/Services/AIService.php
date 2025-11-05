<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Profile;
use App\Models\Project;
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
                'technical_score' => $scores['technical_score'],
                'overall_score' => $scores['overall_score'],
            ]);

            return [
                'ai_report' => $aiReport,
                'seo_score' => $scores['seo_score'],
                'technical_score' => $scores['technical_score'],
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

## Teknisk Optimering (Poäng: X/100)
Analysera och betygsätt:
- **Kodkvalitet**: Inline styles/scripts, render-blocking resurser, minifiering
- **Bildoptimering**: WebP/AVIF-format, lazy loading, dimensioner, srcset
- **Tillgänglighet**: ARIA landmarks, formulärlabels, heading-hierarki
- **Best Practices**: Semantisk HTML5, DOM-djup, deprecated tags
- **Mobil Responsivitet**: Viewport, media queries, mobilmeny, touch targets
- **Konverteringsoptimering**: CTA-placering, kontaktinfo synlighet, formulär
- **Förtroendesignaler**: SSL, integritetspolicy, cookies, företagsinfo

## Övergripande Betyg (Poäng: X/100)
[Viktat medelvärde av SEO och Teknisk Optimering, med kort motivering]

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
        $message .= '**Title**: '.($data['meta']['title'] ?? 'Saknas')."\n";
        $message .= '**Description**: '.($data['meta']['description'] ?? 'Saknas')."\n";
        $message .= '**Meta Keywords**: '.($data['meta']['keywords'] ?: 'Saknas')."\n";
        $message .= '**Canonical**: '.($data['meta']['canonical'] ?: 'Saknas')."\n";
        $message .= '**Robots**: '.($data['meta']['robots'] ?: 'Saknas')."\n\n";

        // Open Graph
        if (! empty($data['meta']['og_tags'])) {
            $message .= '**Open Graph Tags**: '.count($data['meta']['og_tags'])." st\n";
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

        // Technical Optimization
        if (isset($data['technical_optimization'])) {
            $techOpt = $data['technical_optimization'];

            $message .= "\n## TEKNISK OPTIMERING\n";

            // Code Quality
            if (isset($techOpt['code_quality'])) {
                $cq = $techOpt['code_quality'];
                $message .= "\n### Kodkvalitet:\n";
                $message .= "- Inline styles: {$cq['inline_styles_count']} st\n";
                $message .= "- Element med style-attribut: {$cq['elements_with_style_attr']} st\n";
                $message .= "- Inline scripts: {$cq['inline_scripts_count']} st\n";
                $message .= "- Inline event handlers: {$cq['inline_event_handlers']} st\n";
                $message .= "- Blocking scripts i head: {$cq['blocking_scripts_in_head']} st\n";
                $message .= '- HTML-kommentarer: '.($cq['has_html_comments'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Verkar minifierad: '.($cq['appears_minified'] ? 'Ja' : 'Nej')."\n";
            }

            // Image Optimization
            if (isset($techOpt['image_optimization'])) {
                $io = $techOpt['image_optimization'];
                $message .= "\n### Bildoptimering:\n";
                $message .= "- Totalt bilder: {$io['total_images']} st\n";
                $message .= "- Med lazy loading: {$io['with_lazy_loading']} st ({$io['lazy_loading_percentage']}%)\n";
                $message .= "- Med dimensioner (width/height): {$io['with_dimensions']} st ({$io['dimensions_percentage']}%)\n";
                $message .= "- Med srcset: {$io['with_srcset']} st\n";
                $message .= "- <picture> element: {$io['picture_elements']} st\n";
                $message .= '- Moderna format (WebP/AVIF): '.($io['modern_formats_detected'] ? 'Ja' : 'Nej')."\n";
            }

            // Accessibility
            if (isset($techOpt['accessibility'])) {
                $acc = $techOpt['accessibility'];
                $message .= "\n### Tillgänglighet:\n";
                $message .= "- ARIA landmarks: {$acc['aria_landmarks']} st\n";
                $message .= "- Semantiska landmarks: {$acc['semantic_landmarks']} st\n";
                $message .= "- Formulär: {$acc['form_count']} st\n";
                $message .= "- Inputs: {$acc['input_count']} st\n";
                $message .= "- Labels: {$acc['label_count']} st (ratio: {$acc['label_ratio']})\n";
                $message .= "- H1: {$acc['headings']['h1']}, H2: {$acc['headings']['h2']}, H3: {$acc['headings']['h3']}\n";
                $message .= '- Korrekt heading-hierarki: '.($acc['has_proper_heading_hierarchy'] ? 'Ja' : 'Nej')."\n";
            }

            // Best Practices
            if (isset($techOpt['best_practices'])) {
                $bp = $techOpt['best_practices'];
                $message .= "\n### Best Practices:\n";
                $message .= "- Semantiska HTML5 tags: {$bp['semantic_tags_count']} st\n";
                $message .= "- Deprecated tags: {$bp['deprecated_tags_count']} st\n";
                $message .= "- Max DOM-djup: {$bp['max_dom_depth']} nivåer\n";
                $message .= "- Totalt element: {$bp['total_elements']} st\n";
                $message .= '- Viewport meta: '.($bp['has_viewport_meta'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Doctype: '.($bp['has_doctype'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Överdrivet stort DOM: '.($bp['excessive_dom_size'] ? 'Ja (>1500 element)' : 'Nej')."\n";
                $message .= '- Överdrivet djupt DOM: '.($bp['excessive_dom_depth'] ? 'Ja (>32 nivåer)' : 'Nej')."\n";
            }

            // Mobile Responsiveness
            if (isset($techOpt['mobile_responsiveness'])) {
                $mr = $techOpt['mobile_responsiveness'];
                $message .= "\n### Mobil Responsivitet:\n";
                $message .= '- Viewport meta: '.($mr['has_viewport'] ? 'Ja' : 'Nej')."\n";
                $message .= "- Media queries: {$mr['media_query_count']} st\n";
                $message .= '- Mobilmeny: '.($mr['has_mobile_menu'] ? 'Ja' : 'Nej')."\n";
                $message .= "- Responsiva bilder: {$mr['responsive_images']} st\n";
                $message .= '- Touch-vänliga targets: '.($mr['has_touch_targets'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Mobiloptimerad: '.($mr['mobile_optimized'] ? 'Ja' : 'Nej')."\n";
            }

            // CTA Effectiveness
            if (isset($techOpt['cta_effectiveness'])) {
                $cta = $techOpt['cta_effectiveness'];
                $message .= "\n### Konverteringsoptimering (CTA):\n";
                $message .= "- Knappar: {$cta['button_count']} st\n";
                $message .= "- Länkar: {$cta['link_count']} st\n";
                $message .= '- Telefonnummer synligt: '.($cta['phone_visible'] ? 'Ja' : 'Nej')." ({$cta['tel_links']} tel-länkar)\n";
                $message .= '- Email synlig: '.($cta['email_visible'] ? 'Ja' : 'Nej')." ({$cta['mailto_links']} mailto-länkar)\n";
                $message .= "- Kontaktformulär: {$cta['contact_forms']} st\n";
                $message .= "- Formulär med email-fält: {$cta['forms_with_email_field']} st\n";
                $message .= '- CTA i första skärmen: '.($cta['cta_in_first_screen'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Generiska knappar (dåligt): '.($cta['has_generic_button_text'] ? 'Ja' : 'Nej')."\n";
                $message .= "- CTA/innehåll-ratio: {$cta['cta_to_content_ratio']}\n";
            }

            // Trust Signals
            if (isset($techOpt['trust_signals'])) {
                $ts = $techOpt['trust_signals'];
                $message .= "\n### Förtroendesignaler:\n";
                $message .= '- SSL (HTTPS): '.($ts['has_ssl'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Integritetspolicy: '.($ts['has_privacy_policy'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Cookie consent: '.($ts['has_cookie_consent'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Företagsinfo i footer: '.($ts['footer_has_company_info'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Org-nummer: '.($ts['has_org_number'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Adress: '.($ts['has_address'] ? 'Ja' : 'Nej')."\n";
                $message .= '- Certifieringar synliga: '.($ts['displays_certifications'] ? 'Ja' : 'Nej')."\n";
                $message .= "- Förtroende-score: {$ts['trust_score']}/100\n";
            }
        }

        // Technical
        $message .= "\n## TEKNISKT\n";
        $message .= '- HTTPS: '.($data['technical']['has_ssl'] ? 'Ja' : 'Nej')."\n";
        $message .= '- Mobilvänlig (viewport): '.($data['technical']['mobile_friendly'] ? 'Ja' : 'Nej')."\n";
        $message .= '- Schema markup: '.($data['technical']['has_schema'] ? 'Ja' : 'Nej')."\n";

        if (! empty($data['technical']['technologies'])) {
            $message .= '- Identifierade teknologier: '.implode(', ', $data['technical']['technologies'])."\n";
        }

        // Content
        $message .= "\n## INNEHÅLL\n";
        $message .= "- Antal ord: {$data['content']['word_count']}\n";
        $message .= "- Antal paragrafer: {$data['content']['paragraph_count']}\n";
        $message .= '- Tillräckligt innehåll: '.($data['content']['has_sufficient_content'] ? 'Ja' : 'Nej (< 300 ord)')."\n";

        return $message;
    }

    /**
     * Extraherar scores från AI-rapporten
     */
    private function extractScoresFromReport(string $report): array
    {
        $scores = [
            'seo_score' => null,
            'technical_score' => null,
            'overall_score' => null,
        ];

        // Försök extrahera SEO-poäng
        if (preg_match('/SEO.*?Poäng:\s*(\d+)\/100/i', $report, $matches)) {
            $scores['seo_score'] = (int) $matches[1];
        }

        // Försök extrahera Teknisk Optimering-poäng
        if (preg_match('/Teknisk\s+Optimering.*?Poäng:\s*(\d+)\/100/i', $report, $matches)) {
            $scores['technical_score'] = (int) $matches[1];
        }

        // Försök extrahera Övergripande betyg
        if (preg_match('/Övergripande.*?Poäng:\s*(\d+)\/100/i', $report, $matches)) {
            $scores['overall_score'] = (int) $matches[1];
        }

        // Fallback: beräkna scores om de inte kunde extraheras
        if ($scores['seo_score'] === null) {
            $scores['seo_score'] = 50; // Default neutral
        }
        if ($scores['technical_score'] === null) {
            $scores['technical_score'] = 50;
        }
        if ($scores['overall_score'] === null) {
            $scores['overall_score'] = (int) (($scores['seo_score'] + $scores['technical_score']) / 2);
        }

        return $scores;
    }

    /**
     * Assistentens identitet och grundläggande beteende
     */
    private function getAssistantIdentity(?Profile $profile): string
    {
        $name = $profile?->name ?? 'ATDev';

        return 'Du är en DEMO-assistent som visar hur AI kan skräddarsys för företag. '.
            "Du representerar {$name}s portfolio och visar potentialen med att träna AI på företagsspecifik data. ".
            "\n\n".
            'VIKTIGT - Strikt ämnesbegränsning:'.
            "\n".'Du får ENDAST svara på frågor om:'.
            "\n".'1. Projekt i portfolion (beskrivningar, tekniker, funktioner)'.
            "\n".'2. Tjänster som erbjuds (webbutveckling, AI-integration, etc.)'.
            "\n".'3. Hur AI kan integreras i företag (allmänna exempel och möjligheter)'.
            "\n".'4. Hur en skräddarsydd AI-assistent kan tränas på företagets data'.
            "\n\n".
            'För ALLA andra frågor (programmering, teknisk rådgivning, allmänna frågor):'.
            "\n".'- Förklara vänligt att du är en begränsad demo-assistent'.
            "\n".'- Hänvisa till kontaktformuläret för andra frågor'.
            "\n".'- Ge exempel på vad användaren kan fråga dig om istället'.
            "\n\n".
            'Kommunikationsstil:'.
            "\n".'- Var professionell men vänlig och tillgänglig'.
            "\n".'- Svara koncist på svenska'.
            "\n".'- Betona att DU är ett exempel på hur AI kan anpassas'.
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
                $info .= 'Tekniker: '.implode(', ', $project->tech_stack)."\n";
            }

            if ($project->live_url) {
                $info .= "Live-URL: {$project->live_url}\n";
            }

            $info .= "\n";
        }

        return $info;
    }

    /**
     * Riktlinjer för demo-assistenten
     */
    private function getGeneralGuidelines(): string
    {
        return "Riktlinjer för demo-assistenten:\n".
            "- Du är ett EXEMPEL på hur AI kan skräddarsys med företagsspecifik data\n".
            "- Din kunskap är begränsad till projekten och tjänsterna i denna portfolio\n".
            "- Vid frågor om AI-integration: Ge konkreta exempel på hur företag kan använda skräddarsydd AI\n".
            "- Betona att samma teknologi kan tränas på DERAS data (produkter, dokument, FAQ, etc.)\n".
            "- Om någon frågar något utanför dina begränsningar: Var tydlig med att du är en demo och hänvisa till kontaktformuläret\n".
            "- Exempel på off-topic svar: 'Jag är en demo-assistent och kan endast svara på frågor om ATDevs projekt och tjänster. För teknisk rådgivning eller andra frågor, använd kontaktformuläret.'";
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

    /**
     * Estimerar projektpris och komplexitet baserat på beskrivning
     */
    public function estimateProjectPrice(string $description, string $serviceCategory): array
    {
        $apiKey = Config::get('services.anthropic.api_key');

        if (! $apiKey) {
            Log::error('Anthropic API key not configured for price estimation');
            throw new \Exception('AI-tjänsten är inte korrekt konfigurerad.');
        }

        $url = Config::get('services.anthropic.api_url', 'https://api.anthropic.com/v1/messages');

        $systemPrompt = $this->createPriceEstimationPrompt();

        // Inkludera tjänstekategori i user message för bättre context
        $userMessage = "Tjänstekategori: {$serviceCategory}\n\nBeskrivning: {$description}";

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
            'max_tokens' => 1500,
            'temperature' => 0, // 0 för maximal konsistens - samma input ger samma output
        ];

        $headers = [
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ];

        try {
            $response = Http::withHeaders($headers)->timeout(30)->post($url, $data);

            if ($response->failed()) {
                Log::error('Anthropic API call failed for price estimation', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('Kunde inte estimera projektet. Vänligen försök igen.');
            }

            $responseData = $response->json();

            if (! isset($responseData['content'][0]['text'])) {
                Log::error('Unexpected response format from Anthropic API', ['responseData' => $responseData]);
                throw new \Exception('Fick ett oväntat svar från AI-tjänsten.');
            }

            $aiResponse = $responseData['content'][0]['text'];

            // Parsa JSON från AI-svar
            return $this->parsePriceEstimation($aiResponse);
        } catch (\Throwable $e) {
            Log::error('Exception in estimateProjectPrice', [
                'error' => $e->getMessage(),
                'description' => $description,
            ]);

            throw $e;
        }
    }

    /**
     * Skapar systemprompt för prisestimering
     */
    private function createPriceEstimationPrompt(): string
    {
        return <<<'PROMPT'
Du är en erfaren webbutvecklare som analyserar projekt professionellt.

Din uppgift är att MATCHA projektbeskrivningen mot KONKRETA EXEMPEL nedan och välja rätt komplexitet.
Använd exemplen som referens - om beskrivningen liknar ett exempel, använd samma komplexitet.

Returnera ENDAST valid JSON med denna exakta struktur:

```json
{
  "project_type": "simple|webapp|api|maintenance|custom",
  "project_type_label": "Svensk beskrivande text av projekttyp",
  "complexity": 1-10,
  "complexity_label": "Kort svensk förklaring av komplexiteten (1-2 meningar)",
  "key_features": ["Feature 1", "Feature 2", "Feature 3"],
  "confidence": "high|medium|low",
  "notes": "Eventuella viktiga anteckningar på svenska"
}
```

---

## PROJEKTTYP: SIMPLE (Landing pages, portfolios, enkla webbsidor)

### KOMPLEXITET 1-2 (Mycket enkelt - statisk content)
**EXEMPEL:**
- "En enkel one-page landing page med hero, about-sektion och kontaktformulär"
- "Statisk portfolio med bara bilder och text, ingen admin"
- "Enkel företagspresentation med 3-4 sidor"
- "CV/resumé-sida med info om mig och länksamling"

**KÄNNETECKEN:**
✓ Maximalt 5 sidor
✓ Statisk eller minimal databas
✓ Kontaktformulär (enkelt)
✓ Responsiv design
✗ INTE: Admin-panel, användarhantering, komplex logik
✗ INTE: Bildupload, galleri med kategorier

**TRIGGERS:** "enkel", "statisk", "one-page", "landing page", "CV", "resumé"

---

### KOMPLEXITET 3-4 (Bas - standardfunktioner)
**EXEMPEL:**
- "Portfolio med projektgalleri, kontaktformulär och admin-panel för att hantera innehåll"
- "Företagssida med tjänstepresentation, galleri, kontakt och enkel admin"
- "Blog med kategorier, kommentarer och admin-panel"
- "Restaurant-sida med meny, bildgalleri, öppettider och bokningsformulär"

**KÄNNETECKEN:**
✓ Admin-panel för innehållshantering (CRUD)
✓ 5-8 sidor/sektioner
✓ Databas med 2-4 tabeller
✓ Bildgalleri med upload
✓ Kontaktformulär med validering
✓ Kategorisering/filtrering
✗ INTE: Användarregistrering, betalning, API-integrationer
✗ INTE: Real-time features, avancerad sökning

**TRIGGERS:** "admin-panel", "galleri", "hantera innehåll", "portfolio", "blog"

---

### KOMPLEXITET 5-6 (Medel - auth, CMS features)
**EXEMPEL:**
- "Medlemssida med användarregistrering, profiler, inloggning och medlems-content"
- "Enkel e-handel med produktkatalog, varukorg (utan betalning ännu)"
- "Event-plattform med eventregistrering, användarhantering och adminpanel"
- "Intern portal med användarroller, dokumentbibliotek och sökfunktion"

**KÄNNETECKEN:**
✓ Användarregistrering och autentisering
✓ Användarroller (admin, user, etc.)
✓ Databas med 5-8 tabeller
✓ Avancerad admin-panel (multiple modeller)
✓ Sökfunktionalitet
✓ Email-notifikationer
✗ INTE: Betalintegration, real-time, microservices
✗ INTE: Tredjepartsintegrationer (API:er)

**TRIGGERS:** "användarregistrering", "inloggning", "medlemmar", "roller", "auth", "profiler"

---

### KOMPLEXITET 7-8 (Komplex - avancerade features)
**EXEMPEL:**
- "E-handel med produkter, varukorg, Stripe-betalning, orderhantering och admin"
- "Booking-system med kalender, tillgänglighetskontroll, betalning och email-notiser"
- "CRM-system med leads, pipeline, email-integration och rapporter"
- "LMS-plattform med kurser, quiz, progress tracking och certifikat"

**KÄNNETECKEN:**
✓ Betalintegration (Stripe, Swish, etc.)
✓ Tredjepartsintegrationer (API:er)
✓ Komplex affärslogik
✓ Databas med 10+ tabeller
✓ Email-automationer
✓ Rapporter och statistik
✓ Filhantering och storage
✗ INTE: Real-time (websockets), microservices, AI/ML

**TRIGGERS:** "betalning", "Stripe", "Swish", "booking", "e-handel", "integration", "API"

---

### KOMPLEXITET 9-10 (Mycket komplex - enterprise)
**EXEMPEL:**
- "SaaS-plattform med multi-tenancy, prenumerationer, team-hantering och API"
- "Marknadsplats med säljare, köpare, betalningar, recensioner och meddelanden"
- "Real-time chat-applikation med websockets, notiser och fildelning"
- "Enterprise CMS med versionhantering, workflow, permissions och multi-site"

**KÄNNETECKEN:**
✓ Multi-tenancy / Multi-user med komplex logik
✓ Real-time features (websockets)
✓ Prenumerationer och fakturering
✓ REST API för externa integrationer
✓ Databas med 15+ tabeller
✓ Avancerad säkerhet och permissions
✓ Skalbarhet och caching
✓ Queue-system för bakgrundsjobb

**TRIGGERS:** "SaaS", "multi-tenant", "real-time", "websockets", "marknadsplats", "prenumeration", "enterprise"

---

## PROJEKTTYP: WEBAPP (SaaS, e-handel, booking-system, plattformar)

### KOMPLEXITET 1-2 (Enkel web app)
**EXEMPEL:**
- "Enkel todo-app med CRUD-operationer och basic UI"
- "Enkel quiz-app med frågor, svar och resultat"
- "Enkelt formular-verktyg för att skapa och dela formulär"

**KÄNNETECKEN:**
✓ Grundläggande CRUD
✓ Enkel databas (1-3 tabeller)
✓ Basic autentisering (kan vara)
✓ Enkelt användargränssnitt
✗ INTE: Komplex logik, integrationer, betalning

**TRIGGERS:** "enkel app", "todo", "quiz", "basic", "CRUD"

---

### KOMPLEXITET 3-4 (Basic webapp)
**EXEMPEL:**
- "Task management-app med projekt, tasks, deadlines och team-members"
- "Expense tracker med kategorier, rapporter och CSV-export"
- "Enkel CRM med contacts, deals och aktivitetslogg"
- "Inventory-system med produkter, lager och enkla rapporter"

**KÄNNETECKEN:**
✓ Multiple relaterade modeller (3-6 tabeller)
✓ Användarautentisering
✓ Basic rapporter/export
✓ CRUD på multiple resurser
✓ Email-notifikationer
✗ INTE: Betalning, API-integrationer, real-time

**TRIGGERS:** "task management", "CRM", "inventory", "tracking", "hantering"

---

### KOMPLEXITET 5-6 (Medel - integrationer, API)
**EXEMPEL:**
- "Projekt-management tool med Gantt-chart, team-samarbete och Slack-integration"
- "E-handel med produkter, varukorg, betalning och admin-dashboard"
- "HR-system med employee-hantering, ledighet, löner och rapporter"
- "Booking-plattform med kalender, tillgänglighet, betalning och SMS-påminnelser"

**KÄNNETECKEN:**
✓ En eller flera API-integrationer
✓ Betalintegration
✓ Komplex affärslogik
✓ Databas med 8-12 tabeller
✓ Avancerade rapporter
✓ File uploads och storage

**TRIGGERS:** "integration", "API", "betalning", "booking", "komplex", "projekt-management"

---

### KOMPLEXITET 7-8 (Komplex - payments, real-time)
**EXEMPEL:**
- "SaaS med prenumerationer, team-hantering, usage tracking och API"
- "Marknadsplats med multi-vendor, betalningar, recensioner och meddelanden"
- "Real-time collaboration tool med websockets, live-editing och notiser"
- "LMS med kurser, video-streaming, quiz, certifikat och betalning"

**KÄNNETECKEN:**
✓ Real-time features (websockets, broadcasting)
✓ Prenumerationer och recurring billing
✓ Multi-tenant arkitektur
✓ Externa API:er (både konsumera och tillhandahålla)
✓ Databas med 12-20 tabeller
✓ Background jobs och queues
✓ Caching och optimization

**TRIGGERS:** "SaaS", "prenumeration", "real-time", "marknadsplats", "collaboration", "multi-vendor"

---

### KOMPLEXITET 9-10 (Enterprise - microservices)
**EXEMPEL:**
- "Enterprise SaaS med microservices, multi-region, advanced analytics och white-labeling"
- "Fintech-plattform med transaktioner, compliance, KYC och audit-logs"
- "IoT-plattform med device-hantering, real-time data, analytics och alerts"
- "Social media-plattform med feeds, algoritmer, moderation och skalbarhet"

**KÄNNETECKEN:**
✓ Microservices-arkitektur
✓ Hög skalbarhet (1000+ users samtidigt)
✓ Advanced analytics och ML
✓ Compliance och säkerhet (GDPR, PCI-DSS)
✓ Multi-region deployment
✓ Omfattande API-ekosystem
✓ 20+ databastabeller

**TRIGGERS:** "enterprise", "microservices", "fintech", "IoT", "skalbarhet", "analytics", "ML"

---

## PROJEKTTYP: API (Backend/API-utveckling)

### KOMPLEXITET 1-2 (Enkelt API)
**EXEMPEL:**
- "REST API med 3-5 endpoints för att hämta och skapa blogposts"
- "API för att hantera kontakter (CRUD)"
- "Enkel webhook-mottagare för tredjepartstjänst"

**KÄNNETECKEN:**
✓ 3-5 endpoints
✓ Basic CRUD
✓ Enkel databas (1-2 tabeller)
✓ Token-baserad auth (kan vara)
✗ INTE: Komplex logik, externa integrationer

**TRIGGERS:** "enkel API", "REST", "CRUD", "få endpoints"

---

### KOMPLEXITET 3-4 (Basic API - CRUD + auth)
**EXEMPEL:**
- "REST API för task-app med auth, användare, projekt och tasks"
- "API för produktkatalog med kategorier, sökning och filtrering"
- "Backend för mobil-app med användare, profiler och innehåll"

**KÄNNETECKEN:**
✓ 10-15 endpoints
✓ JWT eller OAuth autentisering
✓ Multiple resurser (3-5 modeller)
✓ Validering och error handling
✓ API-dokumentation
✗ INTE: Externa integrationer, komplex logik

**TRIGGERS:** "REST API", "auth", "JWT", "OAuth", "mobil-app backend"

---

### KOMPLEXITET 5-6 (Medel - multiple resources)
**EXEMPEL:**
- "API för e-handel med produkter, orders, betalningar och admin"
- "Integration-API som kopplar ihop 2-3 tredjepartstjänster"
- "Backend för booking-system med kalender, tillgänglighet och notiser"

**KÄNNETECKEN:**
✓ 20-30 endpoints
✓ 1-2 externa API-integrationer
✓ Komplex affärslogik
✓ Background jobs
✓ Rate limiting
✓ Caching

**TRIGGERS:** "integrationer", "e-handel API", "booking API", "komplex logik"

---

### KOMPLEXITET 7-8 (Komplex - integrationer)
**EXEMPEL:**
- "API-gateway som aggregerar data från 5+ externa tjänster"
- "Payment-processing API med Stripe, Swish och webhook-hantering"
- "SaaS API med prenumerationer, usage tracking och billing"

**KÄNNETECKEN:**
✓ 40+ endpoints
✓ Multipla externa integrationer
✓ Webhooks (både in och ut)
✓ Advanced caching strategies
✓ Rate limiting per kund
✓ Extensive logging och monitoring

**TRIGGERS:** "API gateway", "payment processing", "multipla integrationer", "webhooks"

---

### KOMPLEXITET 9-10 (Enterprise - skalbarhet)
**EXEMPEL:**
- "Microservices API-ekosystem med service discovery och load balancing"
- "Real-time data pipeline med streaming och analytics"
- "GraphQL API med federation över multipla services"

**KÄNNETECKEN:**
✓ Microservices
✓ GraphQL eller gRPC
✓ Hög skalbarhet (1000+ req/sec)
✓ Distributed caching
✓ Message queues (RabbitMQ, Kafka)
✓ Monitoring och observability

**TRIGGERS:** "microservices", "GraphQL", "high-scale", "streaming", "gRPC"

---

## PROJEKTTYP: MAINTENANCE (Bugfixar, uppdateringar)

### KOMPLEXITET 1-2 (Minor fixes)
**EXEMPEL:**
- "Fixa 2-3 mindre buggar i kontaktformulär"
- "Uppdatera text och färger på landing page"
- "Lägg till ett nytt fält i formulär"

**TRIGGERS:** "bugfix", "mindre ändringar", "UI-tweaks", "text", "färger"

---

### KOMPLEXITET 3-4 (Small updates)
**EXEMPEL:**
- "Lägg till ny funktion för att exportera data till Excel"
- "Uppdatera betalintegration till ny version"
- "Implementera enkel sökning på befintlig sida"

**TRIGGERS:** "ny funktion", "uppdatering", "export", "sökning"

---

### KOMPLEXITET 5-6 (Medium updates)
**EXEMPEL:**
- "Refactor av admin-panel för bättre UX"
- "Migrera från gamla API-versionen till ny"
- "Implementera email-notifikationer för befintlig modul"

**TRIGGERS:** "refactor", "migration", "notifikationer", "förbättring"

---

### KOMPLEXITET 7-8 (Major refactoring)
**EXEMPEL:**
- "Omstrukturera databas och uppdatera alla queries"
- "Säkerhetsuppdatering över hela systemet"
- "Performance-optimization med caching-lager"

**TRIGGERS:** "omstrukturering", "säkerhet", "performance", "databas-migration"

---

### KOMPLEXITET 9-10 (Complete overhaul)
**EXEMPEL:**
- "Migrera från gamla Laravel 8 till Laravel 12 med arkitekturändringar"
- "Omskriva frontend från jQuery till Vue.js"
- "Implementera microservices-arkitektur från monolith"

**TRIGGERS:** "migration", "omskrivning", "arkitekturändringar", "microservices"

---

## PROJEKTTYP: CUSTOM (Specialanpassade lösningar)

Använd denna kategori när projektet inte passar någon annan typ eller är mycket unikt/specialiserat.

### KOMPLEXITET 1-2: Enkel custom solution
### KOMPLEXITET 3-4: Basic custom
### KOMPLEXITET 5-6: Medel komplexitet
### KOMPLEXITET 7-8: Komplex custom work
### KOMPLEXITET 9-10: Highly specialized

---

## TJÄNSTEKATEGORIER (Service Categories)

Användaren har valt en tjänstekategori som ger dig extra context om projekttypen.
Använd denna information för att bättre förstå vad kunden behöver och välja rätt komplexitet:

**web_development** - Webbutveckling från Grunden
- Skräddarsydda webbplatser och webbapplikationer
- Från enkla landningssidor till avancerade e-handelsplattformar och SaaS-lösningar
- SEO-optimerad struktur, CMS-integration, PWA-möjlighet

**mobile_app** - Mobilapputveckling
- Native och hybrid mobilappar för iOS och Android
- MVP-prototyper till fullskaliga applikationer
- API-integration, push-notifikationer, offline-funktionalitet

**bug_fixes** - Buggfix och Felsökning
- Snabb och effektiv felsökning av webbplatser och applikationer
- Identifiering och åtgärdande av buggar, prestandaproblem och säkerhetsbrister
- OBS: Ofta lägre komplexitet (1-4) eftersom det är punktinsatser

**performance** - Prestandaoptimering
- Optimering av laddningstider, databasfrågor, caching
- Core Web Vitals optimering (LCP, FID, CLS)
- CDN-konfiguration, lazy loading, code splitting
- Komplexitet beror på omfattning: enstaka optimering (2-4) vs helsystem (6-8)

**api_integration** - API-utveckling och Integration
- RESTful och GraphQL API-utveckling
- Integration med tredjepartstjänster (Stripe, Klarna, Mailgun, etc)
- API-dokumentation, autentisering (OAuth2, JWT)

**security** - Säkerhet och Compliance
- Säkerhetsanalys, penetrationstestning
- GDPR-anpassning, SSL-certifikat, säker datahantering
- OWASP Top 10 säkerhetsanalys
- Komplexitet beror på omfattning: basic audit (3-5) vs full compliance (7-9)

**maintenance** - Underhåll och Support
- Kontinuerligt underhåll, proaktiv övervakning
- Säkerhetsuppdateringar, backups, teknisk support
- Komplexitet beror på omfattning: basic support (2-4) vs 24/7 full monitoring (6-8)

**modernization** - Modernisering och Uppgradering
- Modernisera äldre webbplatser och system
- Framework-uppgraderingar, migration till cloud
- Containerisering, CI/CD implementation
- Komplexitet beror på omfattning: enkel upgrade (3-5) vs complete overhaul (8-10)

**VIKTIGT:** Använd tjänstekategorin som VÄGLEDNING men välj fortfarande project_type och complexity baserat på den faktiska beskrivningen och exemplen ovan.

---

## VIKTIGA REGLER

1. **MATCHA mot exempel** - Om beskrivningen liknar ett exempel, använd samma komplexitet
2. **TRIGGERS är nyckelord** - Leta efter triggers i beskrivningen
3. **Var konsistent** - Samma typ av beskrivning ska ALLTID ge samma komplexitet
4. **Temperature = 0** betyder att du måste vara 100% deterministisk
5. **Returnera ENDAST valid JSON** - ingen annan text före eller efter
6. **Alla texter på svenska**
7. **confidence = high** om det matchar exempel, **medium** om tveksam, **low** om vag beskrivning
8. **KEY FEATURES**: Identifiera 4-8 huvudfunktioner från beskrivningen, var specifik och konkret

PROMPT;
    }

    /**
     * Parsear JSON från AI-svar för prisestimering
     */
    private function parsePriceEstimation(string $response): array
    {
        // Ta bort eventuell markdown code block wrapper
        $response = preg_replace('/^```json\s*/', '', $response);
        $response = preg_replace('/\s*```$/', '', $response);
        $response = trim($response);

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to parse price estimation JSON', [
                'response' => $response,
                'error' => json_last_error_msg(),
            ]);

            throw new \Exception('Kunde inte tolka AI-svar. Vänligen försök igen.');
        }

        // Validera att alla nödvändiga fält finns
        $requiredFields = [
            'project_type',
            'project_type_label',
            'complexity',
            'complexity_label',
            'key_features',
        ];

        foreach ($requiredFields as $field) {
            if (! isset($data[$field])) {
                Log::error('Missing required field in price estimation', [
                    'field' => $field,
                    'data' => $data,
                ]);

                throw new \Exception('Ofullständig estimering från AI. Vänligen försök igen.');
            }
        }

        return $data;
    }

    /**
     * Analyserar en matbeskrivning för allergener med AI.
     */
    public function analyzeMenuAllergens(string $dishDescription): array
    {
        $apiKey = Config::get('services.anthropic.api_key');

        if (! $apiKey) {
            Log::error('Anthropic API key not configured for allergen analysis');
            throw new \Exception('AI-tjänsten är inte korrekt konfigurerad.');
        }

        $url = Config::get('services.anthropic.api_url', 'https://api.anthropic.com/v1/messages');

        $systemPrompt = $this->createAllergenAnalysisPrompt();

        $data = [
            'model' => 'claude-3-7-sonnet-20250219',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $dishDescription,
                ],
            ],
            'system' => [
                [
                    'type' => 'text',
                    'text' => $systemPrompt,
                ],
            ],
            'max_tokens' => 800,
            'temperature' => 0.3, // Låg för faktabaserad, konsistent analys
        ];

        $headers = [
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ];

        try {
            $response = Http::withHeaders($headers)->timeout(30)->post($url, $data);

            if ($response->failed()) {
                Log::error('Anthropic API call failed for allergen analysis', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('Kunde inte analysera allergener. Vänligen försök igen.');
            }

            $responseData = $response->json();

            if (! isset($responseData['content'][0]['text'])) {
                Log::error('Unexpected response format from Anthropic API', ['responseData' => $responseData]);
                throw new \Exception('Fick ett oväntat svar från AI-tjänsten.');
            }

            $aiResponse = $responseData['content'][0]['text'];

            Log::info('Allergen analysis successful', [
                'dish' => substr($dishDescription, 0, 100),
                'input_tokens' => $responseData['usage']['input_tokens'] ?? 0,
                'output_tokens' => $responseData['usage']['output_tokens'] ?? 0,
            ]);

            // Parsa JSON från AI-svar
            return $this->parseAllergenAnalysis($aiResponse);
        } catch (\Throwable $e) {
            Log::error('Exception in analyzeMenuAllergens', [
                'error' => $e->getMessage(),
                'dish' => $dishDescription,
            ]);

            throw $e;
        }
    }

    /**
     * Skapar systemprompt för allergenanalys
     */
    private function createAllergenAnalysisPrompt(): string
    {
        $allergens = config('allergens.allergens', []);

        $allergenList = '';
        foreach ($allergens as $key => $allergen) {
            $allergenList .= "- **{$allergen['name']}** ({$allergen['icon']}): ".
                implode(', ', array_slice($allergen['keywords'], 0, 8))."\n";
        }

        return <<<PROMPT
Du är en expertnutritionist och allergenspecialist som analyserar maträtter för allergener.

Din uppgift är att identifiera allergener i en matbeskrivning baserat på ingredienser.

## TILLGÄNGLIGA ALLERGENER:

{$allergenList}

## INSTRUKTIONER:

1. Läs matbeskrivningen noggrant
2. Identifiera alla ingredienser som nämns
3. Matcha ingredienser mot allergenkeywords ovan
4. Returnera ENDAST valid JSON enligt formatet nedan

## JSON-FORMAT (EXAKT):

```json
{
  "dish_name": "Namn på rätten (extrahera från beskrivningen)",
  "allergens": [
    {
      "allergen": "gluten",
      "name": "Gluten",
      "icon": "🌾",
      "confidence": "high",
      "reason": "Innehåller pasta som är gjord av vete"
    }
  ],
  "dietary_info": {
    "vegan": false,
    "vegetarian": true,
    "gluten_free": false,
    "lactose_free": false
  },
  "notes": "Eventuella viktiga anteckningar på svenska"
}
```

## CONFIDENCE LEVELS:
- **high**: Ingrediens explicit nämnd (t.ex. "med parmesan")
- **medium**: Trolig ingrediens (t.ex. "caesardressing" innehåller troligen ägg och fisk)
- **low**: Möjlig ingrediens men osäker

## VIKTIGA REGLER:

1. Var KONSISTENT - samma ingrediens ska alltid ge samma allergen
2. Inkludera ENDAST allergener som faktiskt finns i beskrivningen
3. Om osäker, använd "medium" eller "low" confidence
4. Ange ALLTID en kort, konkret "reason" på svenska
5. Returnera ENDAST valid JSON - ingen annan text
6. dietary_info ska vara boolean (true/false)
7. Om inga allergener hittas, returnera tom array för "allergens"

## EXEMPEL:

Input: "Carbonara - Pasta med ägg, bacon och parmesanost. 145 kr"

Output:
```json
{
  "dish_name": "Carbonara",
  "allergens": [
    {
      "allergen": "gluten",
      "name": "Gluten",
      "icon": "🌾",
      "confidence": "high",
      "reason": "Innehåller pasta som är gjord av vete"
    },
    {
      "allergen": "eggs",
      "name": "Ägg",
      "icon": "🥚",
      "confidence": "high",
      "reason": "Ägg nämns explicit i beskrivningen"
    },
    {
      "allergen": "lactose",
      "name": "Laktos/Mjölk",
      "icon": "🥛",
      "confidence": "high",
      "reason": "Innehåller parmesanost (mejeriprodukt)"
    }
  ],
  "dietary_info": {
    "vegan": false,
    "vegetarian": false,
    "gluten_free": false,
    "lactose_free": false
  },
  "notes": "Klassisk italiensk pastarätt"
}
```

Analysera nu den givna matbeskrivningen och returnera ENDAST valid JSON.
PROMPT;
    }

    /**
     * Parsear JSON från AI-svar för allergenanalys
     */
    private function parseAllergenAnalysis(string $response): array
    {
        // Ta bort eventuell markdown code block wrapper
        $response = preg_replace('/^```json\s*/', '', $response);
        $response = preg_replace('/\s*```$/', '', $response);
        $response = trim($response);

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Failed to parse allergen analysis JSON', [
                'response' => $response,
                'error' => json_last_error_msg(),
            ]);

            throw new \Exception('Kunde inte tolka AI-svar. Vänligen försök igen.');
        }

        // Validera att nödvändiga fält finns
        if (! isset($data['allergens']) || ! is_array($data['allergens'])) {
            Log::error('Missing allergens field in analysis', ['data' => $data]);

            // Returnera default tom analys istället för att kasta exception
            return [
                'dish_name' => $data['dish_name'] ?? 'Okänd rätt',
                'allergens' => [],
                'dietary_info' => [
                    'vegan' => false,
                    'vegetarian' => false,
                    'gluten_free' => true,
                    'lactose_free' => true,
                ],
                'notes' => 'Kunde inte identifiera allergener.',
            ];
        }

        return $data;
    }
}
