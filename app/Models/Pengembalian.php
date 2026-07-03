<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'tanggal_dikembalikan',
        'kondisi',
        'denda',
        'catatan',
    ];

    protected $casts = [
        'tanggal_dikembalikan' => 'datetime',
        'denda' => 'decimal:2',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}