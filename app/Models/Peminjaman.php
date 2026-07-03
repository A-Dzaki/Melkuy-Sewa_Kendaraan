<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $fillable = [

        'kendaraan_id',

        'nama',
        'email',
        'no_hp',
        'alamat',

        'tanggal_pinjam',
        'tanggal_kembali',

        'total_harga',

        'status'
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function qrTokens()
    {
        return $this->hasMany(QrToken::class);
    }
}