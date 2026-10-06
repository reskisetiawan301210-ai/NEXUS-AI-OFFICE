<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();

        return view('kasira.pricing', [
            'business' => $business,
        ]);
    }

    public function selectPlan(Request $request): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:free,starter,pro'],
        ]);

        $business->update([
            'subscription_tier' => $validated['plan'],
            'subscription_expires_at' => $validated['plan'] !== 'free' ? now()->addDays(30) : null,
        ]);

        return back()->with('success', 'Paket langganan berhasil diperbarui menjadi '.ucfirst($validated['plan']).'.');
    }
}
