<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Memperbaiki default value kolom status yang sebelumnya 'none'
     * (bukan bagian dari enum) menjadi 'pending'.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjamans ALTER COLUMN status SET DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE peminjamans ALTER COLUMN status SET DEFAULT 'none'");
    }
};
