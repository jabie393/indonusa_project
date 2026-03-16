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
        Schema::table('catalog', function (Blueprint $table) {
            $table->renameColumn('filter_name', 'brand_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalog', function (Blueprint $table) {
            $table->renameColumn('brand_name', 'filter_name');
        });
    }
};
