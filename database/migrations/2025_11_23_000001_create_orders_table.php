<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('sales_id');      // id user yang membuat order (Sales)
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable(); // id Supervisor yang meninjau
            $table->unsignedBigInteger('warehouse_id')->nullable(); // id Warehouse
            $table->unsignedBigInteger('request_order_id')->nullable();
            $table->unsignedBigInteger('custom_penawaran_id')->nullable();
            $table->enum('status', [
                'pending',
                'open',
                'approved_supervisor',
                'rejected_supervisor',
                'sent_to_warehouse',
                'approved_warehouse',
                'rejected_warehouse',
                'completed',
                'not_completed'
            ])->default('pending');
            $table->text('reason')->nullable(); // alasan penolakan oleh Supervisor atau Warehouse
            $table->date('tanggal_kebutuhan')->nullable();
            $table->text('catatan_customer')->nullable();
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('warehouse_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('request_order_id')->references('id')->on('request_orders')->onDelete('set null');
            $table->foreign('custom_penawaran_id')->references('id')->on('custom_penawarans')->onDelete('cascade');
        });
        // Hapus kolom status dari request_orders
        Schema::table('request_orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        // Kembalikan kolom status ke request_orders
        Schema::table('request_orders', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
    }
};
