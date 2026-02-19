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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'approved_supervisor', 'rejected_supervisor', 'sent_to_warehouse', 'approved_warehouse', 'rejected_warehouse', 'completed', 'not_completed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'approved_supervisor', 'rejected_supervisor', 'sent_to_warehouse', 'approved_warehouse', 'rejected_warehouse', 'completed') DEFAULT 'pending'");
    }
};
