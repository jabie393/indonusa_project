<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
        ]);

        User::factory()->create([
            'name' => 'Admin PT',
            'email' => 'adminpt@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_PT',
        ]);

        User::factory()->create([
            'name' => 'Admin Supply',
            'email' => 'adminsupply@example.com',
            'password' => Hash::make('password'),
            'role' => 'adminsupply',
        ]);

        User::factory()->create([
            'name' => 'Admin Sales',
            'email' => 'adminsales@example.com',
            'password' => Hash::make('password'),
            'role' => 'adminsales',
        ]);

        User::factory()->create([
            'name' => 'Admin Warehouse',
            'email' => 'adminwarehouse@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_warehouse',
        ]);
    }
}
