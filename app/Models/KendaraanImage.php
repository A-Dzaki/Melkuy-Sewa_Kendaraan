<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanImage extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'image_path',
        'is_thumbnail'
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}