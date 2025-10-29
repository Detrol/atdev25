<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'ip_address',
        'user_agent',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead(): void
    {
        $this->update(['read' => true]);
    }
}
