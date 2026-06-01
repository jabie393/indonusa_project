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
                'request_type' => 'primary',
                'goods_code' => 'MSC-38274',
                'goods_status' => 'approved',
                'goods_name' => 'Laptop Asus ExpertBook',
                'category' => 'MISCELLANEOUS',
                'stock' => 10,
                'buy_price' => 8500000,
                'unit' => 'Unit',
                'location' => 'Rak A1',
                'status_listing' => 'listing',
                'description' => 'Laptop Asus ExpertBook for office work.',
                'discount_percent' => 5,
                'form' => '3',
                'submission_reason' => 'Kebutuhan operasional kantor'
            ],
            [
                'request_type' => 'primary',
                'goods_code' => 'MSC-43750',
                'goods_status' => 'approved',
                'goods_name' => 'Printer Canon Pixma',
                'category' => 'MISCELLANEOUS',
                'stock' => 5,
                'buy_price' => 2500000,
                'unit' => 'Unit',
                'location' => 'Rak B2',
                'status_listing' => 'non listing',
                'description' => 'Printer inkjet for document printing.',
                'discount_percent' => 0,
                'form' => '3',
                'submission_reason' => 'Kebutuhan percetakan invoice'
            ],
            [
                'request_type' => 'primary',
                'goods_code' => 'PKG-32543',
                'goods_status' => 'approved',
                'goods_name' => 'Buble Wrap 1m x 50m',
                'category' => 'PACKAGING',
                'stock' => 100,
                'buy_price' => 65000,
                'unit' => 'Roll',
                'location' => 'Rak C3',
                'status_listing' => 'listing',
                'description' => 'High quality protection bubble wrap.',
                'discount_percent' => 2,
                'form' => '3'
            ],
            [
                'request_type' => 'primary',
                'goods_code' => 'MSC-37281',
                'goods_status' => 'rejected',
                'goods_name' => 'Pulpen Biru 0.5',
                'category' => 'MISCELLANEOUS',
                'stock' => 0,
                'buy_price' => 3500,
                'unit' => 'Pcs',
                'location' => 'Rak E5',
                'status_listing' => 'listing',
                'description' => 'Blue ink ballpoint.',
                'note' => 'Ditolak karena vendor tidak tersedia.',
                'form' => '3'
            ],
        ];

        foreach ($goods as $barangData) {
            $barang = Barang::create($barangData);

            // Seed GoodsReceipt for 'masuk' items
            if ($barangData['goods_status'] === 'approved') {
                \Illuminate\Support\Facades\DB::table('goods_receipts')->insert([
                    'good_id' => $barang->id,
                    'supplier_id' => 3, // GA/Fahd
                    'received_at' => now(),
                    'approved_by' => 2, // Supervisor/Imam
                    'quantity' => $barangData['stock'],
                    'unit_cost' => $barangData['buy_price'] * 0.8, // Assuming cost is 80% of price
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
