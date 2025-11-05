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
                'tipe_request' => 'primary',
                'kode_barang' => 'BRG001',
                'status_barang' => 'masuk',
                'nama_barang' => 'Laptop Asus',
                'kategori' => 'Elektronik',
                'stok' => 10,
                'harga' => 8500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak A1',
                'status_listing' => 'listing',
                'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'BRG002',
                'status_barang' => 'masuk',
                'nama_barang' => 'Printer Canon',
                'kategori' => 'Elektronik',
                'stok' => 5,
                'harga' => 2500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak B2',
                'status_listing' => 'non listing',
                'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'BRG003',
                'status_barang' => 'masuk',
                'nama_barang' => 'Kertas A4',
                'kategori' => 'ATK',
                'stok' => 100,
                'harga' => 65000,
                'satuan' => 'Rim',
                'lokasi' => 'Rak C3',
                'status_listing' => 'listing',
                'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'BRG004',
                'status_barang' => 'masuk',
                'nama_barang' => 'Mouse Logitech',
                'kategori' => 'Elektronik',
                'stok' => 20,
                'harga' => 150000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak D4',
                'status_listing' => 'non listing',
                'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'BRG005',
                'status_barang' => 'ditolak',
                'nama_barang' => 'Pulpen Biru',
                'kategori' => 'ATK',
                'stok' => 200,
                'harga' => 3500,
                'satuan' => 'Pcs',
                'lokasi' => 'Rak E5',
                'status_listing' => 'listing',
                'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
                'catatan' => 'Barang ini ditolak karena stok tidak sesuai.'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'BRG006',
                'status_barang' => 'ditinjau',
                'nama_barang' => 'pensil',
                'kategori' => 'ATK',
                'stok' => 210,
                'harga' => 3000,
                'satuan' => 'Pcs',
                'lokasi' => 'Rak E5',
                'status_listing' => 'listing',
                'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'BRG004-1',
                'status_barang' => 'ditolak',
                'nama_barang' => 'Mouse Logitech',
                'kategori' => 'Elektronik',
                'stok' => 12,
                'harga' => 150000,
                'satuan' => 'Pak',
                'lokasi' => 'Rak D4',
                'status_listing' => 'non listing',
                'deskripsi' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."',
                'catatan' => 'Barang ini ditolak karena satuan bukan berupa pak.'
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
