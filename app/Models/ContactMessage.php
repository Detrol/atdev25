<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'ip_address',
        'user_agent',
        'read',
        'reply_token',
        'email_message_id',
        'parent_id',
        'status',
        'is_admin_reply',
        'admin_user_id',
        'replied_at',
    ];

    protected $casts = [
        'read' => 'boolean',
        'is_admin_reply' => 'boolean',
        'replied_at' => 'datetime',
    ];

    /**
     * Boot the model - generate unique reply token and email message ID.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            if (empty($message->reply_token)) {
                $message->reply_token = Str::random(32);
            }

            if (empty($message->email_message_id)) {
                // Generate RFC 2822 compliant Message-ID
                // Format: unique-id.timestamp@domain (without angle brackets - Symfony adds them)
                $domain = config('services.mailgun.domain', 'atdev.me');
                $uniqueId = $message->reply_token ?: Str::random(32);
                $timestamp = time();
                $message->email_message_id = "{$uniqueId}.{$timestamp}@{$domain}";
            }
        });
    }

    /**
     * Relationships
     */

    /**
     * Parent meddelande (om detta är ett svar).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ContactMessage::class, 'parent_id');
    }

    /**
     * Alla svar på detta meddelande.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ContactMessage::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Admin-användare som svarade.
     */
    public function adminReplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    /**
     * Prisestimering kopplad till detta meddelande.
     */
    public function priceEstimation(): HasOne
    {
        return $this->hasOne(PriceEstimation::class);
    }

    /**
     * Scopes
     */

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope för meddelanden med status pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope för meddelanden med status replied.
     */
    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    /**
     * Scope för meddelanden med status closed.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope för original meddelanden (inte replies).
     */
    public function scopeOriginalMessages($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Methods
     */

    /**
     * Mark the message as read.
     */
    public function markAsRead(): void
    {
        $this->update(['read' => true]);
    }

    /**
     * Markera meddelandet som besvarat.
     */
    public function markAsReplied(): void
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now(),
        ]);
    }

    /**
     * Skapa ett svar på detta meddelande.
     */
    public function createReply(string $message, int $adminUserId): self
    {
        $reply = self::create([
            'parent_id' => $this->id,
            'name' => 'ATDev Admin',
            'email' => config('mail.from.address'),
            'message' => $message,
            'is_admin_reply' => true,
            'admin_user_id' => $adminUserId,
            'status' => 'replied',
            'read' => true,
        ]);

        // Uppdatera status på original meddelande
        $this->markAsReplied();

        return $reply;
    }

    /**
     * Hämta hela konversationen (parent + alla replies).
     */
    public function conversation()
    {
        // Om detta är ett svar, hämta parent först
        $parent = $this->parent_id ? $this->parent : $this;

        // Hämta alla replies inkl. detta meddelande om det är ett svar
        return self::where('id', $parent->id)
            ->orWhere('parent_id', $parent->id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Generera reply-to email adress för detta meddelande.
     */
    public function getReplyAddress(): string
    {
        // Använd MAILGUN_DOMAIN för inbound mail om det är satt, annars extrahera från from address
        // Detta är viktigt eftersom MX records ofta finns på en subdomain (t.ex. mg.atdev.me)
        $domain = config('services.mailgun.domain') ?: config('mail.from.address');

        // Om vi fick en email-adress, extrahera domändelen
        if (str_contains($domain, '@')) {
            $domain = substr($domain, strpos($domain, '@') + 1);
        }

        return "reply-{$this->reply_token}@{$domain}";
    }
}
