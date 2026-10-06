<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();

        $query = $business->customers();

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('kasira.pelanggan', [
            'business' => $business,
            'customers' => $customers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $business->customers()->create($validated);

        return back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Request $request, Customer $customer): View
    {
        $business = $request->user()->currentBusiness();

        abort_unless($customer->business_id === $business->id, 403, 'Akses tidak sah.');

        $transactions = $customer->transactions()
            ->where('business_id', $business->id)
            ->with('items')
            ->latest()
            ->paginate(15);

        return view('kasira.pelanggan.show', [
            'business' => $business,
            'customer' => $customer,
            'transactions' => $transactions,
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        abort_unless($customer->business_id === $business->id, 403, 'Akses tidak sah.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $customer->update($validated);

        return back()->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Request $request, Customer $customer): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        abort_unless($customer->business_id === $business->id, 403, 'Akses tidak sah.');

        // Transactions will retain customer_name but customer_id becomes null (nullOnDelete)
        $customer->delete();

        return redirect()->route('kasira.pelanggan')->with('success', 'Pelanggan berhasil dihapus. Riwayat transaksi tetap tersimpan.');
    }
}
