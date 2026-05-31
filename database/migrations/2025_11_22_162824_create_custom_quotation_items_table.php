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
        Schema::create('custom_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_quotation_id');
            $table->string('product_name');
            $table->integer('qty');
            $table->string('unit');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->unsignedTinyInteger('discount')->default(0); // diskon dalam persen
            $table->string('description')->nullable(); // alasan diskon
            $table->json('images')->nullable();
            $table->timestamps();

            $table->foreign('custom_quotation_id')->references('id')->on('custom_quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_quotation_items');
    }
};
