@extends('layouts.kasira')

@section('title', 'Laporan & Statistik Bisnis')

@section('content')
<div class="space-y-6">
    <!-- Header & Period Filter Controls -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Laporan & Analitik Bisnis</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Analisis pendapatan, rasio pembayaran, dan performa omzet: <span class="font-bold text-[#5300B7]">{{ $periodLabel }}</span></p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Preset Period Buttons -->
            <div class="inline-flex p-1 rounded-xl bg-white border border-[#E5E7EB] shadow-2xs text-xs font-semibold">
                <a href="{{ route('kasira.laporan', ['period' => 'today']) }}"
                   class="px-3 py-1.5 rounded-lg transition {{ $selectedPeriod === 'today' ? 'bg-[#5300B7] text-white shadow-2xs' : 'text-[#6B7280] hover:text-[#111827]' }}">
                    Hari Ini
                </a>
                <a href="{{ route('kasira.laporan', ['period' => '7days']) }}"
                   class="px-3 py-1.5 rounded-lg transition {{ $selectedPeriod === '7days' ? 'bg-[#5300B7] text-white shadow-2xs' : 'text-[#6B7280] hover:text-[#111827]' }}">
                    7 Hari
                </a>
                <a href="{{ route('kasira.laporan', ['period' => '30days']) }}"
                   class="px-3 py-1.5 rounded-lg transition {{ $selectedPeriod === '30days' ? 'bg-[#5300B7] text-white shadow-2xs' : 'text-[#6B7280] hover:text-[#111827]' }}">
                    30 Hari
                </a>
                <a href="{{ route('kasira.laporan', ['period' => 'this_month']) }}"
                   class="px-3 py-1.5 rounded-lg transition {{ $selectedPeriod === 'this_month' ? 'bg-[#5300B7] text-white shadow-2xs' : 'text-[#6B7280] hover:text-[#111827]' }}">
                    Bulan Ini
                </a>
            </div>

            <!-- Export CSV Button -->
            <a href="{{ route('kasira.laporan.export', request()->query()) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-[#E5E7EB] hover:bg-[#F9FAFB] text-xs font-bold text-[#111827] shadow-2xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    <!-- Custom Date Filter Form -->
    <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
        <form action="{{ route('kasira.laporan') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="period" value="custom">

            <div class="flex items-center gap-2 text-xs font-semibold text-[#6B7280]">
                <svg class="w-4 h-4 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Filter Tanggal:</span>
            </div>

            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="px-2.5 py-1.5 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs font-medium text-[#111827] focus:border-[#5300B7] outline-none">
                <span class="text-xs text-[#9CA3AF]">s/d</span>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="px-2.5 py-1.5 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs font-medium text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <button type="submit"
                    class="px-3.5 py-1.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-2xs">
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: Total Omzet -->
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex items-center justify-between text-[#6B7280]">
                <span class="text-xs font-semibold">Total Omzet</span>
                <div class="w-7 h-7 rounded-lg bg-[#5300B7]/10 text-[#5300B7] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-[#5300B7] mt-2">
                Rp {{ number_format($periodOmzet, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Pendapatan kotor periode ini</span>
        </div>

        <!-- Metric 2: Laba Kotor -->
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex items-center justify-between text-emerald-700">
                <span class="text-xs font-semibold">Laba Kotor</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-emerald-600 mt-2">
                Rp {{ number_format($periodGrossProfit, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Omzet dikurangi harga modal</span>
        </div>

        <!-- Metric 3: Total Transaksi -->
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex items-center justify-between text-[#6B7280]">
                <span class="text-xs font-semibold">Total Transaksi</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-[#111827] mt-2">
                {{ number_format($periodTrxCount, 0, ',', '.') }}
            </div>
            <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Struk transaksi berhasil</span>
        </div>

        <!-- Metric 4: Produk Terjual -->
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex items-center justify-between text-[#6B7280]">
                <span class="text-xs font-semibold">Produk Terjual</span>
                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-[#111827] mt-2">
                {{ number_format($periodProductsSold, 0, ',', '.') }} pcs
            </div>
            <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Total kuantitas item keluar</span>
        </div>
    </div>

    <!-- Middle Row: Payment Methods & Top Selling Products -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Payment Breakdown (5 cols) -->
        <div class="lg:col-span-5 bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-[#111827] flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#5300B7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>Metode Pembayaran</span>
                </h2>
                <p class="text-[11px] text-[#6B7280] mt-0.5">Sebaran omzet berdasarkan cara bayar pelanggan</p>

                <div class="mt-4 space-y-2.5">
                    @forelse($paymentBreakdown as $pb)
                        @php
                            $pct = $periodOmzet > 0 ? round(($pb->total / $periodOmzet) * 100, 1) : 0;
                        @endphp
                        <div class="p-3 rounded-xl bg-[#F9FAFB] border border-[#E5E7EB] space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="uppercase font-bold text-[#111827]">{{ $pb->payment_method }}</span>
                                <span class="font-bold text-[#5300B7]">Rp {{ number_format($pb->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-[#E5E7EB] h-1.5 rounded-full overflow-hidden">
                                <div class="bg-[#5300B7] h-full rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-[#6B7280]">
                                <span>{{ $pb->count }} transaksi</span>
                                <span>{{ $pct }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-[#9CA3AF] text-xs">
                            Belum ada data pembayaran di periode ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Selling Products (7 cols) -->
        <div class="lg:col-span-7 bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-[#111827] flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span>5 Produk Terlaris Periode Ini</span>
                </h2>
                <p class="text-[11px] text-[#6B7280] mt-0.5">Produk dengan kuantitas penjualan tertinggi</p>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#F3F4F6] text-[#9CA3AF]">
                                <th class="pb-2 font-bold uppercase tracking-wider text-[10px]">Produk</th>
                                <th class="pb-2 font-bold uppercase tracking-wider text-[10px] text-center">Terjual</th>
                                <th class="pb-2 font-bold uppercase tracking-wider text-[10px] text-right">Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F4F6]">
                            @forelse($topProducts as $idx => $p)
                                <tr>
                                    <td class="py-2.5 flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full {{ $idx === 0 ? 'bg-amber-100 text-amber-700' : 'bg-[#F3F4F6] text-[#6B7280]' }} text-[10px] font-bold flex items-center justify-center shrink-0">
                                            {{ $idx + 1 }}
                                        </span>
                                        <span class="font-bold text-[#111827] truncate">{{ $p->product_name }}</span>
                                    </td>
                                    <td class="py-2.5 text-center font-semibold text-[#111827]">{{ $p->total_qty }} pcs</td>
                                    <td class="py-2.5 text-right font-bold text-[#5300B7]">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-12 text-center text-[#9CA3AF] text-xs">
                                        Belum ada penjualan produk pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom: Filtered Transactions Table -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-[#F3F4F6] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-bold text-[#111827]">Daftar Transaksi Periode Ini</h2>
                <p class="text-[11px] text-[#6B7280]">Total {{ $transactions->total() }} transaksi tercatat</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F9FAFB] text-[#6B7280] uppercase text-[10px] font-bold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-4 py-3">Invoice</th>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Kasir</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-[#F9FAFB]/75 transition">
                            <td class="px-4 py-3 font-mono font-bold text-[#5300B7]">{{ $trx->invoice_number }}</td>
                            <td class="px-4 py-3 text-[#6B7280]">{{ $trx->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-[#111827] font-medium">{{ $trx->user?->name ?? 'Kasir' }}</td>
                            <td class="px-4 py-3 text-[#6B7280]">{{ $trx->customer_name ?: 'Umum' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-[#F3F4F6] text-[#374151]">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-[#111827]">
                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('kasira.penjualan.show', $trx) }}"
                                   class="px-2.5 py-1 rounded-lg bg-[#F3F4F6] hover:bg-[#E5E7EB] text-[#5300B7] text-[11px] font-bold transition">
                                    Detail / Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#9CA3AF]">
                                Tidak ada transaksi yang ditemukan pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-[#F3F4F6]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
