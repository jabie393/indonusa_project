<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'company_name' => 'PT. INDONUSA JAYA BERSAMA',
            'company_address' => 'Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296',
            'company_phone' => '08121634173',
            'company_email' => 'info@indonusa.com',
            'leader_name' => 'Alimul Imam S.AP',
            'leader_position' => 'Direktur',
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
