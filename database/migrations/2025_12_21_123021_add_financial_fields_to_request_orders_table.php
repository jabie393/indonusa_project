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
        Schema::table('request_orders', function (Blueprint $table) {
            $table->decimal('subtotal', 14, 2)->default(0)->after('catatan_customer');
            $table->decimal('tax', 14, 2)->default(0)->after('subtotal');
            $table->decimal('grand_total', 14, 2)->default(0)->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax', 'grand_total']);
        });
    }
};
