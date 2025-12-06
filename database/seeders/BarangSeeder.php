<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-38274',
                'status_barang' => 'masuk',
                'nama_barang' => 'Laptop Asus',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 10,
                'harga' => 8500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak A1',
                'status_listing' => 'listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'form' => '3'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-43750',
                'status_barang' => 'masuk',
                'nama_barang' => 'Printer Canon',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 5,
                'harga' => 2500000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak B2',
                'status_listing' => 'non listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'form' => '3'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'PKG-32543',
                'status_barang' => 'masuk',
                'nama_barang' => 'Buble Wrap',
                'kategori' => 'PACKAGING',
                'stok' => 100,
                'harga' => 65000,
                'satuan' => 'Rim',
                'lokasi' => 'Rak C3',
                'status_listing' => 'listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'form' => '3'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-39241',
                'status_barang' => 'masuk',
                'nama_barang' => 'Mouse Logitech',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 20,
                'harga' => 150000,
                'satuan' => 'Unit',
                'lokasi' => 'Rak D4',
                'status_listing' => 'non listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'form' => '3'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-37281',
                'status_barang' => 'ditolak',
                'nama_barang' => 'Pulpen Biru',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 200,
                'harga' => 3500,
                'satuan' => 'Pcs',
                'lokasi' => 'Rak E5',
                'status_listing' => 'listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'catatan' => 'Barang ini ditolak karena stok tidak sesuai.',
                'form' => '3'
            ],
            [
                'tipe_request' => 'primary',
                'kode_barang' => 'MSC-67254',
                'status_barang' => 'ditinjau',
                'nama_barang' => 'Pensil',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 210,
                'harga' => 3000,
                'satuan' => 'Pcs',
                'lokasi' => 'Rak E5',
                'status_listing' => 'listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'form' => '3'
            ],
            [
                'tipe_request' => 'new_stock',
                'kode_barang' => 'PKG-32543#1',
                'status_barang' => 'ditinjau',
                'nama_barang' => 'Buble Wrap',
                'kategori' => 'PACKAGING',
                'stok' => 10,
                'harga' => 65000,
                'satuan' => 'Rim',
                'lokasi' => 'Rak C3',
                'status_listing' => 'listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'form' => '3'
            ],
            [
                'tipe_request' => 'new_stock',
                'kode_barang' => 'MSC-39241#1',
                'status_barang' => 'ditolak',
                'nama_barang' => 'Mouse Logitech',
                'kategori' => 'MISCELLANEOUS',
                'stok' => 12,
                'harga' => 150000,
                'satuan' => 'Pak',
                'lokasi' => 'Rak D4',
                'status_listing' => 'non listing',
                'deskripsi' => 'Lorem ipsum dolor sit amet.',
                'catatan' => 'Barang ini ditolak karena satuan bukan berupa pak.',
                'form' => '3'
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
