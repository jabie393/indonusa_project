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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE goods_histories MODIFY COLUMN old_status ENUM('ditinjau', 'masuk', 'ditolak') NULL");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE goods_histories MODIFY COLUMN new_status ENUM('ditinjau', 'masuk', 'ditolak', 'dihapus', 'keluar') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE goods_histories MODIFY COLUMN old_status ENUM('ditinjau', 'masuk', 'ditolak', 'ditinjau_supervisor', 'ditolak_supervisor') NULL");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE goods_histories MODIFY COLUMN new_status ENUM('ditinjau', 'masuk', 'ditolak', 'ditinjau_supervisor', 'ditolak_supervisor', 'dihapus', 'keluar') NOT NULL");
    }
};
