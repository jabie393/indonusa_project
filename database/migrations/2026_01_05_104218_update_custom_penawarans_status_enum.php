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
        DB::statement("ALTER TABLE custom_penawarans MODIFY COLUMN status ENUM('draft', 'pending_approval', 'sent', 'approved', 'rejected', 'open') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE custom_penawarans MODIFY COLUMN status ENUM('draft', 'sent', 'approved', 'rejected') DEFAULT 'draft'");
    }
};
