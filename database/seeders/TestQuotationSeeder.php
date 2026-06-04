<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomQuotation;
use App\Models\CustomQuotationItem;
use App\Models\User;

class TestQuotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test quotation with status 'sent'
        $sales = User::where('role', 'Sales')->first() ?? User::find(4);
        
        $quotation = CustomQuotation::create([
            'sales_id' => $sales->id,
            'quotation_number' => 'QUO-20260305-001',
            'to' => 'PT. Global Maju Sentosa',
            'up' => 'Bp. Handoko',
            'subject' => 'Penawaran Pengadaan Alat Kantor',
            'email' => 'procurement@globalmaju.com',
            'our_ref' => 'REF/IDN/2026/03/001',
            'date' => now(),
            'intro_text' => 'Bersama ini kami sampaikan penawaran harga untuk kebutuhan alat kantor perusahaan Bapak/Ibu.',
            'status' => 'approved_supervisor',
            'subtotal' => 5000000,
            'tax' => 550000,
            'grand_total' => 5550000,
            'expired_at' => now()->addDays(14),
            'approved_by' => 2, // Supervisor/Imam
            'approved_at' => now(),
        ]);

        // Add an item
        CustomQuotationItem::create([
            'custom_quotation_id' => $quotation->id,
            'product_name' => 'Kursi Kantor Ergonomis',
            'qty' => 5,
            'unit' => 'Unit',
            'price' => 1000000,
            'subtotal' => 5000000,
            'discount' => 0,
            'description' => 'Warna Hitam',
            'category' => 'MISCELLANEOUS',
        ]);

        echo "Test quotation created: " . $quotation->quotation_number . "\n";
    }
}
