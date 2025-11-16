# Website Audit - Felsökningsguide

## Loggning

Hela audit-processen är nu fullständigt loggad för enkel felsökning. Varje steg skriver till Laravel's log.

### Loggnivåer

- **INFO**: Normal processflöde (start, slutförande, statusändringar)
- **WARNING**: Problem som hanterades (screenshot misslyckades, etc.)
- **ERROR**: Kritiska fel som stoppar processen

## Följa Loggarna Live

### Alternativ 1: Tail Laravel Log (Rekommenderat)
```bash
tail -f storage/logs/laravel.log
```

### Alternativ 2: Med Grep Filter (Bara Audit-relaterat)
```bash
tail -f storage/logs/laravel.log | grep -E "Controller:|WebsiteDataCollector:|AIService:|Processing website audit|audit_id"
```

### Alternativ 3: Med Färger (Kräver ccze)
```bash
tail -f storage/logs/laravel.log | ccze -A
```

## Loggflöde: Normal Audit

Här är exakt vad du förväntar dig att se i loggarna för en framgångsrik audit:

### 1. Controller (Submission)
```
Controller: Creating new audit
Controller: Audit created (audit_id: X, token: XXX)
Controller: Dispatching ProcessWebsiteAudit job
Controller: Job dispatched successfully
```

### 2. Job Start
```
Processing website audit (audit_id: X, url: https://example.com)
```

### 3. Data Collection (WebsiteDataCollector)
```
WebsiteDataCollector: Starting collection (url: https://example.com)
WebsiteDataCollector: Fetching HTML...
WebsiteDataCollector: HTML fetched (load_time: 2.5, html_size: 45000)
WebsiteDataCollector: Initializing DOM crawler
WebsiteDataCollector: Taking screenshot...
WebsiteDataCollector: Screenshot complete (path: audits/xxx.png)
WebsiteDataCollector: Extracting data...
WebsiteDataCollector: Collection complete (total_images: 15, total_links: 45)
```

### 4. Database Update
```
Website data collected (audit_id: X, data_size: 5000)
```

### 5. AI Analysis (AIService)
```
AIService: Starting website analysis (url: https://example.com)
AIService: Creating analysis prompts...
AIService: Prompts created (system_prompt_length: 2500, user_message_length: 3000)
AIService: Calling Anthropic API...
AIService: API call completed (duration: 15.5, status: 200)
AIService: Response parsed (input_tokens: 4500, output_tokens: 2000)
AIService: Report generated (report_length: 8000)
AIService: Extracting scores from report...
AIService: Scores extracted (seo_score: 75, performance_score: 68, overall_score: 72)
AIService: Website analysis completed successfully
```

### 6. Job Complete
```
AI analysis completed (audit_id: X, overall_score: 72)
Audit email sent (audit_id: X)
Website audit completed successfully (audit_id: X)
```

## Vanliga Problem & Lösningar

### Problem 1: Jobbet Startar Aldrig
**Symptom**: Inget loggas efter "Job dispatched successfully"

**Orsak**: Queue worker körs inte

**Lösning**:
```bash
# Starta queue worker
php artisan queue:work

# Eller med composer dev
composer dev
```

**Kontrollera**:
```bash
# Kolla om jobs ligger i kön
php artisan queue:failed
php artisan tinker
>>> \App\Models\WebsiteAudit::where('status', 'pending')->count()
```

### Problem 2: Browsershot Timeout
**Symptom**:
```
WebsiteDataCollector: Fetching HTML...
ERROR: Browsershot HTML fetch failed
```

**Orsaker**:
- Node/Puppeteer ej installerat
- Timeout (30s för långsam site)
- Webbplatsen blockerar headless browsers

**Lösning**:
```bash
# Kontrollera Node installation
node --version
npm --version

# Installera om Browsershot dependencies
npm install puppeteer

# Testa manuellt
php artisan tinker
>>> use Spatie\Browsershot\Browsershot;
>>> Browsershot::url('https://example.com')->bodyHtml();
```

