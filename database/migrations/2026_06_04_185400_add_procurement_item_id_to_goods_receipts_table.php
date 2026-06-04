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
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('procurement_of_goods_item_id')->nullable()->after('good_id');

            $table->foreign('procurement_of_goods_item_id', 'gr_proc_item_foreign')->references('id')->on('procurement_of_goods_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->dropForeign('gr_proc_item_foreign');
            $table->dropColumn(['procurement_of_goods_item_id']);
        });
    }
};
