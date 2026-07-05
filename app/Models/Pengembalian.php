<?php

namespace App\Models;

use Database\Factories\PengembalianFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    /** @use HasFactory<PengembalianFactory> */
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'tanggal_dikembalikan',
        'kondisi',
        'denda',
        'catatan',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_dikembalikan' => 'datetime',
            'denda' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    // ──────────────────────────────────────────
    // Constants
    // ──────────────────────────────────────────

    /**
     * Koordinat lokasi rental (placeholder — ubah sesuai lokasi asli).
     */
    public const RENTAL_LATITUDE = -6.200000;
    public const RENTAL_LONGITUDE = 106.816666;
    public const RADIUS_METERS = 100;

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

    /**
     * Filter pengembalian yang memiliki denda.
     */
    public function scopeHasDenda($query)
    {
        return $query->where('denda', '>', 0);
    }

    /**
     * Filter berdasarkan kondisi kendaraan.
     */
    public function scopeByKondisi($query, string $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /**
     * Mendapatkan denda terformat Rupiah.
     */
    public function getFormattedDendaAttribute(): string
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }

    /**
     * Mendapatkan label kondisi yang lebih deskriptif.
     */
    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'lecet' => 'Lecet Ringan',
            'rusak' => 'Rusak',
            'hilang' => 'Hilang',
            default => ucfirst($this->kondisi),
        };
    }

    /**
     * Mendapatkan tipe badge berdasarkan kondisi.
     */
    public function getKondisiBadgeTypeAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'success',
            'lecet' => 'warning',
            'rusak' => 'danger',
            'hilang' => 'danger',
            default => 'default',
        };
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Cek apakah ada denda.
     */
    public function hasDenda(): bool
    {
        return $this->denda > 0;
    }

    /**
     * Validasi apakah lokasi pengembalian berada dalam radius rental.
     *
     * Menggunakan rumus Haversine untuk menghitung jarak antara
     * dua titik koordinat di permukaan bumi.
     */
    public function isValidLocation(): bool
    {
        if ($this->latitude === null || $this->longitude === null) {
            return false;
        }

        $distance = $this->calculateDistanceToRental();

        return $distance <= self::RADIUS_METERS;
    }

    /**
     * Hitung jarak ke lokasi rental dalam meter.
     */
    public function calculateDistanceToRental(): float
    {
        return self::haversineDistance(
            (float) $this->latitude,
            (float) $this->longitude,
            self::RENTAL_LATITUDE,
            self::RENTAL_LONGITUDE,
        );
    }

    /**
     * Haversine formula — menghitung jarak 2 titik GPS dalam meter.
     */
    public static function haversineDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
    ): float {
        $earthRadius = 6371000; // dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}