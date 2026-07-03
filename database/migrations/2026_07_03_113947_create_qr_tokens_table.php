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
        Schema::create('qr_tokens', function (Blueprint $table) {

            $table->id();

            $table->foreignId('peminjaman_id')
                ->constrained('peminjamans')
                ->cascadeOnDelete();

            $table->string('token')->unique();

            $table->enum('type', [
                'pickup',
                'return'
            ]);

            $table->timestamp('expired_at');

            $table->timestamp('used_at')
                ->nullable();

            $table->timestamps();
        });
    }
};
