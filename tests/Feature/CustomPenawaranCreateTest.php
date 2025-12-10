<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\CustomPenawaran;

class CustomPenawaranCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_can_create_custom_penawaran()
    {
        // Create a sales user
        $sales = User::factory()->create(['role' => 'Sales']);

        // Data for creating penawaran
        $data = [
            'to' => 'PT Test Customer',
            'up' => 'Manager',
            'subject' => 'Penawaran Produk',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-TEST12345',
            'date' => now()->toDateString(),
            'intro_text' => 'Berikut kami sampaikan penawaran terbaik untuk Anda',
            'tax' => 100000,
            'items' => [
                [
                    'nama_barang' => 'Laptop',
                    'qty' => 1,
                    'satuan' => 'pcs',
                    'harga' => 10000000,
                    'diskon' => 0,
                    'keterangan' => null,
                ]
            ]
        ];

        // Post to create penawaran
        $response = $this->actingAs($sales)
            ->post(route('sales.custom-penawaran.store'), $data);

        // Check response redirects to show page
        $response->assertRedirect();

        // Check penawaran created in database
        $this->assertDatabaseHas('custom_penawarans', [
            'sales_id' => $sales->id,
            'to' => 'PT Test Customer',
            'subject' => 'Penawaran Produk',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-TEST12345',
        ]);

        // Check penawaran items created
        $penawaran = CustomPenawaran::where('sales_id', $sales->id)->first();
        $this->assertEquals(1, $penawaran->items()->count());
    }

    public function test_sales_cannot_create_penawaran_without_our_ref()
    {
        // Create a sales user
        $sales = User::factory()->create(['role' => 'Sales']);

        // Data without our_ref
        $data = [
            'to' => 'PT Test Customer',
            'subject' => 'Penawaran Produk',
            'email' => 'customer@test.com',
            'date' => now()->toDateString(),
            'items' => [
                [
                    'nama_barang' => 'Laptop',
                    'qty' => 1,
                    'satuan' => 'pcs',
                    'harga' => 10000000,
                    'diskon' => 0,
                ]
            ]
        ];

        // Post to create penawaran
        $response = $this->actingAs($sales)
            ->post(route('sales.custom-penawaran.store'), $data);

        // Check validation error for our_ref
        $response->assertSessionHasErrors(['our_ref']);
    }

    public function test_diskon_more_than_20_percent_requires_keterangan()
    {
        // Create a sales user
        $sales = User::factory()->create(['role' => 'Sales']);

        // Data with diskon > 20% but no keterangan
        $data = [
            'to' => 'PT Test Customer',
            'subject' => 'Penawaran Produk',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-TEST12345',
            'date' => now()->toDateString(),
            'items' => [
                [
                    'nama_barang' => 'Laptop',
                    'qty' => 1,
                    'satuan' => 'pcs',
                    'harga' => 10000000,
                    'diskon' => 25,  // More than 20%
                    'keterangan' => null,  // No keterangan
                ]
            ]
        ];

        // Post to create penawaran
        $response = $this->actingAs($sales)
            ->post(route('sales.custom-penawaran.store'), $data);

        // Check error
        $response->assertSessionHasErrors();
    }

    public function test_diskon_more_than_20_percent_with_keterangan_works()
    {
        // Create a sales user
        $sales = User::factory()->create(['role' => 'Sales']);

        // Data with diskon > 20% and keterangan
        $data = [
            'to' => 'PT Test Customer',
            'subject' => 'Penawaran Produk',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-TEST12345',
            'date' => now()->toDateString(),
            'items' => [
                [
                    'nama_barang' => 'Laptop',
                    'qty' => 1,
                    'satuan' => 'pcs',
                    'harga' => 10000000,
                    'diskon' => 25,  // More than 20%
                    'keterangan' => 'Diskon diberikan karena pembelian dalam jumlah besar',
                ]
            ]
        ];

        // Post to create penawaran
        $response = $this->actingAs($sales)
            ->post(route('sales.custom-penawaran.store'), $data);

        // Check response redirects
        $response->assertRedirect();

        // Check penawaran created with status 'sent' (needs approval)
        $penawaran = CustomPenawaran::where('sales_id', $sales->id)->first();
        $this->assertEquals('sent', $penawaran->status);
    }
}
