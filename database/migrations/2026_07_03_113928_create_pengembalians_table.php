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
        Schema::create('pengembalians', function (Blueprint $table) {

            $table->id();

            $table->foreignId('peminjaman_id')
                ->constrained('peminjamans')
                ->cascadeOnDelete();

            $table->timestamp('tanggal_dikembalikan');

            $table->text('kondisi');

            $table->decimal('denda', 12, 2)
                ->default(0);

            $table->text('catatan')
                ->nullable();

            $table->timestamps();
        });
    }
};
