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
        Schema::table('request_order_items', function (Blueprint $table) {
            $table->decimal('diskon_percent', 5, 2)->default(0)->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_order_items', function (Blueprint $table) {
            $table->dropColumn('diskon_percent');
        });
    }
};
