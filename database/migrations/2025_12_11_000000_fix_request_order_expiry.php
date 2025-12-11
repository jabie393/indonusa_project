<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration updates existing request_orders that were created with a 7-day expiry
     * and sets their `expired_at` to 14 days after `created_at`.
     */
    public function up()
    {
        // Only update records where expired_at is roughly 7 days after created_at (<= 8 days tolerance)
        DB::table('request_orders')
            ->whereNotNull('created_at')
            ->whereNotNull('expired_at')
            ->whereRaw('TIMESTAMPDIFF(DAY, created_at, expired_at) <= 8')
            ->update(['expired_at' => DB::raw("DATE_ADD(created_at, INTERVAL 14 DAY)")]);
    }

    /**
     * Reverse the migrations.
     * We won't attempt to revert data changes here to avoid data loss.
     */
    public function down()
    {
        // Intentionally left empty. If you need to revert, restore from backup.
    }
};
