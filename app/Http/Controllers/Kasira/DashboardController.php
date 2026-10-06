<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();

        // 1. Today's Key Metrics
        $todayOmzet = (float) $business->transactions()
            ->completed()
            ->today()
            ->sum('total_amount');

        $todayTransactionsCount = $business->transactions()
            ->completed()
            ->today()
            ->count();

        $todayProductsSold = (int) TransactionItem::whereHas('transaction', function ($q) use ($business) {
            $q->where('business_id', $business->id)
                ->where('status', 'completed')
                ->whereDate('transactions.created_at', Carbon::today());
        })->sum('quantity');

        // Real Gross Profit (Laba Kotor = Omzet - Modal)
        $todayGrossProfit = (float) (TransactionItem::whereHas('transaction', function ($q) use ($business) {
            $q->where('business_id', $business->id)
                ->where('status', 'completed')
                ->whereDate('transactions.created_at', Carbon::today());
        })->selectRaw('SUM((price - cost_price) * quantity) as profit')->value('profit') ?? 0);

        $lowStockProducts = $business->products()
            ->active()
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();

        $activeShift = $business->activeShift();

        // 2. Sales Chart (7 / 30 / 90 Days)
        $chartDays = (int) $request->query('days', 7);
        if (! in_array($chartDays, [7, 30, 90], true)) {
            $chartDays = 7;
        }

        $salesChartLabels = [];
        $salesChartData = [];
        $today = Carbon::today();

        for ($i = $chartDays - 1; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dayLabel = $chartDays > 30 ? $date->isoFormat('D/M') : $date->isoFormat('D MMM');
            $dayTotal = (float) $business->transactions()
                ->completed()
                ->whereDate('created_at', $date)
                ->sum('total_amount');

            $salesChartLabels[] = $dayLabel;
            $salesChartData[] = $dayTotal;
        }

        // 3. Best Selling Products
        $bestSellingProducts = TransactionItem::query()
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('MAX(price) as last_price')
            )
            ->whereHas('transaction', function ($query) use ($business) {
                $query->where('business_id', $business->id)->where('status', 'completed');
            })
            ->with('product')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 4. Recent Transactions
        $recentTransactions = $business->transactions()
            ->with(['items'])
            ->latest()
            ->limit(5)
            ->get();

        // 5. Popular Products for Quick Ordering
        $categories = $business->categories()->orderBy('name')->get();

        $selectedCategory = $request->query('category');
        $searchQuery = $request->query('q');

        $productsQuery = $business->products()
            ->active()
            ->with('category');

        if ($selectedCategory) {
            $productsQuery->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        if ($searchQuery) {
            $productsQuery->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('barcode', 'like', "%{$searchQuery}%");
            });
        }

        $popularProducts = $productsQuery->latest()->limit(10)->get();

        return view('kasira.dashboard', [
            'business' => $business,
            'todayOmzet' => $todayOmzet,
            'todayTransactionsCount' => $todayTransactionsCount,
            'todayProductsSold' => $todayProductsSold,
            'todayGrossProfit' => $todayGrossProfit,
            'lowStockProducts' => $lowStockProducts,
            'activeShift' => $activeShift,
            'salesChartLabels' => $salesChartLabels,
            'salesChartData' => $salesChartData,
            'chartDays' => $chartDays,
            'bestSellingProducts' => $bestSellingProducts,
            'recentTransactions' => $recentTransactions,
            'categories' => $categories,
            'popularProducts' => $popularProducts,
        ]);
    }
}
