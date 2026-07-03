<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [

        'peminjaman_id',

        'metode',

        'jumlah',

        'bukti_bayar',

        'status'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}