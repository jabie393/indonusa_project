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
        // Modify the enum values for tipe_customer
        // This is for MySQL - changes the enum to include both old and new values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `customers` CHANGE `tipe_customer` `tipe_customer` ENUM('Pribadi','GOV','BUMN','Swasta','Retail','Wholesale','Distributor') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `customers` CHANGE `tipe_customer` `tipe_customer` ENUM('Pribadi','GOV','BUMN','Swasta') NOT NULL");
        }
    }
};
