<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class BarangHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // ambil data barang & users untuk membuat relasi realistis
        $goods = DB::table('goods')->get();
        $userIds = DB::table('users')->pluck('id')->toArray();

        $newStatuses = ['ditinjau', 'masuk', 'ditolak', 'dihapus', 'keluar'];
        $oldStatuses = ['ditinjau', 'masuk', 'ditolak', null];

        for ($i = 0; $i < 30; $i++) {
            $barang = $goods->isNotEmpty() ? $goods->random() : null;

            DB::table('goods_histories')->insert([
                'barang_id'    => $barang->id ?? null,
                'kode_barang'  => $barang->kode_barang ?? 'KD' . $faker->unique()->numerify('###'),
                'nama_barang'  => $barang->nama_barang ?? $faker->word(),
                'kategori'     => $barang->kategori ?? $faker->randomElement(['HANDTOOLS', 'MISCELLANEOUS', 'PACKAGING']),
                'stok'         => $barang->stok ?? $faker->numberBetween(0, 200),
                'satuan'       => $barang->satuan ?? $faker->randomElement(['pcs', 'box', 'Roll', 'Unit']),
                'lokasi'       => $barang->lokasi ?? $faker->randomElement(['Rak A1', 'Rak B2', 'Gudang Utama']),
                'harga'        => $barang->harga ?? $faker->randomFloat(2, 1000, 100000),
                'old_status'   => $faker->randomElement($oldStatuses),
                'new_status'   => $faker->randomElement($newStatuses),
                'changed_by'   => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'form'         => $faker->randomElement(['3', '4', '5']),
                'note'         => $faker->sentence(),
                'changed_at'   => Carbon::now()->subDays($faker->numberBetween(0, 30))->toDateTimeString(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
