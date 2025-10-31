<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebsiteAuditController extends Controller
{
    /**
     * Display a listing of audits
     */
    public function index(): View
    {
        $audits = WebsiteAudit::latest()->paginate(20);

        $stats = [
            'total' => WebsiteAudit::count(),
            'completed' => WebsiteAudit::completed()->count(),
            'processing' => WebsiteAudit::processing()->count(),
            'pending' => WebsiteAudit::pending()->count(),
            'failed' => WebsiteAudit::failed()->count(),
            'avg_score' => WebsiteAudit::completed()->avg('overall_score'),
        ];

        return view('admin.audits.index', compact('audits', 'stats'));
    }

    /**
     * Display the specified audit
     */
    public function show(WebsiteAudit $audit): View
    {
        return view('admin.audits.show', compact('audit'));
    }

    /**
     * Remove the specified audit
     */
    public function destroy(WebsiteAudit $audit): RedirectResponse
    {
        $audit->delete();

        return redirect()
            ->route('admin.audits.index')
            ->with('success', 'Granskning raderad.');
    }
}
