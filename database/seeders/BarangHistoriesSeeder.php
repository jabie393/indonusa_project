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

        // ambil data barang & users (jika ada) untuk membuat relasi realistis
        $barangs = DB::table('barangs')->get();
        $userIds = DB::table('users')->pluck('id')->toArray();

        $newStatuses = ['ditinjau', 'masuk', 'ditolak', 'dihapus', 'keluar'];
        $oldStatuses = ['ditinjau', 'masuk', 'ditolak', null];

        for ($i = 0; $i < 50; $i++) {
            $barang = $barangs->isNotEmpty() ? $barangs->random() : null;

            DB::table('barang_histories')->insert([
                'barang_id'    => $barang->id ?? null,
                'kode_barang'  => $barang->kode_barang ?? 'KD' . $faker->unique()->numerify('###'),
                'nama_barang'  => $barang->nama_barang ?? $faker->word(),
                'kategori'     => $barang->kategori ?? $faker->randomElement(['Elektronik', 'Alat Tulis', 'Makanan', 'Peralatan']),
                'stok'         => $barang->stok ?? $faker->numberBetween(0, 200),
                'satuan'       => $barang->satuan ?? $faker->randomElement(['pcs', 'box', 'kg']),
                'lokasi'       => $barang->lokasi ?? $faker->randomElement(['Gudang A', 'Gudang B', 'Toko']),
                'harga'        => $barang->harga ?? $faker->randomFloat(2, 1000, 100000),
                'old_status'   => $faker->randomElement($oldStatuses),
                'new_status'   => $faker->randomElement($newStatuses),
                'changed_by'   => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'form'   => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'note'         => $faker->sentence(),
                'changed_at'   => Carbon::now()->subDays($faker->numberBetween(0, 365))->toDateTimeString(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
