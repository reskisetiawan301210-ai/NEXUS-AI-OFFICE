<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();

        $query = $business->transactions()->with(['items', 'user']);

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($method = $request->query('method')) {
            $query->where('payment_method', $method);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $allTimeSales = (float) $business->transactions()->completed()->sum('total_amount');
        $allTimeCount = $business->transactions()->completed()->count();

        return view('kasira.penjualan.index', [
            'transactions' => $transactions,
            'allTimeSales' => $allTimeSales,
            'allTimeCount' => $allTimeCount,
        ]);
    }

    public function show(Request $request, Transaction $transaction): View
    {
        $business = $request->user()->currentBusiness();

        if ($transaction->business_id !== $business->id) {
            abort(403, 'Akses tidak sah.');
        }

        $transaction->load(['items.product', 'user']);

        return view('kasira.penjualan.show', [
            'transaction' => $transaction,
            'business' => $business,
        ]);
    }
}
