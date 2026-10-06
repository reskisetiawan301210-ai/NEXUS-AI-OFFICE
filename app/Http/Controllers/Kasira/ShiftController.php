<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();
        $activeShift = $business->activeShift();

        $shiftSummary = null;
        if ($activeShift) {
            $transactions = $business->transactions()
                ->where('created_at', '>=', $activeShift->opened_at)
                ->where('status', 'completed')
                ->get();

            $totalSales = (float) $transactions->sum('total_amount');
            $cashSales = (float) $transactions->where('payment_method', 'cash')->sum('total_amount');
            $qrisSales = (float) $transactions->where('payment_method', 'qris')->sum('total_amount');
            $expectedCash = $activeShift->initial_cash + $cashSales;

            $shiftSummary = [
                'total_sales' => $totalSales,
                'cash_sales' => $cashSales,
                'qris_sales' => $qrisSales,
                'expected_cash' => $expectedCash,
                'transaction_count' => $transactions->count(),
            ];
        }

        $shifts = $business->shifts()->with('user')->latest('opened_at')->paginate(15);

        return view('kasira.shift.index', [
            'business' => $business,
            'activeShift' => $activeShift,
            'shiftSummary' => $shiftSummary,
            'shifts' => $shifts,
        ]);
    }

    public function open(Request $request): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        if ($business->activeShift()) {
            return back()->with('error', 'Masih ada shift kasir yang sedang aktif. Silakan tutup shift terlebih dahulu.');
        }

        $validated = $request->validate([
            'initial_cash' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $business->shifts()->create([
            'user_id' => $request->user()->id,
            'opened_at' => Carbon::now(),
            'initial_cash' => $validated['initial_cash'],
            'status' => 'open',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('kasira.shift.index')->with('success', 'Shift kasir berhasil dibuka.');
    }

    public function close(Request $request, CashierShift $shift): RedirectResponse
    {
        $business = $request->user()->currentBusiness();

        if ($shift->business_id !== $business->id || ! $shift->isOpen()) {
            abort(403, 'Shift tidak valid atau sudah ditutup.');
        }

        $validated = $request->validate([
            'actual_cash' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transactions = $business->transactions()
            ->where('created_at', '>=', $shift->opened_at)
            ->where('status', 'completed')
            ->get();

        $totalSales = (float) $transactions->sum('total_amount');
        $cashSales = (float) $transactions->where('payment_method', 'cash')->sum('total_amount');
        $qrisSales = (float) $transactions->where('payment_method', 'qris')->sum('total_amount');
        $expectedCash = $shift->initial_cash + $cashSales;
        $actualCash = (float) $validated['actual_cash'];
        $difference = $actualCash - $expectedCash;

        $shift->update([
            'closed_at' => Carbon::now(),
            'total_sales' => $totalSales,
            'cash_sales' => $cashSales,
            'qris_sales' => $qrisSales,
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'difference' => $difference,
            'status' => 'closed',
            'notes' => $shift->notes.($validated['notes'] ? ' | Tutup: '.$validated['notes'] : ''),
        ]);

        return redirect()->route('kasira.shift.index')->with('success', 'Shift kasir berhasil ditutup.');
    }
}
