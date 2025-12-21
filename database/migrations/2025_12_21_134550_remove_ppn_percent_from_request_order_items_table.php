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
            $table->dropColumn('ppn_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_order_items', function (Blueprint $table) {
            $table->decimal('ppn_percent', 5, 2)->default(0)->after('diskon_percent');
        });
    }
};
