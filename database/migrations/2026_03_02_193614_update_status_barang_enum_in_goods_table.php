<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE goods MODIFY COLUMN status_barang ENUM('ditinjau', 'masuk', 'ditolak') DEFAULT 'ditinjau'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE goods MODIFY COLUMN status_barang ENUM('ditinjau', 'masuk', 'ditolak', 'ditinjau_supervisor', 'ditolak_supervisor') DEFAULT 'ditinjau'");
    }
};
