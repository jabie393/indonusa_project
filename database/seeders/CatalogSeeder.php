<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalogs = [
            [
                'brand_name' => 'IT & Gadget',
                'catalog_name' => 'Apple Product Catalog 2024',
                'catalog_file' => '-',
            ],
            [
                'brand_name' => 'Office Furniture',
                'catalog_name' => 'Ergonomic Chair Series',
                'catalog_file' => '-',
            ],
            [
                'brand_name' => 'Networking',
                'catalog_name' => 'Cisco Router Guide',
                'catalog_file' => '-',
            ],
            [
                'brand_name' => 'Stationery',
                'catalog_name' => 'Premium Paper & Pens',
                'catalog_file' => '-',
            ],
        ];

        foreach ($catalogs as $catalog) {
            \App\Models\Catalog::create($catalog);
        }
    }
}
