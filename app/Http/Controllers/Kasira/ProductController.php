<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();
        $categories = $business->categories()->orderBy('name')->get();

        $query = $business->products()->with('category');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        match ($request->query('filter')) {
            'active' => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            'low_stock' => $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0),
            'out_of_stock' => $query->where('stock', '<=', 0),
            default => null,
        };

        match ($request->query('sort')) {
            'name_asc' => $query->orderBy('name'),
            'price_asc' => $query->orderBy('selling_price'),
            'price_desc' => $query->orderByDesc('selling_price'),
            'stock_asc' => $query->orderBy('stock'),
            default => $query->latest(),
        };

        $products = $query->paginate(15)->withQueryString();

        return view('kasira.produk.index', [
            'products' => $products,
            'categories' => $categories,
            'business' => $business,
        ]);
    }

    public function create(Request $request): View
    {
        $business = $request->user()->currentBusiness();
        $categories = $business->categories()->orderBy('name')->get();
        $suppliers = $business->suppliers()->orderBy('name')->get();

        return view('kasira.produk.create', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'initialBarcode' => $request->query('barcode', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('business_id', $business->id)],
            'supplier_id' => ['nullable', Rule::exists('suppliers', 'id')->where('business_id', $business->id)],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'photo.max' => 'Ukuran foto maksimal 5 MB.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
        ]);

        // Verify barcode uniqueness within this business
        if (! empty($validated['barcode'])) {
            $barcodeExists = $business->products()
                ->where('barcode', $validated['barcode'])
                ->exists();

            if ($barcodeExists) {
                return back()->withInput()->withErrors(['barcode' => 'Barcode ini sudah terdaftar untuk produk lain di toko Anda.']);
            }
        }

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('product_photos', 'public');
        }

        $business->products()->create([
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'name' => $validated['name'],
            'barcode' => $validated['barcode'] ?? null,
            'cost_price' => $validated['cost_price'],
            'selling_price' => $validated['selling_price'],
            'stock' => $validated['stock'],
            'min_stock' => $validated['min_stock'],
            'image_path' => $imagePath,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('kasira.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Request $request, Product $product): View
    {
        $business = $request->user()->currentBusiness();

        abort_unless($product->business_id === $business->id, 403, 'Akses tidak sah.');

        $categories = $business->categories()->orderBy('name')->get();
        $suppliers = $business->suppliers()->orderBy('name')->get();

        return view('kasira.produk.edit', [
            'product' => $product,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        abort_unless($product->business_id === $business->id, 403, 'Akses tidak sah.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('business_id', $business->id)],
            'supplier_id' => ['nullable', Rule::exists('suppliers', 'id')->where('business_id', $business->id)],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'photo.max' => 'Ukuran foto maksimal 5 MB.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
        ]);

        // Barcode uniqueness check within current business (excluding self)
        if (! empty($validated['barcode'])) {
            $barcodeExists = $business->products()
                ->where('barcode', $validated['barcode'])
                ->where('id', '!=', $product->id)
                ->exists();

            if ($barcodeExists) {
                return back()->withInput()->withErrors(['barcode' => 'Barcode ini sudah terdaftar untuk produk lain di toko Anda.']);
            }
        }

        $imagePath = $product->image_path;

        if ($request->boolean('remove_photo')) {
            if ($imagePath) {
                $this->deleteImageIfUnused($imagePath, $product);
            }
            $imagePath = null;
        }

        if ($request->hasFile('photo')) {
            if ($imagePath) {
                $this->deleteImageIfUnused($imagePath, $product);
            }
            $imagePath = $request->file('photo')->store('product_photos', 'public');
        }

        $product->update([
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'name' => $validated['name'],
            'barcode' => $validated['barcode'] ?? null,
            'cost_price' => $validated['cost_price'],
            'selling_price' => $validated['selling_price'],
            'stock' => $validated['stock'],
            'min_stock' => $validated['min_stock'],
            'image_path' => $imagePath,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('kasira.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        abort_unless($product->business_id === $business->id, 403, 'Akses tidak sah.');

        if ($product->transactionItems()->exists()) {
            $product->update(['is_active' => false]);

            return redirect()->route('kasira.produk.index')->with('success', 'Produk telah dinonaktifkan karena sudah tercatat dalam transaksi.');
        }

        if ($product->image_path) {
            $this->deleteImageIfUnused($product->image_path, $product);
        }

        $product->delete();

        return redirect()->route('kasira.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function updateStatus(Request $request, Product $product): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        abort_unless($product->business_id === $business->id, 403, 'Akses tidak sah.');

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $product->update(['is_active' => $validated['is_active']]);

        $message = $validated['is_active'] ? 'Produk berhasil diaktifkan.' : 'Produk berhasil dinonaktifkan.';

        return redirect()->route('kasira.produk.index')->with('success', $message);
    }

    private function deleteImageIfUnused(string $imagePath, Product $product): void
    {
        $isUsedByAnotherProduct = Product::query()
            ->where('image_path', $imagePath)
            ->whereKeyNot($product->id)
            ->exists();

        if (! $isUsedByAnotherProduct) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
