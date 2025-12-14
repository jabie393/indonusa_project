<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BackfillSalesOrderNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RequestOrder::whereNull('sales_order_number')->each(function ($requestOrder) {
            $requestOrder->update(['sales_order_number' => 'SO-' . strtoupper(\Illuminate\Support\Str::random(8))]);
        });
    }
}
