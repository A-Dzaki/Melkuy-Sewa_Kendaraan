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
        Schema::create('peminjamans', function (Blueprint $table) {

            $table->id();

            $table->string('kode_booking')->unique();

            $table->foreignId('kendaraan_id')
                ->constrained('kendaraans')
                ->cascadeOnDelete();

            $table->string('nama');

            $table->string('email');

            $table->string('no_hp');

            $table->string('nik');

            $table->text('alamat');

            $table->date('tanggal_pinjam');

            $table->date('tanggal_kembali');

            $table->unsignedInteger('lama_sewa');

            $table->decimal('total_harga', 12, 2);

            $table->enum('status', [
                'pending',
                'approved',
                'paid',
                'picked_up',
                'returned',
                'cancelled'
            ])->default('none');

            $table->timestamps();
        });
    }
};
