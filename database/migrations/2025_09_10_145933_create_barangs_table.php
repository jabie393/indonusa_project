<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_request', ['primary', 'new_stock'])->default('primary');
            $table->enum('status_barang', ['ditinjau', 'masuk', 'ditolak'])->default('ditinjau');
            $table->string('status_listing')->default('listing');
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->enum('kategori', [
                'HANDTOOLS',
                'ADHESIVE AND SEALANT',
                'AUTOMOTIVE EQUIPMENT',
                'CLEANING',
                'COMPRESSOR',
                'CONSTRUCTION',
                'CUTTING TOOLS',
                'LIGHTING',
                'FASTENING',
                'GENERATOR',
                'HEALTH CARE EQUIPMENT',
                'HOSPITALITY',
                'HYDRAULIC TOOLS',
                'MARKING MACHINE',
                'MATERIAL HANDLING EQUIPMENT',
                'MEASURING AND TESTING EQUIPMENT',
                'METAL CUTTING MACHINERY',
                'PACKAGING',
                'PAINTING AND COATING',
                'PNEUMATIC TOOLS',
                'POWER TOOLS',
                'SAFETY AND PROTECTION EQUIPMENT',
                'SECURITY',
                'SHEET METAL MACHINERY',
                'STORAGE SYSTEM',
                'WELDING EQUIPMENT',
                'WOODWORKING EQUIPMENT',
                'MISCELLANEOUS'
            ])->nullable();
            $table->integer('stok')->default(0);
            $table->string('satuan');
            $table->string('lokasi')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
