<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BusinessSetupController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->currentBusiness()) {
            return redirect()->route('kasira.dashboard');
        }

        return view('kasira.setup');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->currentBusiness()) {
            return redirect()->route('kasira.dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'business_type' => ['required', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('business_logos', 'public');
        }

        $business = $request->user()->businesses()->create([
            'name' => $validated['name'],
            'business_type' => $validated['business_type'],
            'logo_path' => $logoPath,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'currency' => 'IDR',
            'low_stock_threshold' => 5,
        ]);

        // Default categories
        $defaultCategories = [
            'Makanan',
            'Minuman',
            'Snack',
            'Sembako',
            'Lainnya',
        ];

        foreach ($defaultCategories as $categoryName) {
            $business->categories()->create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);
        }

        return redirect()->route('kasira.dashboard')->with('success', 'Bisnis Anda berhasil disiapkan! Selamat datang di Kasira.');
    }
}
