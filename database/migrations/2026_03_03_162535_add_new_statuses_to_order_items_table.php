<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status_item ENUM('pending', 'pending_stock', 'partial', 'partially_delivered', 'delivered', 'cancel') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status_item ENUM('pending', 'pending_stock', 'partial', 'delivered') DEFAULT 'pending'");
    }
};
