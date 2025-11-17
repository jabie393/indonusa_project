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
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_order_id');
            $table->unsignedBigInteger('request_order_item_id')->nullable();
            $table->unsignedBigInteger('barang_id');
            $table->integer('quantity');
            $table->integer('delivered_quantity')->default(0);
            $table->decimal('harga', 12, 2)->nullable();
            $table->decimal('subtotal', 14, 2)->nullable();
            $table->enum('status_item', ['pending', 'partial', 'completed'])->default('pending');
            $table->timestamps();

            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->foreign('request_order_item_id')->references('id')->on('request_order_items')->onDelete('set null');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
