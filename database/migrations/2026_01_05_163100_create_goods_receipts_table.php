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
            $table->unsignedBigInteger('procurement_of_goods_item_id')->nullable();
            $table->unsignedBigInteger('supplier_id'); // Relasi ke users (Role: General Affair)
            $table->timestamp('received_at');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_cost', 15, 2); // CATATAN harga beli
            $table->string('status')->default('pending');
            $table->text('reject_reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('good_id')->references('id')->on('goods')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('procurement_of_goods_item_id', 'gr_proc_item_foreign')->references('id')->on('procurement_of_goods_items')->onDelete('set null');
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
