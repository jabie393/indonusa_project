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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('sales_order_number')->unique();
            $table->unsignedBigInteger('request_order_id');
            $table->unsignedBigInteger('sales_id');
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('status', ['pending', 'in_process', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->text('reason')->nullable(); // alasan pembatalan
            $table->date('tanggal_kebutuhan')->nullable();
            $table->text('catatan_customer')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('request_order_id')->references('id')->on('request_orders')->onDelete('cascade');
            $table->foreign('sales_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('warehouse_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
