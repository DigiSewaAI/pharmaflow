<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage_settings')->only(['index', 'update']);
        $this->middleware('permission:view_settings')->only(['index']);
    }

    /**
     * Display settings page.
     */
    public function index()
    {
        // Load all settings grouped
        $settings = Setting::all()->groupBy('group')->map(function ($items) {
            return $items->pluck('value', 'key')->toArray();
        });

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings (bulk).
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Pharmacy
            'pharmacy_name' => 'nullable|string|max:255',
            'pharmacy_address' => 'nullable|string|max:500',
            'pharmacy_phone' => 'nullable|string|max:20',
            'pharmacy_email' => 'nullable|email|max:255',
            'tax_id' => 'nullable|string|max:50',

            // Currency
            'currency_symbol' => 'nullable|string|max:10',
            'currency_code' => 'nullable|string|max:10',

            // Tax
            'tax_rate' => 'nullable|numeric|min:0|max:100',

            // Invoice
            'invoice_prefix' => 'nullable|string|max:10',
            'invoice_footer' => 'nullable|string|max:255',

            // Theme
            'default_theme' => 'nullable|in:light,dark',

            // Other
            'timezone' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            // Determine group
            $group = 'general';
            if (in_array($key, ['pharmacy_name', 'pharmacy_address', 'pharmacy_phone', 'pharmacy_email', 'tax_id'])) {
                $group = 'pharmacy';
            } elseif (in_array($key, ['currency_symbol', 'currency_code'])) {
                $group = 'currency';
            } elseif (in_array($key, ['tax_rate'])) {
                $group = 'tax';
            } elseif (in_array($key, ['invoice_prefix', 'invoice_footer'])) {
                $group = 'invoice';
            } elseif (in_array($key, ['default_theme'])) {
                $group = 'theme';
            } elseif (in_array($key, ['timezone'])) {
                $group = 'general';
            }

            Setting::set($key, $value, $group);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully.'
            ]);
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}