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
        $barangs = DB::table('barangs')->get();
        if ($barangs->isEmpty()) {
            $sample = [];
            for ($i = 1; $i <= 5; $i++) {
                $sample[] = [
                    'kode_barang' => 'SAMPLE' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'nama_barang' => 'Sample Item ' . $i,
                    'kategori'    => $faker->randomElement(['Elektronik','Alat Tulis','Makanan']),
                    'stok'        => $faker->numberBetween(1, 100),
                    'satuan'      => $faker->randomElement(['pcs','box','kg']),
                    'lokasi'      => $faker->randomElement(['Gudang A','Gudang B']),
                    'harga'       => $faker->randomFloat(2, 1000, 50000),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            DB::table('barangs')->insert($sample);
            $barangs = DB::table('barangs')->get();
        }

        $userIds = DB::table('users')->pluck('id')->toArray();
        $now = Carbon::now()->toDateTimeString();

        // buat beberapa history 'masuk' hari ini
        $inserts = [];
        foreach ($barangs->random(min(3, $barangs->count())) as $b) {
            $inserts[] = [
                'barang_id'   => $b->id,
                'kode_barang' => $b->kode_barang,
                'nama_barang' => $b->nama_barang,
                'kategori'    => $b->kategori,
                'stok'        => $b->stok + $faker->numberBetween(1,10),
                'satuan'      => $b->satuan,
                'lokasi'      => $b->lokasi,
                'harga'       => $b->harga,
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
        foreach ($barangs->random(min(3, $barangs->count())) as $b) {
            $inserts[] = [
                'barang_id'   => $b->id,
                'kode_barang' => $b->kode_barang,
                'nama_barang' => $b->nama_barang,
                'kategori'    => $b->kategori,
                'stok'        => max(0, $b->stok - $faker->numberBetween(1,10)),
                'satuan'      => $b->satuan,
                'lokasi'      => $b->lokasi,
                'harga'       => $b->harga,
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
            DB::table('barang_histories')->insert($inserts);
        }
    }
}
