<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteAuditRequest;
use App\Jobs\ProcessWebsiteAudit;
use App\Models\WebsiteAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class WebsiteAuditController extends Controller
{
    /**
     * Show the audit submission form
     */
    public function create(): View
    {
        return view('audits.create');
    }

    /**
     * Store a new audit request
     */
    public function store(WebsiteAuditRequest $request): RedirectResponse
    {
        // Check rate limiting (3 per day per IP)
        $ip = $request->ip();
        $key = 'audit-submission:'.$ip;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $hours = ceil($seconds / 3600);

            return back()->withErrors([
                'rate_limit' => "Du har nått dagsgränsen för webbplatsgranskningar. Försök igen om {$hours} timmar.",
            ])->withInput();
        }

        // Check for duplicate URL within last 7 days
        $recentAudit = WebsiteAudit::where('url', $request->url)
            ->where('created_at', '>', now()->subDays(7))
            ->first();

        if ($recentAudit) {
            return redirect()
                ->route('audits.status', $recentAudit->token)
                ->with('info', 'Denna webbplats har redan granskats nyligen. Här är den senaste rapporten.');
        }

        // Create audit
        $audit = WebsiteAudit::create([
            'url' => $request->url,
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
        ]);

        // Dispatch job
        ProcessWebsiteAudit::dispatch($audit);

        // Record rate limit
        RateLimiter::hit($key, 86400); // 24 hours

        return redirect()
            ->route('audits.status', $audit->token)
            ->with('success', 'Din webbplatsgranskning har skickats! Du kommer att få rapporten via e-post när den är klar.');
    }

    /**
     * Show audit status/result
     */
    public function status(string $token): View
    {
        $audit = WebsiteAudit::where('token', $token)->firstOrFail();

        return view('audits.status', compact('audit'));
    }
}
