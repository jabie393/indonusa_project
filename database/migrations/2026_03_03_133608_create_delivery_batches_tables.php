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
        Schema::create('delivery_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('batch_number');
            $table->timestamps();
        });

        Schema::create('delivery_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_batch_id')->constrained('delivery_batches')->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            $table->integer('quantity_sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_batch_items');
        Schema::dropIfExists('delivery_batches');
    }
};
