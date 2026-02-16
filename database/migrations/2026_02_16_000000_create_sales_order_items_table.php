<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('sales_order_items');
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_order_id');
            $table->string('nama_barang');
            $table->integer('qty');
            $table->string('satuan');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->integer('diskon')->nullable();
            $table->string('keterangan')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();

            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
