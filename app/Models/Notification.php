<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Get the user who owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    /**
     * Get the notification type label.
     */
    public function getTypeLabelAttribute()
    {
        $types = [
            'sale' => 'Sale',
            'stock' => 'Stock',
            'expiry' => 'Expiry',
            'system' => 'System',
        ];
        return $types[$this->data['type'] ?? 'system'] ?? 'System';
    }

    /**
     * Get the notification icon.
     */
    public function getIconAttribute()
    {
        $icons = [
            'sale' => 'fa-receipt text-green-500',
            'stock' => 'fa-box text-yellow-500',
            'expiry' => 'fa-clock text-red-500',
            'system' => 'fa-cog text-blue-500',
        ];
        return $icons[$this->data['type'] ?? 'system'] ?? 'fa-bell text-gray-500';
    }
}