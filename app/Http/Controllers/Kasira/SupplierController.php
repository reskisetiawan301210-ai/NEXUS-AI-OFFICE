<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();

        $query = $business->suppliers()->withCount('products');

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest()->paginate(15);

        return view('kasira.supplier.index', [
            'business' => $business,
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $business->suppliers()->create($validated);

        return redirect()->route('kasira.supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        if ($supplier->business_id !== $business->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $supplier->update($validated);

        return redirect()->route('kasira.supplier.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Request $request, Supplier $supplier): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        if ($supplier->business_id !== $business->id) {
            abort(403);
        }

        $supplier->delete();

        return redirect()->route('kasira.supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
