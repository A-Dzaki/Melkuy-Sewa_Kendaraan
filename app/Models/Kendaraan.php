<?php

namespace App\Models;

use Database\Factories\KendaraanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kendaraan extends Model
{
    /** @use HasFactory<KendaraanFactory> */
    use HasFactory;

    protected $table = 'kendaraans';

    protected $fillable = [
        'kode_kendaraan',
        'jenis',
        'merk',
        'nama',
        'tahun',
        'warna',
        'transmisi',
        'harga_sewa',
        'status',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'harga_sewa' => 'decimal:2',
            'tahun' => 'integer',
        ];
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function images(): HasMany
    {
        return $this->hasMany(KendaraanImage::class);
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /**
     * Filter hanya kendaraan yang tersedia.
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    /**
     * Filter berdasarkan jenis kendaraan.
     */
    public function scopeByJenis($query, string $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    /**
     * Filter berdasarkan merk kendaraan.
     */
    public function scopeByMerk($query, string $merk)
    {
        return $query->where('merk', $merk);
    }

    /**
     * Filter berdasarkan transmisi.
     */
    public function scopeByTransmisi($query, string $transmisi)
    {
        return $query->where('transmisi', $transmisi);
    }

    /**
     * Filter berdasarkan range harga.
     */
    public function scopeByHargaRange($query, ?float $min = null, ?float $max = null)
    {
        if ($min !== null) {
            $query->where('harga_sewa', '>=', $min);
        }
        if ($max !== null) {
            $query->where('harga_sewa', '<=', $max);
        }

        return $query;
    }

    /**
     * Pencarian berdasarkan nama, merk, atau kode.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'ilike', "%{$search}%")
              ->orWhere('merk', 'ilike', "%{$search}%")
              ->orWhere('kode_kendaraan', 'ilike', "%{$search}%");
        });
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /**
     * Mendapatkan harga terformat dalam Rupiah.
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_sewa, 0, ',', '.');
    }

    /**
     * Mendapatkan thumbnail utama.
     */
    public function getThumbnailAttribute(): ?KendaraanImage
    {
        return $this->images->firstWhere('thumbnail', true)
            ?? $this->images->first();
    }

    /**
     * Mendapatkan URL thumbnail.
     */
    public function getThumbnailUrlAttribute(): string
    {
        $thumbnail = $this->thumbnail;

        if ($thumbnail) {
            return asset('storage/' . $thumbnail->image);
        }

        return asset('images/placeholder-vehicle.png');
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Generate kode kendaraan unik (VH-XXXX).
     */
    public static function generateKode(): string
    {
        do {
            $kode = 'VH-' . strtoupper(substr(uniqid(), -6));
        } while (static::where('kode_kendaraan', $kode)->exists());

        return $kode;
    }

    /**
     * Cek apakah kendaraan bisa dipesan.
     */
    public function isTersedia(): bool
    {
        return $this->status === 'tersedia';
    }
}