### Problem 3: AI API Fel
**Symptom**:
```
AIService: Anthropic API call failed (status: 401)
```

**Orsaker**:
- API key saknas eller felaktig
- Rate limit (Anthropic)
- Nätverksproblem

**Lösning**:
```bash
# Kontrollera API key
php artisan tinker
>>> config('services.anthropic.api_key')

# Kolla .env
cat .env | grep ANTHROPIC

# Testa API direkt
curl https://api.anthropic.com/v1/messages \
  -H "x-api-key: $ANTHROPIC_API_KEY" \
  -H "anthropic-version: 2023-06-01" \
  -H "content-type: application/json" \
  -d '{"model":"claude-sonnet-4-5-20250929","messages":[{"role":"user","content":"test"}],"max_tokens":10}'
```

### Problem 4: Job Går Till Failed
**Symptom**: Status stannar på "processing" men jobbet finns i `failed_jobs`

**Kontrollera**:
```bash
# Visa failed jobs
php artisan queue:failed

# Visa specifikt failed job
php artisan queue:failed:show [id]

# Retry
php artisan queue:retry [id]

# Retry all
php artisan queue:retry all
```

### Problem 5: Screenshot Misslyckas
**Symptom**:
```
WARNING: Screenshot failed
```

**Effekt**: Auditen fortsätter men utan screenshot

**Orsak**: Vanligtvis permission-problem eller långsam site

**Åtgärd**:
```bash
# Kontrollera storage permissions
chmod -R 775 storage/app/public/audits

# Skapa symbolisk länk om den saknas
php artisan storage:link
```

## Debug-Kommandon

### Kolla Status på Audit
```bash
php artisan tinker
>>> $audit = \App\Models\WebsiteAudit::find(1)
>>> $audit->status
>>> $audit->collected_data  // Visa insamlad data
>>> $audit->ai_report       // Visa AI-rapport
```

### Kör Manuell Audit (Utan Queue)
```bash
php artisan tinker
>>> $audit = \App\Models\WebsiteAudit::find(1)
>>> $collector = app(\App\Services\WebsiteDataCollector::class)
>>> $data = $collector->collect($audit->url)
>>> $aiService = app(\App\Services\AIService::class)
>>> $analysis = $aiService->analyzeWebsite($data)
```

### Rensa Queue
```bash
# Rensa alla pending jobs
php artisan queue:clear

# Rensa failed jobs
php artisan queue:flush
```

### Kolla Queue Status
```bash
# Visa jobs tabellen
php artisan tinker
>>> \DB::table('jobs')->count()
>>> \DB::table('jobs')->get()
```

## Performance Monitoring

### Tidmätning
Varje steg loggar tidsåtgång:
- HTML fetch: Förväntat 1-5 sekunder
- Screenshot: Förväntat 2-10 sekunder
- Data extraction: Förväntat < 1 sekund
- AI analysis: Förväntat 10-30 sekunder

**Total processtid**: 20-60 sekunder (normal)

### API Token Usage
AI-analysen loggar token-användning:
```
input_tokens: ~4500 (systemPrompt + data)
output_tokens: ~2000 (rapport)
```

**Kostnad per audit**: ~$0.03-0.05 USD

## Log Rotation

Laravel roterar loggar automatiskt. För production:

**config/logging.php**:
```php
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => env('LOG_LEVEL', 'debug'),
    'days' => 14,
],
```

## Produktionstips

1. **Använd Redis Queue** istället för database driver
2. **Konfigurera Horizon** för queue monitoring
3. **Sätt upp Sentry** för error tracking
4. **Aktivera query logging** endast vid behov (performance)
5. **Använd separate log channels** för audit vs. general

## Support Checklist

När du rapporterar ett problem, inkludera:
- [ ] Audit ID
- [ ] URL som granskades
- [ ] Status från database (`select * from website_audits where id = X`)
- [ ] Relevanta loggrader (från submission till fel)
- [ ] Queue status (`php artisan queue:failed`)
- [ ] Environment info (`php artisan about`)
