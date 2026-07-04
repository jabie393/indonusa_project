<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SystemSettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'company_name' => SystemSetting::get('company_name', 'PT. INDONUSA JAYA BERSAMA'),
            'company_address' => SystemSetting::get('company_address', 'Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296'),
            'company_phone' => SystemSetting::get('company_phone', '08121634173'),
            'company_email' => SystemSetting::get('company_email', 'info@indonusa.com'),
            'leader_name' => SystemSetting::get('leader_name', 'Alimul Imam S.AP'),
            'leader_position' => SystemSetting::get('leader_position', 'Direktur'),
        ];

        return view('admin.wms-settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:50',
            'company_email' => 'required|email|max:255',
            'leader_name' => 'required|string|max:255',
            'leader_position' => 'required|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }

        return redirect()->route('wms-settings.index')->with([
            'title' => 'Berhasil',
            'text' => 'Pengaturan sistem berhasil diperbarui.'
        ]);
    }
}
