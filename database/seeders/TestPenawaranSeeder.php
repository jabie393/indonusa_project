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
        $sales = User::where('role', 'Sales')->first();
        
        if (!$sales) {
            // Create a test sales user if not exist
            $sales = User::create([
                'name' => 'Test Sales',
                'email' => 'sales@test.com',
                'password' => bcrypt('password'),
                'role' => 'Sales',
            ]);
        }

        $penawaran = CustomPenawaran::create([
            'sales_id' => $sales->id,
            'penawaran_number' => 'TEST-' . uniqid(),
            'to' => 'Test Customer',
            'subject' => 'Test Penawaran',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-' . uniqid(),
            'date' => now(),
            'status' => 'sent',
            'subtotal' => 1000000,
            'grand_total' => 1000000,
        ]);

        // Add an item
        CustomPenawaranItem::create([
            'custom_penawaran_id' => $penawaran->id,
            'nama_barang' => 'Test Product',
            'qty' => 1,
            'satuan' => 'pcs',
            'harga' => 1000000,
            'subtotal' => 1000000,
        ]);

        echo "Test penawaran created: " . $penawaran->penawaran_number . "\n";
    }
}

