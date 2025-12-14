<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE custom_penawarans MODIFY COLUMN status ENUM('open', 'sent', 'approved', 'rejected', 'expired') DEFAULT 'open'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE custom_penawarans MODIFY COLUMN status ENUM('open', 'sent', 'approved', 'rejected') DEFAULT 'open'");
    }
};
