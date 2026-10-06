<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarcodeController extends Controller
{
    public function index(): View
    {
        return view('kasira.barcode');
    }

    public function search(Request $request): JsonResponse
    {
        $business = $request->user()->currentBusiness();
        $barcode = trim($request->query('barcode', ''));

        if (empty($barcode)) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak boleh kosong.',
            ], 422);
        }

        $product = $business->products()
            ->active()
            ->where('barcode', $barcode)
            ->with('category')
            ->first();

        if ($product) {
            return response()->json([
                'found' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'barcode' => $product->barcode,
                    'selling_price' => (float) $product->selling_price,
                    'formatted_price' => 'Rp '.number_format($product->selling_price, 0, ',', '.'),
                    'stock' => $product->stock,
                    'min_stock' => $product->min_stock,
                    'category' => $product->category?->name ?? 'Umum',
                    'image_url' => $product->image_path ? asset('storage/'.$product->image_path) : null,
                    'is_low_stock' => $product->stock <= $product->min_stock,
                ],
            ]);
        }

        return response()->json([
            'found' => false,
            'barcode' => $barcode,
            'register_url' => route('kasira.produk.create', ['barcode' => $barcode]),
        ]);
    }
}
