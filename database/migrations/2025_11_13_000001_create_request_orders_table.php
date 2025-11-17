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
            $table->unsignedBigInteger('sales_id');
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'converted'])->default('pending');
            $table->text('reason')->nullable(); // alasan penolakan
            $table->date('tanggal_kebutuhan')->nullable();
            $table->text('catatan_customer')->nullable();
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
