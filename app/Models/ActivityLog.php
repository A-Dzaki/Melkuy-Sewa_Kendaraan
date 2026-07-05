<?php

namespace App\Models;

use Database\Factories\ActivityLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /** @use HasFactory<ActivityLogFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'user_agent',
    ];

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /**
     * Filter berdasarkan user tertentu.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Pencarian berdasarkan aktivitas atau IP address.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('activity', 'ilike', "%{$search}%")
              ->orWhere('ip_address', 'ilike', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('name', 'ilike', "%{$search}%");
              });
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
     * Mendapatkan browser dari user agent.
     */
    public function getBrowserAttribute(): string
    {
        $agent = $this->user_agent;

        if (str_contains($agent, 'Chrome')) return 'Chrome';
        if (str_contains($agent, 'Firefox')) return 'Firefox';
        if (str_contains($agent, 'Safari')) return 'Safari';
        if (str_contains($agent, 'Edge')) return 'Edge';

        return 'Unknown';
    }
}