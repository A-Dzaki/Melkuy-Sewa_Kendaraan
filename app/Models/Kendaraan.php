<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraans';

    protected $fillable = [
        'jenis',
        'merk',
        'nama',
        'tahun',
        'transmisi',
        'harga_sewa',
        'status',
        'deskripsi'
    ];

    public function images()
    {
        return $this->hasMany(KendaraanImage::class);
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}