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
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('password'),
            'role' => 'Operator',
        ]);

        User::factory()->create([
            'name' => 'Imam',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'role' => 'Supervisor',
        ]);

        User::factory()->create([
            'name' => 'Fahd',
            'email' => 'generalaffair@example.com',
            'password' => Hash::make('password'),
            'role' => 'General Affair',
        ]);

        User::factory()->create([
            'name' => 'Hilmi',
            'email' => 'sales@example.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::factory()->create([
            'name' => 'Ryujin',
            'email' => 'salesryujin@example.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::factory()->create([
            'name' => 'Usman',
            'email' => 'warehouse@example.com',
            'password' => Hash::make('password'),
            'role' => 'Warehouse',
        ]);
        User::factory()->create([
            'name' => 'Finance',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'),
            'role' => 'Finance',
        ]);

        $this->call(BarangSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
