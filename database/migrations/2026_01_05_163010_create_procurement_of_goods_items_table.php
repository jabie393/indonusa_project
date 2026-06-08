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
        Schema::create('procurement_of_goods_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procurement_of_goods_id');
            $table->unsignedBigInteger('goods_id');
            $table->integer('qty_requested');
            $table->integer('qty_ordered');
            $table->integer('qty_received')->default(0);
            $table->string('unit');
            $table->decimal('buy_price', 15, 2)->default(0.00);
            $table->decimal('selling_price', 15, 2)->default(0.00);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('procurement_of_goods_id', 'pog_items_pog_id_foreign')->references('id')->on('procurement_of_goods')->onDelete('cascade');
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_of_goods_items');
    }
};
