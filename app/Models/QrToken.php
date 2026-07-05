<?php

namespace App\Models;

use Database\Factories\QrTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class QrToken extends Model
{
    /** @use HasFactory<QrTokenFactory> */
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'token',
        'type',
        'expired_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /**
     * Filter token yang masih valid (belum expired & belum dipakai).
     */
    public function scopeValid($query)
    {
        return $query->where('expired_at', '>', now())
                     ->whereNull('used_at');
    }

    /**
     * Filter berdasarkan tipe (pickup/return).
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Cek apakah token sudah expired.
     */
    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    /**
     * Cek apakah token sudah digunakan.
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Cek apakah token masih valid.
     */
    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isUsed();
    }

    /**
     * Tandai token sebagai sudah digunakan.
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * Generate token unik untuk QR Code.
     */
    public static function generateToken(): string
    {
        do {
            $token = Str::uuid()->toString();
        } while (static::where('token', $token)->exists());

        return $token;
    }

    /**
     * Mendapatkan label tipe.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'pickup' => 'Pengambilan',
            'return' => 'Pengembalian',
            default => ucfirst($this->type),
        };
    }
}