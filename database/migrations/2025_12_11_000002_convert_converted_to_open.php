<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Convert request_orders with status 'converted' to 'open'.
     */
    public function up()
    {
        // WARNING: This will change all converted records to open. Make sure this is what you want.
        DB::table('request_orders')
            ->where('status', 'converted')
            ->update(['status' => 'open']);
    }

    /**
     * Reverse the migrations.
     * Change 'open' back to 'converted' for records that were converted.
     * Note: this will convert all 'open' back to 'converted' which may affect records not intended.
     */
    public function down()
    {
        DB::table('request_orders')
            ->where('status', 'open')
            ->update(['status' => 'converted']);
    }
};
