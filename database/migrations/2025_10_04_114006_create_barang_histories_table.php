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
        Schema::create('barang_histories', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel barang (bisa null jika barang sudah dihapus)
            $table->unsignedBigInteger('barang_id')->nullable();

            // Simpan snapshot data barang
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('stok')->nullable();
            $table->string('satuan')->nullable();
            $table->string('lokasi')->nullable();
            $table->decimal('harga', 15, 2)->nullable();

            // Status sebelum dan sesudah perubahan
            $table->enum('old_status', ['ditinjau', 'masuk', 'ditolak'])->nullable();
            $table->enum('new_status', ['ditinjau', 'masuk', 'ditolak', 'dihapus']);

            // Siapa yang mengubah (relasi ke users)
            $table->unsignedBigInteger('changed_by')->nullable();

            // Catatan tambahan
            $table->text('note')->nullable();

            // Timestamp perubahan
            $table->timestamp('changed_at')->useCurrent();

            $table->timestamps();

            // Foreign keys (optional biar fleksibel kalau data dihapus)
            // $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('set null');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_histories');
    }
};
