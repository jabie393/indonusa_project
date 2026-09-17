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
        Schema::create('procurement_arrival_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procurement_of_goods_item_id');
            $table->unsignedBigInteger('good_id');
            $table->timestamp('received_at');
            $table->integer('quantity');
            $table->decimal('unit_cost', 15, 2);
            $table->string('status')->default('pending_spv'); // pending_spv, pending_warehouse, approved, rejected
            $table->unsignedBigInteger('spv_id')->nullable();
            $table->timestamp('spv_approved_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->string('rejected_by_role')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('procurement_of_goods_item_id', 'par_pog_item_foreign')->references('id')->on('procurement_of_goods_items')->onDelete('cascade');
            $table->foreign('good_id')->references('id')->on('goods')->onDelete('cascade');
            $table->foreign('spv_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_arrival_requests');
    }
};
