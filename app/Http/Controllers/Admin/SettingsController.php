<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        // System settings view
        return view('admin.settings.index');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        // Store settings in cache or config
        // TODO: Implement proper settings storage (e.g., settings table)
        Cache::put('settings.site_name', $request->site_name);
        Cache::put('settings.site_email', $request->site_email);
        Cache::put('settings.default_commission_rate', $request->default_commission_rate);
        Cache::put('settings.vat_rate', $request->vat_rate);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function clearCache(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cache cleared successfully!');
    }
}
