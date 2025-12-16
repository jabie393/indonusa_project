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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_penawaran_id')->nullable()->after('warehouse_id');
            $table->foreign('custom_penawaran_id')->references('id')->on('custom_penawarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['custom_penawaran_id']);
            $table->dropColumn('custom_penawaran_id');
        });
    }
};
