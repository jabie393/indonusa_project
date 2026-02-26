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
            $table->dropForeign(['barang_id']);
            $table->unsignedBigInteger('barang_id')->nullable()->change();
            $table->string('nama_barang_custom')->nullable()->after('barang_id');
            $table->foreign('barang_id')->references('id')->on('goods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_order_items', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->dropColumn('nama_barang_custom');
            $table->unsignedBigInteger('barang_id')->nullable(false)->change();
            $table->foreign('barang_id')->references('id')->on('goods')->onDelete('cascade');
        });
    }
};
