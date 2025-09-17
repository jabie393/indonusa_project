<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Laptop Asus',
                'kategori' => 'Elektronik',
                'stok' => 10,
                'harga' => 8500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak A1',
                'status_listing' => 'listing',
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Printer Canon',
                'kategori' => 'Elektronik',
                'stok' => 5,
                'harga' => 2500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak B2',
                'status_listing' => 'non listing',
            ],
            [
                'kode_barang' => 'BRG003',
                'nama_barang' => 'Kertas A4',
                'kategori' => 'ATK',
                'stok' => 100,
                'harga' => 65000,
                'satuan' => 'Rim',
                'lokasi' => 'Rak C3',
                'status_listing' => 'listing',
            ],
            [
                'kode_barang' => 'BRG004',
                'nama_barang' => 'Mouse Logitech',
                'kategori' => 'Elektronik',
                'stok' => 20,
                'harga' => 150000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak D4',
                'status_listing' => 'non listing',
            ],
            [
                'kode_barang' => 'BRG005',
                'nama_barang' => 'Pulpen Biru',
                'kategori' => 'ATK',
                'stok' => 200,
                'harga' => 3500,
                'satuan' => 'Pcs',
                'lokasi' => 'Rak E5',
                'status_listing' => 'listing',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
