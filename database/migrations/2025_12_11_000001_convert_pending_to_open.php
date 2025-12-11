<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Convert any request_orders with status 'pending' to 'open'.
     */
    public function up()
    {
        DB::table('request_orders')
            ->where('status', 'pending')
            ->update(['status' => 'open']);
    }

    /**
     * Reverse the migrations.
     * Convert 'open' back to 'pending' for records updated by this migration.
     * Note: this is a best-effort reversal — it will change all 'open' statuses back to 'pending',
     * which may affect records that were intentionally 'open' before.
     */
    public function down()
    {
        DB::table('request_orders')
            ->where('status', 'open')
            ->update(['status' => 'pending']);
    }
};
