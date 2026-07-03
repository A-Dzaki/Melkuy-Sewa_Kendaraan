<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();

            $table->string('kode_kendaraan')->unique();

            $table->enum('jenis', [
                'motor',
                'mobil'
            ]);

            $table->string('merk');
            $table->string('nama');
            $table->year('tahun');

            $table->string('warna');

            $table->enum('transmisi', [
                'Manual',
                'Matic'
            ]);

            $table->decimal('harga_sewa', 12, 2);

            $table->enum('status', [
                'tersedia',
                'dipesan',
                'dipinjam',
                'maintenance'
            ])->default('tersedia');

            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }
};
