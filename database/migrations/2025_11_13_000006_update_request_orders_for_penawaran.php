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
        Schema::table('request_orders', function (Blueprint $table) {
            // Nomor penawaran (auto-generated)
            $table->string('nomor_penawaran')->nullable()->after('request_number');
            
            // Tanggal berlaku penawaran (14 hari dari sekarang)
            $table->dateTime('tanggal_berlaku')->nullable()->after('tanggal_kebutuhan');
            
            // Tanggal ekspirasi
            $table->dateTime('expired_at')->nullable()->after('tanggal_berlaku');
            
            // Status penawaran: pending, approved, rejected, expired, converted
            $table->string('status')->default('pending')->change();
            
            // Kategori barang yang dipilih (validation bahwa harus dipilih dulu)
            $table->string('kategori_barang')->nullable()->after('catatan_customer');
            
            // Untuk tracking supporting images
            $table->json('supporting_images')->nullable()->after('kategori_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_orders', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_penawaran',
                'tanggal_berlaku',
                'expired_at',
                'kategori_barang',
                'supporting_images'
            ]);
        });
    }
};
