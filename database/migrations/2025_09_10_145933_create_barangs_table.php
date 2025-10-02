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
            $table->string('kategori')->nullable();
            $table->integer('stok')->default(0);
            $table->string('satuan');
            $table->string('lokasi')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
