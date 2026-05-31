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
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->unsignedBigInteger('custom_quotation_id')->nullable();
            $table->enum('status', [
                'pending',
                'sent_to_supervisor',
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
            $table->date('required_date')->nullable();
            $table->text('customer_notes')->nullable();
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('warehouse_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('set null');
            $table->foreign('custom_quotation_id')->references('id')->on('custom_quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        // Kembalikan kolom status ke quotations
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
    }
};
