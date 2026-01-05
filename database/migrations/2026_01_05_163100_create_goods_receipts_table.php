<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id');
            $table->unsignedBigInteger('supplier_id'); // Relasi ke users (Role: General Affair)
            $table->timestamp('received_at');
            $table->unsignedBigInteger('created_by');
            $table->integer('quantity');
            $table->decimal('unit_cost', 15, 2); // CATATAN harga beli
            $table->timestamps();

            // Foreign keys
            $table->foreign('good_id')->references('id')->on('goods')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
