<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('request_order_items')) {
            Schema::table('request_order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('request_order_items', 'kategori_barang')) {
                    $table->string('kategori_barang', 100)->nullable()->after('barang_id');
                }
            });
        }
    }

    public function down()
    {
        Schema::table('request_order_items', function (Blueprint $table) {
            $table->dropColumn('kategori_barang');
        });
    }
};
