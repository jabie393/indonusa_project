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
        Schema::create('custom_penawaran_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_penawaran_id');
            $table->string('nama_barang');
            $table->integer('qty');
            $table->string('satuan');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->json('images')->nullable();
            $table->timestamps();

            $table->foreign('custom_penawaran_id')->references('id')->on('custom_penawarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_penawaran_items');
    }
};
