<?php

namespace App\Http\Controllers\Kasira;

use App\Http\Controllers\Controller;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Resolve date range based on period filter.
     *
     * @return array{0: Carbon, 1: Carbon, 2: string, 3: string}
     */
    private function resolveDateRange(Request $request): array
    {
        $period = $request->query('period', 'this_month');
        $today = Carbon::today();

        switch ($period) {
            case 'today':
                $start = $today->copy()->startOfDay();
                $end = $today->copy()->endOfDay();
                $label = 'Hari Ini ('.$today->translatedFormat('d M Y').')';
                break;

            case '7days':
                $start = $today->copy()->subDays(6)->startOfDay();
                $end = $today->copy()->endOfDay();
                $label = '7 Hari Terakhir ('.$start->translatedFormat('d M').' - '.$end->translatedFormat('d M Y').')';
                break;

            case '30days':
                $start = $today->copy()->subDays(29)->startOfDay();
                $end = $today->copy()->endOfDay();
                $label = '30 Hari Terakhir ('.$start->translatedFormat('d M').' - '.$end->translatedFormat('d M Y').')';
                break;

            case 'custom':
                $startInput = $request->query('start_date');
                $endInput = $request->query('end_date');

                try {
                    $start = $startInput ? Carbon::parse($startInput)->startOfDay() : Carbon::now()->startOfMonth();
                    $end = $endInput ? Carbon::parse($endInput)->endOfDay() : Carbon::now()->endOfDay();
                } catch (\Throwable $e) {
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                }
                $label = 'Kustom ('.$start->translatedFormat('d M Y').' - '.$end->translatedFormat('d M Y').')';
                break;

            case 'this_month':
            default:
                $period = 'this_month';
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $label = 'Bulan Ini ('.Carbon::now()->translatedFormat('F Y').')';
                break;
        }

        return [$start, $end, $label, $period];
    }

    public function index(Request $request): View
    {
        $business = $request->user()->currentBusiness();
        [$startDate, $endDate, $periodLabel, $selectedPeriod] = $this->resolveDateRange($request);

        // Filtered transactions base query
        $trxQuery = $business->transactions()
            ->completed()
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Key metrics
        $periodOmzet = (float) (clone $trxQuery)->sum('total_amount');
        $periodTrxCount = (int) (clone $trxQuery)->count();

        // Products sold in period
        $periodProductsSold = (int) TransactionItem::whereHas('transaction', function ($q) use ($business, $startDate, $endDate) {
            $q->where('business_id', $business->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantity');

        // Gross profit in period
        $periodGrossProfit = (float) (TransactionItem::whereHas('transaction', function ($q) use ($business, $startDate, $endDate) {
            $q->where('business_id', $business->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })->selectRaw('SUM((price - cost_price) * quantity) as profit')->value('profit') ?? 0);

        // Payment method breakdown
        $paymentBreakdown = (clone $trxQuery)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Top 5 best selling products in this period
        $topProducts = TransactionItem::query()
            ->select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('transaction', function ($query) use ($business, $startDate, $endDate) {
                $query->where('business_id', $business->id)
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Transactions list with pagination
        $transactions = (clone $trxQuery)
            ->with(['items', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('kasira.laporan', [
            'business' => $business,
            'periodOmzet' => $periodOmzet,
            'periodTrxCount' => $periodTrxCount,
            'periodProductsSold' => $periodProductsSold,
            'periodGrossProfit' => $periodGrossProfit,
            'paymentBreakdown' => $paymentBreakdown,
            'topProducts' => $topProducts,
            'transactions' => $transactions,
            'selectedPeriod' => $selectedPeriod,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    public function statistics(Request $request): View
    {
        return $this->index($request);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $business = $request->user()->currentBusiness();
        [$startDate, $endDate, $periodLabel] = $this->resolveDateRange($request);

        $transactions = $business->transactions()
            ->completed()
            ->with(['items', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $filename = 'laporan-penjualan-'.$business->slug.'-'.$startDate->format('Ymd').'-'.$endDate->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No. Invoice',
                'Tanggal & Waktu',
                'Kasir',
                'Pelanggan',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Total Belanja (Rp)',
                'Uang Diterima (Rp)',
                'Kembalian (Rp)',
                'Jumlah Item',
            ]);

            foreach ($transactions as $trx) {
                fputcsv($handle, [
                    $trx->invoice_number,
                    $trx->created_at->format('d/m/Y H:i:s'),
                    $trx->user?->name ?? 'Kasir',
                    $trx->customer_name ?: 'Umum',
                    strtoupper($trx->payment_method),
                    strtoupper($trx->payment_status),
                    (float) $trx->total_amount,
                    (float) $trx->paid_amount,
                    (float) $trx->change_amount,
                    $trx->items->sum('quantity'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
