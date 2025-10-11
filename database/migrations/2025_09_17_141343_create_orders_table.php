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
            $table->unsignedBigInteger('supervisor_id')->nullable(); // id Supervisor yang meninjau
            $table->unsignedBigInteger('warehouse_id')->nullable(); // id Warehouse
            $table->enum('status', [
                'pending',
                'approved_supervisor',
                'rejected_supervisor',
                'sent_to_warehouse',
                'approved_warehouse',
                'rejected_warehouse',
                'completed'
            ])->default('pending');
            $table->text('reason')->nullable(); // alasan penolakan oleh Supervisor atau Warehouse
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
