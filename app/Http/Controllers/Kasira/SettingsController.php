<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();

        return view('kasira.pengaturan', [
            'business' => $business,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'business_type' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
            'low_stock_threshold' => ['required', 'integer', 'min:1'],
            'receipt_footer' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'remove_logo' => ['nullable', 'boolean'],
        ], [
            'logo.max' => 'Ukuran logo maksimal 5 MB.',
            'logo.image' => 'File yang diunggah harus berupa gambar.',
            'logo.mimes' => 'Format logo harus JPG, PNG, atau WEBP.',
        ]);

        $logoPath = $business->logo_path;

        if ($request->boolean('remove_logo') && $logoPath) {
            Storage::disk('public')->delete($logoPath);
            $logoPath = null;
        }

        if ($request->hasFile('logo')) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('business_logos', 'public');
        }

        $business->update([
            'name' => $validated['name'],
            'business_type' => $validated['business_type'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'low_stock_threshold' => $validated['low_stock_threshold'],
            'receipt_footer' => $validated['receipt_footer'] ?? null,
            'logo_path' => $logoPath,
        ]);

        return back()->with('success', 'Pengaturan bisnis Anda berhasil disimpan.');
    }
}
