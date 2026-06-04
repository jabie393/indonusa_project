<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\CustomQuotation;
use App\Models\CustomQuotationItem;

class CustomQuotationApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_approve_quotation()
    {
        // Create users
        $supervisor = User::factory()->create(['role' => 'Supervisor']);
        $sales = User::factory()->create(['role' => 'Sales']);

        // Create quotation
        $quotation = CustomQuotation::create([
            'sales_id' => $sales->id,
            'quotation_number' => 'TEST-001',
            'to' => 'Test Customer',
            'up' => $sales->name,
            'subject' => 'Test Offer',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-001',
            'date' => now(),
            'status' => 'pending_approval',
            'subtotal' => 1000000,
            'grand_total' => 1000000,
        ]);

        // Create item
        CustomQuotationItem::create([
            'custom_quotation_id' => $quotation->id,
            'product_name' => 'Test Product',
            'qty' => 1,
            'unit' => 'pcs',
            'price' => 1000000,
            'subtotal' => 1000000,
        ]);

        // Test approval
        $response = $this->actingAs($supervisor)
            ->post(route('admin.custom-quotation-approval.approval', $quotation), [
                'action' => 'approve'
            ]);

        $this->assertTrue($response->status() === 302 || $response->status() === 200);
        $this->assertEquals('approved_supervisor', $quotation->fresh()->status);
    }

    public function test_supervisor_can_reject_quotation()
    {
        // Create users
        $supervisor = User::factory()->create(['role' => 'Supervisor']);
        $sales = User::factory()->create(['role' => 'Sales']);

        // Create quotation
        $quotation = CustomQuotation::create([
            'sales_id' => $sales->id,
            'quotation_number' => 'TEST-002',
            'to' => 'Test Customer',
            'up' => $sales->name,
            'subject' => 'Test Offer',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-002',
            'date' => now(),
            'status' => 'pending_approval',
            'subtotal' => 1000000,
            'grand_total' => 1000000,
        ]);

        // Create item
        CustomQuotationItem::create([
            'custom_quotation_id' => $quotation->id,
            'product_name' => 'Test Product',
            'qty' => 1,
            'unit' => 'pcs',
            'price' => 1000000,
            'subtotal' => 1000000,
        ]);

        // Test rejection (rejection requires 'reason')
        $response = $this->actingAs($supervisor)
            ->post(route('admin.custom-quotation-approval.approval', $quotation), [
                'action' => 'reject',
                'reason' => 'Harga tidak masuk akal'
            ]);

        $this->assertTrue($response->status() === 302 || $response->status() === 200);
        $this->assertEquals('rejected_supervisor', $quotation->fresh()->status);
    }

    public function test_non_supervisor_cannot_approve_quotation()
    {
        // Create users
        $sales = User::factory()->create(['role' => 'Sales']);
        $other_sales = User::factory()->create(['role' => 'Sales']);

        // Create quotation
        $quotation = CustomQuotation::create([
            'sales_id' => $sales->id,
            'quotation_number' => 'TEST-003',
            'to' => 'Test Customer',
            'up' => $sales->name,
            'subject' => 'Test Offer',
            'email' => 'customer@test.com',
            'our_ref' => 'REF-003',
            'date' => now(),
            'status' => 'pending_approval',
            'subtotal' => 1000000,
            'grand_total' => 1000000,
        ]);

        // Create item
        CustomQuotationItem::create([
            'custom_quotation_id' => $quotation->id,
            'product_name' => 'Test Product',
            'qty' => 1,
            'unit' => 'pcs',
            'price' => 1000000,
            'subtotal' => 1000000,
        ]);

        // Test that non-supervisor is denied
        $response = $this->actingAs($other_sales)
            ->post(route('admin.custom-quotation-approval.approval', $quotation), [
                'action' => 'approve'
            ]);

        $this->assertEquals(403, $response->status());
    }
}
