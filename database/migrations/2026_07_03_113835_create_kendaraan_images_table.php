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
        Schema::create('kendaraan_images', function (Blueprint $table) {

            $table->id();

            $table->foreignId('kendaraan_id')
                ->constrained('kendaraans')
                ->cascadeOnDelete();

            $table->string('image');

            $table->boolean('thumbnail')
                ->default(false);

            $table->timestamps();
        });
    }
};
