<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TodayMovementsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // pastikan ada beberapa barang untuk direferensi
        $goods = DB::table('goods')->get();
        if ($goods->isEmpty()) {
            $sample = [];
            for ($i = 1; $i <= 5; $i++) {
                $sample[] = [
                    'goods_code' => 'SAMPLE' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'goods_name' => 'Sample Item ' . $i,
                    'category'    => $faker->randomElement(['Elektronik','Alat Tulis','Makanan']),
                    'stock'        => $faker->numberBetween(1, 100),
                    'unit'      => $faker->randomElement(['pcs','box','kg']),
                    'location'      => $faker->randomElement(['Gudang A','Gudang B']),
                    'buy_price'           => $faker->randomFloat(2, 800, 40000),
                    'selling_price'       => $faker->randomFloat(2, 1040, 52000),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            DB::table('goods')->insert($sample);
            $goods = DB::table('goods')->get();
        }

        $userIds = DB::table('users')->pluck('id')->toArray();
        $now = Carbon::now()->toDateTimeString();

        // buat beberapa history 'masuk' hari ini
        $inserts = [];
        foreach ($goods->random(min(3, $goods->count())) as $b) {
            $inserts[] = [
                'goods_id'    => $b->id,
                'goods_code'  => $b->goods_code,
                'goods_name'  => $b->goods_name,
                'category'    => $b->category,
                'stock'       => $b->stock + $faker->numberBetween(1,10),
                'unit'        => $b->unit,
                'location'    => $b->location,
                'buy_price'   => $b->buy_price ?? round($b->selling_price / 1.3, 2),
                'selling_price' => $b->selling_price,
                'old_status'  => 'ditinjau',
                'new_status'  => 'masuk',
                'changed_by'  => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'form'        => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'note'        => 'Seeded inbound today',
                'changed_at'  => $now,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        // buat beberapa history 'keluar' hari ini
        foreach ($goods->random(min(3, $goods->count())) as $b) {
            $inserts[] = [
                'goods_id'    => $b->id,
                'goods_code'  => $b->goods_code,
                'goods_name'  => $b->goods_name,
                'category'    => $b->category,
                'stock'       => max(0, $b->stock - $faker->numberBetween(1,10)),
                'unit'        => $b->unit,
                'location'    => $b->location,
                'buy_price'   => $b->buy_price ?? round($b->selling_price / 1.3, 2),
                'selling_price' => $b->selling_price,
                'old_status'  => 'masuk',
                'new_status'  => 'keluar',
                'changed_by'  => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'form'        => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'note'        => 'Seeded outbound today',
                'changed_at'  => $now,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        if (!empty($inserts)) {
            DB::table('goods_histories')->insert($inserts);
        }
    }
}
