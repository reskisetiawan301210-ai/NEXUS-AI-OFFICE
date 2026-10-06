<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();
        $query = $business->products()->with('category');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->query('filter') === 'low_stock') {
            $query->whereColumn('stock', '<=', 'min_stock');
        }

        $products = $query->orderBy('stock')->paginate(20)->withQueryString();

        $totalStockUnits = (int) $business->products()->sum('stock');
        $lowStockCount = $business->products()->whereColumn('stock', '<=', 'min_stock')->count();
        $outOfStockCount = $business->products()->where('stock', '<=', 0)->count();

        return view('kasira.stok', [
            'business' => $business,
            'products' => $products,
            'totalStockUnits' => $totalStockUnits,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        if ($product->business_id !== $business->id) {
            abort(403, 'Akses tidak sah.');
        }

        $validated = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($validated);

        return back()->with('success', "Stok untuk {$product->name} berhasil diperbarui.");
    }
}
