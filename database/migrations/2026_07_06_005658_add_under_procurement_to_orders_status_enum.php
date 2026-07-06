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
    public function up(): void
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM(
            'pending',
            'sent_to_supervisor',
            'open',
            'approved_supervisor',
            'rejected_supervisor',
            'under_procurement',
            'sent_to_warehouse',
            'approved_warehouse',
            'rejected_warehouse',
            'completed',
            'not_completed'
        ) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM(
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
        ) NOT NULL DEFAULT 'pending'");
    }
};
