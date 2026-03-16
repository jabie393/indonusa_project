<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $goods = [
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-38274',
                'status_barang' => 'masuk',
                'nama_barang' => 'Laptop Asus ExpertBook',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 10,
                'harga' => 8500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak A1',
                'status_listing' => 'listing',
                'deskripsi' => 'Laptop Asus ExpertBook for office work.',
                'diskon_percent' => 5,
                'form' => '3',
                'alasan_pengajuan' => 'Kebutuhan operasional kantor'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-43750',
                'status_barang' => 'masuk',
                'nama_barang' => 'Printer Canon Pixma',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 5,
                'harga' => 2500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak B2',
                'status_listing' => 'non listing',
                'deskripsi' => 'Printer inkjet for document printing.',
                'diskon_percent' => 0,
                'form' => '3',
                'alasan_pengajuan' => 'Kebutuhan percetakan invoice'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'PKG-32543',
                'status_barang' => 'masuk',
                'nama_barang' => 'Buble Wrap 1m x 50m',
                'kategori' => 'PACKAGING',
                'stok' => 100,
                'harga' => 65000,
                'satuan' => 'Roll',
                'lokasi' => 'Rak C3',
                'status_listing' => 'listing',
                'deskripsi' => 'High quality protection bubble wrap.',
                'diskon_percent' => 2,
                'form' => '3'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'HTL-12345',
                'status_barang' => 'ditinjau_supervisor',
                'nama_barang' => 'Kunci Inggris 12 Inch',
                'kategori' => 'HANDTOOLS',
                'stok' => 15,
                'harga' => 125000,
                'satuan' => 'Pcs',
                'lokasi' => 'Rak D1',
                'status_listing' => 'listing',
                'deskripsi' => 'Adjustable wrench carbon steel.',
                'diskon_percent' => 0,
                'form' => '4',
                'alasan_pengajuan' => 'Penambahan alat bengkel'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-37281',
                'status_barang' => 'ditolak',
                'nama_barang' => 'Pulpen Biru 0.5',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 0,
                'harga' => 3500,
                'satuan' => 'Pcs',
                'lokasi' => 'Rak E5',
                'status_listing' => 'listing',
                'deskripsi' => 'Blue ink ballpoint.',
                'catatan' => 'Ditolak karena vendor tidak tersedia.',
                'form' => '3'
            ],
        ];

        foreach ($goods as $barangData) {
            $barang = Barang::create($barangData);

            // Seed GoodsReceipt for 'masuk' items
            if ($barangData['status_barang'] === 'masuk') {
                \Illuminate\Support\Facades\DB::table('goods_receipts')->insert([
                    'good_id' => $barang->id,
                    'supplier_id' => 3, // GA/Fahd
                    'received_at' => now(),
                    'approved_by' => 2, // Supervisor/Imam
                    'quantity' => $barangData['stok'],
                    'unit_cost' => $barangData['harga'] * 0.8, // Assuming cost is 80% of price
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
