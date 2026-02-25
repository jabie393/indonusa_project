<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom reason ke tabel request_orders jika belum ada
        if (!Schema::hasColumn('request_orders', 'reason')) {
            Schema::table('request_orders', function (Blueprint $table) {
                $table->text('reason')->nullable()->after('catatan_customer')
                      ->comment('Alasan penolakan dari supervisor');
            });
        }

        // Tambah kolom reason ke tabel orders jika belum ada
        if (!Schema::hasColumn('orders', 'reason')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('reason')->nullable()->after('catatan_customer')
                      ->comment('Alasan penolakan dari supervisor');
            });
        }
    }

    public function down(): void
    {
        Schema::table('request_orders', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
};
