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
        Schema::table('request_order_items', function (Blueprint $table) {
            // Track images per item if needed
            $table->json('item_images')->nullable()->after('subtotal');
            
            // Notes per item
            $table->text('notes')->nullable()->after('item_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_order_items', function (Blueprint $table) {
            $table->dropColumn(['item_images', 'notes']);
        });
    }
};
