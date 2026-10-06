<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DebtController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();

        $query = $business->debts()->with(['customer', 'transaction']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $debts = $query->latest()->paginate(15);
        $customers = $business->customers()->orderBy('name')->get();

        $totalUnpaid = (float) $business->debts()->where('status', '!=', 'paid')->sum('remaining_amount');
        $unpaidCount = $business->debts()->where('status', '!=', 'paid')->count();

        return view('kasira.piutang.index', [
            'business' => $business,
            'debts' => $debts,
            'customers' => $customers,
            'totalUnpaid' => $totalUnpaid,
            'unpaidCount' => $unpaidCount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'total_amount' => ['required', 'numeric', 'min:1000'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $business->debts()->create([
            'customer_id' => $validated['customer_id'],
            'total_amount' => $validated['total_amount'],
            'paid_amount' => 0,
            'remaining_amount' => $validated['total_amount'],
            'due_date' => $validated['due_date'] ?? null,
            'status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('kasira.piutang.index')->with('success', 'Piutang pelanggan berhasil dicatat.');
    }

    public function pay(Request $request, Debt $debt): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        if ($debt->business_id !== $business->id) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000', 'max:'.$debt->remaining_amount],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $newPaidAmount = $debt->paid_amount + $validated['amount'];
        $newRemainingAmount = max(0, $debt->total_amount - $newPaidAmount);
        $newStatus = $newRemainingAmount <= 0 ? 'paid' : 'partial';

        $debt->update([
            'paid_amount' => $newPaidAmount,
            'remaining_amount' => $newRemainingAmount,
            'status' => $newStatus,
            'notes' => $debt->notes.($validated['notes'] ? ' | Bayar: '.$validated['notes'] : ''),
        ]);

        return redirect()->route('kasira.piutang.index')->with('success', 'Pembayaran piutang berhasil dicatat.');
    }
}
