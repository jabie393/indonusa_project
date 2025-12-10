<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\CustomPenawaran;
use App\Models\CustomPenawaranItem;

class CustomPenawaranApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_approve_penawaran()
    {
        // Create users
        $supervisor = User::factory()->create(['role' => 'Supervisor']);
        $sales = User::factory()->create(['role' => 'Sales']);

        // Create penawaran
        $penawaran = CustomPenawaran::create([
            'sales_id' => $sales->id,
            'penawaran_number' => 'TEST-001',
            'to' => 'Test Customer',
            'subject' => 'Test Offer',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-001',
            'date' => now(),
            'status' => 'sent',
            'subtotal' => 1000000,
            'grand_total' => 1000000,
        ]);

        // Create item
        CustomPenawaranItem::create([
            'custom_penawaran_id' => $penawaran->id,
            'nama_barang' => 'Test Product',
            'qty' => 1,
            'satuan' => 'pcs',
            'harga' => 1000000,
            'subtotal' => 1000000,
        ]);

        // Test approval
        $response = $this->actingAs($supervisor)
            ->post(route('admin.custom-penawaran.approval', $penawaran), [
                'action' => 'approve'
            ]);

        $this->assertTrue($response->status() === 302 || $response->status() === 200);
        $this->assertEquals('approved', $penawaran->fresh()->status);
    }

    public function test_supervisor_can_reject_penawaran()
    {
        // Create users
        $supervisor = User::factory()->create(['role' => 'Supervisor']);
        $sales = User::factory()->create(['role' => 'Sales']);

        // Create penawaran
        $penawaran = CustomPenawaran::create([
            'sales_id' => $sales->id,
            'penawaran_number' => 'TEST-002',
            'to' => 'Test Customer',
            'subject' => 'Test Offer',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-002',
            'date' => now(),
            'status' => 'sent',
            'subtotal' => 1000000,
            'grand_total' => 1000000,
        ]);

        // Create item
        CustomPenawaranItem::create([
            'custom_penawaran_id' => $penawaran->id,
            'nama_barang' => 'Test Product',
            'qty' => 1,
            'satuan' => 'pcs',
            'harga' => 1000000,
            'subtotal' => 1000000,
        ]);

        // Test rejection
        $response = $this->actingAs($supervisor)
            ->post(route('admin.custom-penawaran.approval', $penawaran), [
                'action' => 'reject'
            ]);

        $this->assertTrue($response->status() === 302 || $response->status() === 200);
        $this->assertEquals('rejected', $penawaran->fresh()->status);
    }

    public function test_non_supervisor_cannot_approve_penawaran()
    {
        // Create users
        $sales = User::factory()->create(['role' => 'Sales']);
        $other_sales = User::factory()->create(['role' => 'Sales']);

        // Create penawaran
        $penawaran = CustomPenawaran::create([
            'sales_id' => $sales->id,
            'penawaran_number' => 'TEST-003',
            'to' => 'Test Customer',
            'subject' => 'Test Offer',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-003',
            'date' => now(),
            'status' => 'sent',
            'subtotal' => 1000000,
            'grand_total' => 1000000,
        ]);

        // Create item
        CustomPenawaranItem::create([
            'custom_penawaran_id' => $penawaran->id,
            'nama_barang' => 'Test Product',
            'qty' => 1,
            'satuan' => 'pcs',
            'harga' => 1000000,
            'subtotal' => 1000000,
        ]);

        // Test that non-supervisor is denied
        $response = $this->actingAs($other_sales)
            ->post(route('admin.custom-penawaran.approval', $penawaran), [
                'action' => 'approve'
            ]);

        $this->assertEquals(403, $response->status());
    }
}
