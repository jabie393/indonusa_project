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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->unsignedBigInteger('goods_id')->nullable();
            $table->string('custom_product_name')->nullable();
            $table->text('custom_product_description')->nullable();
            $table->string('custom_product_unit')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('subtotal', 14, 2)->nullable();
            $table->unsignedSmallInteger('discount_percent')->default(0);
            $table->string('product_category')->nullable();
            $table->json('images')->nullable();
            // $table->json('item_images')->nullable();
            $table->timestamps();

            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
