<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\WebsiteAuditController as AdminAuditController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DemosController;
use App\Http\Controllers\GdprController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TechStackController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebsiteAuditController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tech-stack', [TechStackController::class, 'index'])->name('tech-stack');
Route::get('/demos', [DemosController::class, 'index'])->name('demos');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

// SEO routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Website Audit routes
Route::get('/audit', [WebsiteAuditController::class, 'create'])->name('audits.create');
Route::post('/audit', [WebsiteAuditController::class, 'store'])->name('audits.store');
Route::get('/audit/{token}', [WebsiteAuditController::class, 'status'])->name('audits.status');

// GDPR routes
Route::get('/privacy', [GdprController::class, 'privacy'])->name('gdpr.privacy');
Route::get('/cookies', [GdprController::class, 'cookies'])->name('gdpr.cookies');
Route::get('/gdpr/showcase', [GdprController::class, 'showcase'])->name('gdpr.showcase');
Route::post('/gdpr/export-demo', [GdprController::class, 'exportDemo'])->name('gdpr.export-demo');
Route::post('/gdpr/delete-demo', [GdprController::class, 'deleteDemo'])->name('gdpr.delete-demo');
Route::get('/gdpr/confirm-deletion/{token}', [GdprController::class, 'confirmDeletion'])->name('gdpr.confirm-deletion');
Route::post('/gdpr/process-deletion/{token}', [GdprController::class, 'processDeletion'])->name('gdpr.process-deletion');

// Webhook routes (utan CSRF protection)
// Körs på webhooks.atdev.me subdomain (utan Cloudflare proxy)
// CSRF undantag konfigurerat i bootstrap/app.php
Route::post('/mailgun/inbound', [WebhookController::class, 'handleInbound'])
    ->middleware('throttle:100,1');

// Admin routes (protected by auth middleware)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::resource('projects', AdminProjectController::class);
    Route::post('projects/{project}/screenshot', [AdminProjectController::class, 'screenshot'])->name('projects.screenshot');

    // Services
    Route::resource('services', AdminServiceController::class);

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Messages
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::post('messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Website Audits
    Route::get('audits', [AdminAuditController::class, 'index'])->name('audits.index');
    Route::get('audits/{audit}', [AdminAuditController::class, 'show'])->name('audits.show');
    Route::delete('audits/{audit}', [AdminAuditController::class, 'destroy'])->name('audits.destroy');
});
