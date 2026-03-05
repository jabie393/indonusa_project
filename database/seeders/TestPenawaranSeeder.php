<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomPenawaran;
use App\Models\CustomPenawaranItem;
use App\Models\User;

class TestPenawaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test penawaran with status 'sent'
        $sales = User::where('role', 'Sales')->first() ?? User::find(4);
        
        $penawaran = CustomPenawaran::create([
            'sales_id' => $sales->id,
            'penawaran_number' => 'QUO-20260305-001',
            'to' => 'PT. Global Maju Sentosa',
            'up' => 'Bp. Handoko',
            'subject' => 'Penawaran Pengadaan Alat Kantor',
            'email' => 'procurement@globalmaju.com',
            'our_ref' => 'REF/IDN/2026/03/001',
            'date' => now(),
            'intro_text' => 'Bersama ini kami sampaikan penawaran harga untuk kebutuhan alat kantor perusahaan Bapak/Ibu.',
            'status' => 'approved',
            'subtotal' => 5000000,
            'tax' => 550000,
            'grand_total' => 5550000,
            'expired_at' => now()->addDays(14),
            'approved_by' => 2, // Supervisor/Imam
            'approved_at' => now(),
        ]);

        // Add an item
        CustomPenawaranItem::create([
            'custom_penawaran_id' => $penawaran->id,
            'nama_barang' => 'Kursi Kantor Ergonomis',
            'qty' => 5,
            'satuan' => 'Unit',
            'harga' => 1000000,
            'subtotal' => 5000000,
            'diskon' => 0,
            'keterangan' => 'Warna Hitam',
        ]);

        echo "Test penawaran created: " . $penawaran->penawaran_number . "\n";
    }
}

