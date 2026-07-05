<?php

namespace App\Models;

use Database\Factories\PembayaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    /** @use HasFactory<PembayaranFactory> */
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'metode',
        'jumlah',
        'bukti',
        'status',
        'dibayar_pada',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'dibayar_pada' => 'datetime',
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
     * Filter berdasarkan status pembayaran.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Pembayaran yang menunggu verifikasi.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Pembayaran yang sudah diverifikasi.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Pencarian berdasarkan relasi peminjaman.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->whereHas('peminjaman', function ($q) use ($search) {
            $q->where('kode_booking', 'ilike', "%{$search}%")
              ->orWhere('nama', 'ilike', "%{$search}%")
              ->orWhere('no_hp', 'ilike', "%{$search}%");
        });
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /**
     * Mendapatkan jumlah terformat Rupiah.
     */
    public function getFormattedJumlahAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Mendapatkan URL bukti pembayaran.
     */
    public function getBuktiUrlAttribute(): ?string
    {
        if ($this->bukti) {
            return asset('storage/' . $this->bukti);
        }

        return null;
    }

    /**
     * Mendapatkan label status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    /**
     * Mendapatkan tipe badge berdasarkan status.
     */
    public function getStatusBadgeTypeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'verified' => 'success',
            'rejected' => 'danger',
            default => 'default',
        };
    }
}