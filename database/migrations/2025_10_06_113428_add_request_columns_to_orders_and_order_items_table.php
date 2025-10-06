<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('sales_id');
            $table->unsignedBigInteger('customer_id')->nullable()->after('customer_name');
            $table->date('tanggal_kebutuhan')->nullable()->after('reason');
            $table->text('catatan_customer')->nullable()->after('tanggal_kebutuhan');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('delivered_quantity')->default(0)->after('quantity');
            $table->enum('status_item', ['pending','pending_stock','partial','delivered'])
                  ->default('pending')
                  ->after('delivered_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_name','customer_id','tanggal_kebutuhan','catatan_customer']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['delivered_quantity','status_item']);
        });
    }
};
