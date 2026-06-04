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
        Schema::table('custom_quotation_items', function (Blueprint $table) {
            $table->unsignedBigInteger('goods_id')->nullable()->after('custom_quotation_id');
            $table->string('category')->nullable()->after('product_name');
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('set null');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('custom_product_name')->nullable()->after('goods_id');
            $table->string('category')->nullable()->after('custom_product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['custom_product_name', 'category']);
        });

        Schema::table('custom_quotation_items', function (Blueprint $table) {
            $table->dropForeign(['goods_id']);
            $table->dropColumn(['goods_id', 'category']);
        });
    }
};
