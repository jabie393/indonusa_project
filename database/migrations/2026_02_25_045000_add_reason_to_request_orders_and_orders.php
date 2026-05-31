<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom reason ke tabel quotations jika belum ada
        if (!Schema::hasColumn('quotations', 'reason')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->text('reason')->nullable()->after('customer_notes')
                      ->comment('Alasan penolakan dari supervisor');
            });
        }

        // Tambah kolom reason ke tabel orders jika belum ada
        if (!Schema::hasColumn('orders', 'reason')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('reason')->nullable()->after('customer_notes')
                      ->comment('Alasan penolakan dari supervisor');
            });
        }
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
};
