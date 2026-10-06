<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\QrisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();
        $categories = $business->categories()->orderBy('name')->get();

        $products = $business->products()
            ->active()
            ->with('category')
            ->orderBy('name')
            ->get();

        $customers = $business->customers()->orderBy('name')->get();

        $initialProductId = $request->query('add_product_id');

        return view('kasira.kasir', [
            'business' => $business,
            'categories' => $categories,
            'products' => $products,
            'customers' => $customers,
            'initialProductId' => $initialProductId,
        ]);
    }

    public function checkout(Request $request): JsonResponse
    {
        $business = $request->user()->currentBusiness();

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', 'in:cash,qris,transfer'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validated['payment_method'] === 'qris' && ! $business->isPro()) {
            return response()->json([
                'success' => false,
                'upgrade_required' => true,
                'message' => 'Metode QRIS eksklusif untuk pengguna Paket Pro. Silakan upgrade paket Kasira Anda untuk menggunakan pembayaran QRIS instan.',
            ], 403);
        }

        try {
            $activeShift = $business->activeShift();

            $transaction = DB::transaction(function () use ($business, $request, $validated, $activeShift) {
                $totalAmount = 0;
                $itemsToCreate = [];

                // 1. Verify products and check stocks
                foreach ($validated['items'] as $cartItem) {
                    $product = Product::where('business_id', $business->id)
                        ->lockForUpdate()
                        ->findOrFail($cartItem['product_id']);

                    if ($product->stock < $cartItem['quantity']) {
                        throw new \Exception("Stok untuk produk '{$product->name}' tidak mencukupi (tersedia: {$product->stock}).");
                    }

                    $subtotal = $product->selling_price * $cartItem['quantity'];
                    $totalAmount += $subtotal;

                    $itemsToCreate[] = [
                        'product' => $product,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->selling_price,
                        'cost_price' => $product->cost_price,
                        'quantity' => $cartItem['quantity'],
                        'subtotal' => $subtotal,
                    ];
                }

                $isQris = $validated['payment_method'] === 'qris';
                $paidAmount = $isQris ? $totalAmount : (float) ($validated['paid_amount'] ?? $totalAmount);

                if ($validated['payment_method'] === 'cash' && $paidAmount < $totalAmount) {
                    throw new \Exception('Nominal pembayaran tunai kurang dari total belanja (Total: Rp '.number_format($totalAmount, 0, ',', '.').').');
                }

                $changeAmount = max(0, $paidAmount - $totalAmount);

                // Generate readable invoice number: TRX-Ymd-0001
                $datePrefix = date('Ymd');
                $todayCount = $business->transactions()->whereDate('created_at', today())->count() + 1;
                $invoiceNumber = 'TRX-'.$datePrefix.'-'.str_pad((string) $todayCount, 4, '0', STR_PAD_LEFT);
                while (Transaction::where('invoice_number', $invoiceNumber)->exists()) {
                    $todayCount++;
                    $invoiceNumber = 'TRX-'.$datePrefix.'-'.str_pad((string) $todayCount, 4, '0', STR_PAD_LEFT);
                }

                // 2. Create Transaction
                $trx = $business->transactions()->create([
                    'user_id' => $request->user()->id,
                    'shift_id' => $activeShift?->id,
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $isQris ? 'pending' : 'paid',
                    'status' => $isQris ? 'pending' : 'completed',
                    'customer_name' => $validated['customer_name'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                // 3. Create items and decrement stocks if payment is immediately paid (e.g. cash)
                foreach ($itemsToCreate as $itemData) {
                    $product = $itemData['product'];
                    unset($itemData['product']);

                    $trx->items()->create($itemData);

                    if ($trx->payment_status === 'paid') {
                        $product->decrement('stock', $itemData['quantity']);
                    }
                }

                return $trx;
            });

            $qrisData = null;
            if ($transaction->payment_method === 'qris') {
                $qrisService = app(QrisService::class);
                $qrisData = $qrisService->generateQris($transaction);
            }

            $transaction->load(['items', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
                'qris' => $qrisData,
                'transaction' => [
                    'id' => $transaction->id,
                    'invoice_number' => $transaction->invoice_number,
                    'total_amount' => (float) $transaction->total_amount,
                    'formatted_total' => 'Rp '.number_format($transaction->total_amount, 0, ',', '.'),
                    'paid_amount' => (float) $transaction->paid_amount,
                    'change_amount' => (float) $transaction->change_amount,
                    'formatted_change' => 'Rp '.number_format($transaction->change_amount, 0, ',', '.'),
                    'payment_method' => $transaction->payment_method,
                    'payment_status' => $transaction->payment_status,
                    'customer_name' => $transaction->customer_name ?: 'Umum',
                    'created_at' => $transaction->created_at->format('d M Y H:i'),
                    'cashier_name' => $request->user()->name,
                    'business_name' => $business->name,
                    'business_phone' => $business->phone ?? '-',
                    'business_address' => $business->address ?? '-',
                    'receipt_footer' => $business->receipt_footer ?? 'Terima kasih telah berbelanja!',
                    'items' => $transaction->items->map(fn ($item) => [
                        'name' => $item->product_name,
                        'price' => (float) $item->price,
                        'formatted_price' => 'Rp '.number_format($item->price, 0, ',', '.'),
                        'quantity' => (int) $item->quantity,
                        'subtotal' => (float) $item->subtotal,
                        'formatted_subtotal' => 'Rp '.number_format($item->subtotal, 0, ',', '.'),
                    ])->values()->all(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function checkQrisStatus(Request $request, Transaction $transaction, QrisService $qrisService): JsonResponse
    {
        $business = $request->user()->currentBusiness();
        if ($transaction->business_id !== $business->id) {
            abort(403);
        }

        $status = $qrisService->checkStatus($transaction);
        if ($status === 'paid') {
            $transaction->markAsPaid();
        }

        return response()->json([
            'status' => $status,
            'is_paid' => $status === 'paid',
            'invoice_number' => $transaction->invoice_number,
        ]);
    }

    public function confirmQrisPayment(Request $request, Transaction $transaction): JsonResponse
    {
        $business = $request->user()->currentBusiness();
        if ($transaction->business_id !== $business->id) {
            abort(403);
        }

        $transaction->markAsPaid();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran QRIS telah dikonfirmasi berhasil.',
        ]);
    }
}
