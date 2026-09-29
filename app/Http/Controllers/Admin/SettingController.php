<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public const KEYS = ['store_name', 'tagline', 'about', 'years_experience', 'whatsapp', 'address', 'instagram', 'hours', 'maps_url'];

    public function edit()
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'required|string|max:100',
            'tagline' => 'nullable|string|max:300',
            'about' => 'nullable|string|max:1500',
            'years_experience' => 'nullable|integer|min:0|max:100',
            'whatsapp' => ['nullable', 'regex:/^62\d{7,13}$/'],
            'address' => 'nullable|string|max:300',
            'instagram' => 'nullable|url|max:200',
            'hours' => 'nullable|string|max:150',
            'maps_url' => 'nullable|url|max:500',
        ], [
            'whatsapp.regex' => 'Nomor WhatsApp harus diawali 62 tanpa tanda + atau spasi, contoh 628123456789.',
            'instagram.url' => 'Link Instagram harus berupa URL lengkap (https://...).',
            'maps_url.url' => 'Link Maps harus berupa URL lengkap (https://...).',
        ]);
        foreach (self::KEYS as $k) {
            Setting::put($k, $data[$k] ?? null);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
