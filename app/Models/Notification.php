<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<NotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /**
     * Filter notifikasi yang belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Filter berdasarkan tipe notifikasi.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Pencarian berdasarkan judul atau pesan.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'ilike', "%{$search}%")
              ->orWhere('message', 'ilike', "%{$search}%");
        });
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /**
     * Mendapatkan waktu relatif (e.g. "5 menit yang lalu").
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Mendapatkan ikon berdasarkan tipe.
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'booking' => '📋',
            'payment' => '💳',
            'pickup' => '🚗',
            'return' => '🔄',
            'warning' => '⚠️',
            default => '🔔',
        };
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Tandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public static function markAllAsRead(): void
    {
        static::unread()->update(['is_read' => true]);
    }
}