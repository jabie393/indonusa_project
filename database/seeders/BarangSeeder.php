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
                'kategori'   => 'Elektronik',
                'stok'       => 10,
                'harga'      => 8500000,
                'satuan'     => 'Unit',
                'lokasi'     => 'Rak A1',
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Printer Canon',
                'kategori'   => 'Elektronik',
                'stok'       => 5,
                'harga'      => 2500000,
                'satuan'     => 'Unit',
                'lokasi'     => 'Rak B2',
            ],
            [
                'kode_barang' => 'BRG003',
                'nama_barang' => 'Kertas A4',
                'kategori'   => 'ATK',
                'stok'       => 100,
                'harga'      => 65000,
                'satuan'     => 'Rim',
                'lokasi'     => 'Rak C3',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
