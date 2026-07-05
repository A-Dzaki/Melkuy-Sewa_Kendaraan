<?php

namespace App\Models;

use Database\Factories\PeminjamanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Peminjaman extends Model
{
    /** @use HasFactory<PeminjamanFactory> */
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'kode_booking',
        'kendaraan_id',
        'nama',
        'email',
        'no_hp',
        'nik',
        'alamat',
        'tanggal_pinjam',
        'tanggal_kembali',
        'lama_sewa',
        'total_harga',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali' => 'date',
            'total_harga' => 'decimal:2',
            'lama_sewa' => 'integer',
        ];
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function qrTokens(): HasMany
    {
        return $this->hasMany(QrToken::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /**
     * Filter berdasarkan status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filter berdasarkan nomor HP.
     */
    public function scopeByPhone($query, string $phone)
    {
        return $query->where('no_hp', $phone);
    }

    /**
     * Pencarian berdasarkan kode booking, nama, no HP, atau email.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('kode_booking', 'ilike', "%{$search}%")
              ->orWhere('nama', 'ilike', "%{$search}%")
              ->orWhere('no_hp', 'ilike', "%{$search}%")
              ->orWhere('email', 'ilike', "%{$search}%");
        });
    }

    /**
     * Peminjaman yang sedang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['approved', 'paid', 'picked_up']);
    }

    /**
     * Peminjaman yang pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /**
     * Mendapatkan total harga terformat Rupiah.
     */
    public function getFormattedTotalHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Mendapatkan label status dengan format yang proper.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'paid' => 'Dibayar',
            'picked_up' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'cancelled' => 'Dibatalkan',
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
            'approved' => 'info',
            'paid' => 'primary',
            'picked_up' => 'info',
            'returned' => 'success',
            'cancelled' => 'danger',
            default => 'default',
        };
    }

    /**
     * Mendapatkan jadwal sewa terformat.
     */
    public function getJadwalSewaAttribute(): string
    {
        return $this->tanggal_pinjam->format('d M Y') . ' - ' . $this->tanggal_kembali->format('d M Y');
    }

    /**
     * Mendapatkan inisial nama pemesan.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->nama);
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(mb_substr($word, 0, 1));
        }

        return $initials;
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Generate kode booking unik (BK-XXXXXX).
     */
    public static function generateKodeBooking(): string
    {
        do {
            $kode = 'BK-' . strtoupper(Str::random(8));
        } while (static::where('kode_booking', $kode)->exists());

        return $kode;
    }

    /**
     * Hitung total harga berdasarkan lama sewa dan harga kendaraan.
     */
    public function hitungTotalHarga(): float
    {
        return $this->lama_sewa * $this->kendaraan->harga_sewa;
    }

    /**
     * Cek apakah sudah melewati tanggal kembali.
     */
    public function isTerlambat(): bool
    {
        return $this->status === 'picked_up' && now()->greaterThan($this->tanggal_kembali);
    }

    /**
     * Hitung jumlah hari keterlambatan.
     */
    public function hariTerlambat(): int
    {
        if (! $this->isTerlambat()) {
            return 0;
        }

        return (int) now()->diffInDays($this->tanggal_kembali);
    }
}