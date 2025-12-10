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
        Schema::table('custom_penawaran_items', function (Blueprint $table) {
            if (!Schema::hasColumn('custom_penawaran_items', 'diskon')) {
                $table->unsignedTinyInteger('diskon')->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('custom_penawaran_items', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('diskon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_penawaran_items', function (Blueprint $table) {
            if (Schema::hasColumn('custom_penawaran_items', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (Schema::hasColumn('custom_penawaran_items', 'diskon')) {
                $table->dropColumn('diskon');
            }
        });
    }
};
