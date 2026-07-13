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
        Schema::create('procurement_of_goods', function (Blueprint $table) {
            $table->id();
            $table->string('procurement_number')->unique();
            $table->unsignedBigInteger('custom_quotation_id')->nullable();
            $table->unsignedBigInteger('general_affair_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('custom_quotation_id')->references('id')->on('custom_quotations')->onDelete('set null');
            $table->foreign('general_affair_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_of_goods');
    }
};
