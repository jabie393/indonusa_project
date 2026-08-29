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
        // Add approved_at and queue_at columns to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('customer_notes');
            $table->timestamp('queue_at')->nullable()->after('approved_at');
        });

        // Add allocated_quantity and shortage_quantity to order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('allocated_quantity')->default(0)->after('delivered_quantity');
            $table->integer('shortage_quantity')->default(0)->after('allocated_quantity');
        });

        // Add order_id and make general_affair_id nullable in procurement_of_goods
        Schema::table('procurement_of_goods', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('custom_quotation_id');
            $table->unsignedBigInteger('general_affair_id')->nullable()->change();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });

        // Create procurement_order_items pivot table
        Schema::create('procurement_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procurement_of_goods_item_id');
            $table->unsignedBigInteger('order_item_id');
            $table->integer('quantity'); // shortage quantity requested
            $table->integer('allocated_quantity')->default(0); // quantity received and allocated to this order item
            $table->timestamps();

            $table->foreign('procurement_of_goods_item_id', 'poi_pog_item_foreign')
                ->references('id')->on('procurement_of_goods_items')
                ->onDelete('cascade');
            $table->foreign('order_item_id', 'poi_order_item_foreign')
                ->references('id')->on('order_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_order_items');

        Schema::table('procurement_of_goods', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
            $table->unsignedBigInteger('general_affair_id')->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['allocated_quantity', 'shortage_quantity']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'queue_at']);
        });
    }
};
