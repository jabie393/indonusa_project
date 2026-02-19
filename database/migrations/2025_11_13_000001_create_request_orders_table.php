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
        Schema::create('request_orders', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('nomor_penawaran')->nullable();
            $table->unsignedBigInteger('sales_id');
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, expired, converted, etc.
            $table->text('reason')->nullable(); // alasan penolakan
            $table->date('tanggal_kebutuhan')->nullable();
            $table->dateTime('tanggal_berlaku')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->text('catatan_customer')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2)->default(0);
            $table->string('kategori_barang')->nullable();
            $table->json('supporting_images')->nullable();
            $table->string('no_po')->nullable();
            $table->string('sales_order_number')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_orders');
    }
};
