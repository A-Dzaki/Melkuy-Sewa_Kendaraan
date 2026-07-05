<?php

namespace App\Models;

use Database\Factories\KendaraanImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KendaraanImage extends Model
{
    /** @use HasFactory<KendaraanImageFactory> */
    use HasFactory;

    protected $fillable = [
        'kendaraan_id',
        'image',
        'thumbnail',
    ];

    protected function casts(): array
    {
        return [
            'thumbnail' => 'boolean',
        ];
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /**
     * Mendapatkan URL gambar.
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}