@extends('layouts.kasira')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header & Live Badges -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">
                Selamat datang, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5 font-medium">
                Berikut ringkasan bisnis Anda hari ini. Tetap semangat!
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 text-xs font-semibold text-[#374151]">
            <!-- Dynamic Date -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-[#E5E7EB] shadow-2xs">
                <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="realtime-clock-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>

            <!-- Dynamic Time Clock -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-[#E5E7EB] shadow-2xs">
                <svg class="w-3.5 h-3.5 text-[#5300B7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="realtime-clock-time font-mono tabular-nums text-[#5300B7] font-bold">{{ \Carbon\Carbon::now()->format('H:i:s') }} WIB</span>
            </div>

            <!-- Online Status -->
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Online</span>
            </div>
        </div>
    </div>

    <!-- Active Shift Notice Banner -->
    @if($activeShift)
        <div class="p-4 rounded-2xl bg-gradient-to-r from-[#161D2E] to-[#0E131F] border border-[#2B354C] text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-white">Shift Kasir Sedang Aktif</span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-bold">BUKA</span>
                    </div>
                    <p class="text-[11px] text-[#9CA3AF] mt-0.5">
                        Dibuka pukul {{ $activeShift->opened_at->format('H:i') }} WIB &bull; Kas Awal: <strong class="text-white">Rp {{ number_format($activeShift->initial_cash, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('kasira.kasir') }}" class="px-3 py-1.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-semibold transition shadow-2xs">
                    Buka Kasir POS
                </a>
                <a href="{{ route('kasira.shift.index') }}" class="px-3 py-1.5 rounded-xl bg-[#1F2937] hover:bg-[#374151] text-gray-200 text-xs font-semibold transition border border-gray-700">
                    Kelola Shift
                </a>
            </div>
        </div>
    @else
        <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
            <div class="flex items-center gap-2.5 text-xs font-medium">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Shift kasir belum dibuka untuk sesi ini. Buka shift untuk merekonsiliasi uang kas di laci kasir.</span>
            </div>
            <a href="{{ route('kasira.shift.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shrink-0 transition">
                Buka Shift Kasir &rsaquo;
            </a>
        </div>
    @endif

    <!-- 5 Key Metric Cards (Including Laba Kotor) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        <!-- Metric 1: Total Omzet -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-[#E5E7EB] shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-[#5300B7] text-white flex items-center justify-center shadow-md shadow-[#5300B7]/25">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-lg border border-purple-100">
                    Omzet
                </span>
            </div>
            <div class="mt-3">
                <span class="text-xs font-semibold text-[#6B7280] block">Total Omzet</span>
                <span class="text-xl font-bold text-[#111827] tracking-tight block mt-0.5 truncate">
                    Rp {{ number_format($todayOmzet, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Penjualan hari ini</span>
            </div>
            <div class="absolute right-0 bottom-0 w-20 h-8 opacity-30 pointer-events-none">
                <svg viewBox="0 0 100 40" class="w-full h-full" preserveAspectRatio="none">
                    <path d="M0,35 Q25,30 40,20 T70,18 T100,5" fill="none" stroke="#5300B7" stroke-width="3" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <!-- Metric 2: Laba Kotor (Real Profit) -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-emerald-200 shadow-xs relative overflow-hidden flex flex-col justify-between ring-1 ring-emerald-500/10">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-600/25">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                    Laba Bersih
                </span>
            </div>
            <div class="mt-3">
                <span class="text-xs font-semibold text-emerald-800 block">Laba Kotor</span>
                <span class="text-xl font-bold text-emerald-700 tracking-tight block mt-0.5 truncate">
                    Rp {{ number_format($todayGrossProfit, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Omzet dikurangi Modal</span>
            </div>
            <div class="absolute right-0 bottom-0 w-20 h-8 opacity-30 pointer-events-none">
                <svg viewBox="0 0 100 40" class="w-full h-full" preserveAspectRatio="none">
                    <path d="M0,38 Q30,25 50,15 T80,10 T100,4" fill="none" stroke="#059669" stroke-width="3" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <!-- Metric 3: Jumlah Transaksi -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-[#E5E7EB] shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-[#2563EB] text-white flex items-center justify-center shadow-md shadow-[#2563EB]/25">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
                    Order
                </span>
            </div>
            <div class="mt-3">
                <span class="text-xs font-semibold text-[#6B7280] block">Total Transaksi</span>
                <span class="text-xl font-bold text-[#111827] tracking-tight block mt-0.5">
                    {{ $todayTransactionsCount }}
                </span>
                <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Struk dicetak hari ini</span>
            </div>
            <div class="absolute right-0 bottom-0 w-20 h-8 opacity-30 pointer-events-none">
                <svg viewBox="0 0 100 40" class="w-full h-full" preserveAspectRatio="none">
                    <path d="M0,38 Q30,32 50,15 T80,18 T100,8" fill="none" stroke="#2563EB" stroke-width="3" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <!-- Metric 4: Produk Terjual -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-[#E5E7EB] shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/25">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="inline-flex items-center gap-0.5 text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                    Item
                </span>
            </div>
            <div class="mt-3">
                <span class="text-xs font-semibold text-[#6B7280] block">Produk Terjual</span>
                <span class="text-xl font-bold text-[#111827] tracking-tight block mt-0.5">
                    {{ $todayProductsSold }} pcs
                </span>
                <span class="text-[11px] text-[#9CA3AF] mt-0.5 block">Kuantitas keluar</span>
            </div>
            <div class="absolute right-0 bottom-0 w-20 h-8 opacity-30 pointer-events-none">
                <svg viewBox="0 0 100 40" class="w-full h-full" preserveAspectRatio="none">
                    <path d="M0,32 Q25,28 45,18 T75,12 T100,5" fill="none" stroke="#4F46E5" stroke-width="3" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <!-- Metric 5: Stok Menipis -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-[#E5E7EB] shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl bg-[#D97706] text-white flex items-center justify-center shadow-md shadow-[#D97706]/25">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                @if($lowStockProducts > 0)
                    <span class="inline-flex items-center text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
                        Perlu Restock
                    </span>
                @else
                    <span class="inline-flex items-center text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">
                        Stok Aman
                    </span>
                @endif
            </div>
            <div class="mt-3">
                <span class="text-xs font-semibold text-[#6B7280] block">Stok Menipis</span>
                <span class="text-xl font-bold text-[#111827] tracking-tight block mt-0.5">
                    {{ $lowStockProducts }} produk
                </span>
                <span class="text-[11px] {{ $lowStockProducts > 0 ? 'text-amber-600 font-medium' : 'text-[#9CA3AF]' }} mt-0.5 block">
                    {{ $lowStockProducts > 0 ? 'Perlu order supplier' : 'Semua stok mencukupi' }}
                </span>
            </div>
            <div class="absolute right-0 bottom-0 w-20 h-8 opacity-30 pointer-events-none">
                <svg viewBox="0 0 100 40" class="w-full h-full" preserveAspectRatio="none">
                    <path d="M0,35 Q20,30 40,25 T70,30 T100,10" fill="none" stroke="#D97706" stroke-width="3" stroke-linecap="round" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Middle Row: Sales Chart, Best Selling Products, Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Sales Chart (6 cols) -->
        <div class="lg:col-span-6 bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#F3F4F6]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#5300B7]/10 text-[#5300B7] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-[#111827]">Grafik Penjualan</h2>
                        <p class="text-[11px] text-[#6B7280]">Performa penjualan dalam {{ $chartDays }} hari terakhir</p>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 bg-[#F9FAFB] border border-[#E5E7EB] p-1 rounded-xl">
                    <a href="{{ request()->fullUrlWithQuery(['days' => 7]) }}" class="px-2.5 py-1 text-[11px] font-semibold rounded-lg transition {{ $chartDays == 7 ? 'bg-[#5300B7] text-white shadow-2xs' : 'text-[#6B7280] hover:text-[#111827]' }}">7 Hari</a>
                    <a href="{{ request()->fullUrlWithQuery(['days' => 30]) }}" class="px-2.5 py-1 text-[11px] font-semibold rounded-lg transition {{ $chartDays == 30 ? 'bg-[#5300B7] text-white shadow-2xs' : 'text-[#6B7280] hover:text-[#111827]' }}">30 Hari</a>
                    <a href="{{ request()->fullUrlWithQuery(['days' => 90]) }}" class="px-2.5 py-1 text-[11px] font-semibold rounded-lg transition {{ $chartDays == 90 ? 'bg-[#5300B7] text-white shadow-2xs' : 'text-[#6B7280] hover:text-[#111827]' }}">90 Hari</a>
                </div>
            </div>

            <!-- Chart Canvas / SVG Container -->
            <div class="mt-4 relative min-h-[220px] flex items-end">
                @php
                    $maxVal = max(max($salesChartData), 100000);
                    $chartPoints = [];
                    $width = 500;
                    $height = 180;
                    $step = count($salesChartData) > 1 ? $width / (count($salesChartData) - 1) : $width;

                    foreach ($salesChartData as $idx => $val) {
                        $x = $idx * $step;
                        $y = $height - (($val / $maxVal) * ($height - 30)) - 15;
                        $chartPoints[] = ['x' => $x, 'y' => $y, 'val' => $val, 'label' => $salesChartLabels[$idx]];
                    }

                    $polyPoints = "0,{$height} ";
                    $pathD = "";
                    foreach ($chartPoints as $i => $pt) {
                        if ($i === 0) {
                            $pathD .= "M {$pt['x']},{$pt['y']}";
                        } else {
                            $prev = $chartPoints[$i - 1];
                            $cx1 = $prev['x'] + ($pt['x'] - $prev['x']) / 2;
                            $cy1 = $prev['y'];
                            $cx2 = $prev['x'] + ($pt['x'] - $prev['x']) / 2;
                            $cy2 = $pt['y'];
                            $pathD .= " C {$cx1},{$cy1} {$cx2},{$cy2} {$pt['x']},{$pt['y']}";
                        }
                        $polyPoints .= "{$pt['x']},{$pt['y']} ";
                    }
                    $polyPoints .= "{$width},{$height}";
                @endphp

                <svg viewBox="0 0 500 200" class="w-full h-48 overflow-visible" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="purpleGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#5300B7" stop-opacity="0.25" />
                            <stop offset="100%" stop-color="#5300B7" stop-opacity="0.0" />
                        </linearGradient>
                    </defs>

                    <!-- Horizontal Grid Lines -->
                    <line x1="0" y1="30" x2="500" y2="30" stroke="#F3F4F6" stroke-width="1" stroke-dasharray="4" />
                    <line x1="0" y1="85" x2="500" y2="85" stroke="#F3F4F6" stroke-width="1" stroke-dasharray="4" />
                    <line x1="0" y1="140" x2="500" y2="140" stroke="#F3F4F6" stroke-width="1" stroke-dasharray="4" />
                    <line x1="0" y1="180" x2="500" y2="180" stroke="#E5E7EB" stroke-width="1" />

                    <!-- Area Fill -->
                    <polygon points="{{ $polyPoints }}" fill="url(#purpleGrad)" />

                    <!-- Line Stroke -->
                    <path d="{{ $pathD }}" fill="none" stroke="#5300B7" stroke-width="3" stroke-linecap="round" />

                    <!-- Dots -->
                    @foreach($chartPoints as $pt)
                        <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="4" fill="#FFFFFF" stroke="#5300B7" stroke-width="2.5" class="hover:r-6 transition cursor-pointer" />
                    @endforeach
                </svg>
            </div>

            <!-- X-Axis Labels -->
            <div class="flex justify-between text-[10px] font-medium text-[#9CA3AF] mt-2 pt-2 border-t border-[#F3F4F6]">
                @foreach($salesChartLabels as $lbl)
                    <span>{{ $lbl }}</span>
                @endforeach
            </div>
        </div>

        <!-- Best Selling Products (3 cols) -->
        <div class="lg:col-span-3 bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#F3F4F6]">
                    <div>
                        <h2 class="text-sm font-bold text-[#111827]">Produk Terlaris</h2>
                        <p class="text-[11px] text-[#6B7280]">Berdasarkan jumlah penjualan</p>
                    </div>
                </div>

                <div class="mt-3 space-y-3">
                    @forelse($bestSellingProducts as $index => $item)
                        <div class="flex items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="text-xs font-bold text-[#9CA3AF] w-4 text-center shrink-0">{{ $index + 1 }}</span>
                                <div class="w-10 h-10 rounded-xl bg-[#F3F4F6] border border-[#E5E7EB] overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($item->product && $item->product->image_path)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-[#111827] truncate">{{ $item->product_name }}</h4>
                                    <span class="text-[10px] text-[#6B7280]">Terjual {{ $item->total_qty }}x</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-[#111827] shrink-0">
                                Rp {{ number_format($item->last_price, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-xs text-[#9CA3AF] font-medium">Belum ada data penjualan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 border-t border-[#F3F4F6] mt-3 text-right">
                <a href="{{ route('kasira.produk.index') }}" class="text-[11px] font-bold text-[#5300B7] hover:underline inline-flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Recent Transactions (3 cols) -->
        <div class="lg:col-span-3 bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-[#F3F4F6]">
                    <h2 class="text-sm font-bold text-[#111827]">Transaksi Terbaru</h2>
                    <a href="{{ route('kasira.penjualan') }}" class="text-[11px] font-bold text-[#5300B7] hover:underline inline-flex items-center gap-0.5">
                        <span>Lihat Semua</span>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="mt-3 space-y-3">
                    @forelse($recentTransactions as $trx)
                        <div class="flex items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-[#5300B7]/10 text-[#5300B7] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-[#111827] truncate">{{ $trx->invoice_number }}</h4>
                                    <span class="text-[10px] text-[#6B7280] block">
                                        {{ $trx->created_at->translatedFormat('d M Y · H:i') }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold text-[#111827] block">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </span>
                                <span class="inline-block text-[9px] font-semibold px-1.5 py-0.2 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Selesai
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-xs text-[#9CA3AF] font-medium">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 border-t border-[#F3F4F6] mt-3">
                <a href="{{ route('kasira.kasir') }}" class="w-full py-2 px-3 rounded-xl bg-[#F9FAFB] hover:bg-[#F3F4F6] border border-[#E5E7EB] text-xs font-bold text-[#111827] flex items-center justify-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-[#5300B7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Transaksi Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Popular Products & Quick Actions / Promo -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Popular Products (8 cols) -->
        <div class="lg:col-span-8 bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#F3F4F6]">
                <div>
                    <h2 class="text-sm font-bold text-[#111827]">Produk Populer</h2>
                    <p class="text-[11px] text-[#6B7280]">Produk yang paling banyak dicari dan dibeli</p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" id="dashboard-product-search" placeholder="Cari produk..."
                               class="pl-8 pr-3 py-1.5 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none">
                        <svg class="w-3.5 h-3.5 text-[#9CA3AF] absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <a href="{{ route('kasira.produk.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-semibold shadow-xs transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Produk</span>
                    </a>
                </div>
            </div>

            <!-- Categories Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto py-3 no-scrollbar">
                <a href="{{ route('kasira.dashboard') }}"
                   class="px-3 py-1 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ !request('category') ? 'bg-[#5300B7] text-white shadow-2xs' : 'bg-[#F3F4F6] text-[#6B7280] hover:text-[#111827]' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('kasira.dashboard', ['category' => $cat->slug]) }}"
                       class="px-3 py-1 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('category') === $cat->slug ? 'bg-[#5300B7] text-white shadow-2xs' : 'bg-[#F3F4F6] text-[#6B7280] hover:text-[#111827]' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mt-2">
                @forelse($popularProducts as $prod)
                    <div class="product-item-card bg-[#F9FAFB] hover:bg-white border border-[#E5E7EB] hover:border-[#5300B7]/30 rounded-xl p-3 flex flex-col justify-between transition group shadow-2xs">
                        <div class="w-full aspect-square rounded-lg bg-white border border-[#E5E7EB] overflow-hidden mb-2 relative flex items-center justify-center">
                            @if($prod->image_path)
                                <img src="{{ asset('storage/' . $prod->image_path) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif

                            @if($prod->is_low_stock)
                                <span class="absolute top-1 right-1 px-1.5 py-0.5 rounded bg-red-100 text-red-700 text-[9px] font-bold">Low</span>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-[#111827] truncate" title="{{ $prod->name }}">{{ $prod->name }}</h4>
                            <span class="text-[10px] text-[#6B7280] block truncate">{{ $prod->category?->name ?? 'Umum' }}</span>
                            <span class="text-xs font-bold text-[#111827] block mt-1">
                                Rp {{ number_format($prod->selling_price, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-2 pt-2 border-t border-[#E5E7EB] flex items-center justify-between">
                            <span class="text-[10px] font-medium {{ $prod->stock <= $prod->min_stock ? 'text-red-600 font-bold' : 'text-[#6B7280]' }}">
                                Stok {{ $prod->stock }}
                            </span>
                            <a href="{{ route('kasira.kasir', ['add_product_id' => $prod->id]) }}"
                               class="w-6 h-6 rounded-lg bg-[#5300B7] hover:bg-[#430095] text-white flex items-center justify-center transition shadow-2xs"
                               title="Tambah ke Kasir">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-[#F9FAFB] rounded-xl border border-dashed border-[#E5E7EB]">
                        <svg class="w-10 h-10 text-[#9CA3AF] mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-xs font-bold text-[#111827]">Belum ada produk</p>
                        <p class="text-[11px] text-[#6B7280] mt-0.5">Tambahkan produk pertama Anda untuk mulai berjualan</p>
                        <a href="{{ route('kasira.produk.create') }}" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#5300B7] text-white text-xs font-semibold shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Produk Sekarang</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Aksi Cepat & QRIS Banner (4 cols) -->
        <div class="lg:col-span-4 space-y-5">
            <!-- Aksi Cepat -->
            <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
                <div class="pb-3 border-b border-[#F3F4F6]">
                    <h2 class="text-sm font-bold text-[#111827]">Aksi Cepat</h2>
                    <p class="text-[11px] text-[#6B7280]">Akses fitur penting dengan cepat</p>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4">
                    <!-- Kasir F1 -->
                    <a href="{{ route('kasira.kasir') }}"
                       class="p-3 rounded-xl border border-[#E5E7EB] hover:border-[#5300B7]/40 hover:bg-[#5300B7]/5 transition flex flex-col justify-between group">
                        <div class="w-8 h-8 rounded-lg bg-[#5300B7]/10 text-[#5300B7] flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#111827] block">Kasir</span>
                            <span class="text-[10px] text-[#6B7280] block">Mulai transaksi baru</span>
                        </div>
                        <span class="mt-2 text-[10px] font-mono text-[#9CA3AF] self-start">F1</span>
                    </a>

                    <!-- Scan Barcode F2 -->
                    <a href="{{ route('kasira.barcode') }}"
                       class="p-3 rounded-xl border border-[#E5E7EB] hover:border-[#2563EB]/40 hover:bg-[#2563EB]/5 transition flex flex-col justify-between group">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#111827] block">Scan Barcode</span>
                            <span class="text-[10px] text-[#6B7280] block">Scan via kamera</span>
                        </div>
                        <span class="mt-2 text-[10px] font-mono text-[#9CA3AF] self-start">F2</span>
                    </a>

                    <!-- Tambah Produk F3 -->
                    <a href="{{ route('kasira.produk.create') }}"
                       class="p-3 rounded-xl border border-[#E5E7EB] hover:border-[#059669]/40 hover:bg-[#059669]/5 transition flex flex-col justify-between group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#111827] block">Tambah Produk</span>
                            <span class="text-[10px] text-[#6B7280] block">Daftarkan produk</span>
                        </div>
                        <span class="mt-2 text-[10px] font-mono text-[#9CA3AF] self-start">F3</span>
                    </a>

                    <!-- Lihat Laporan F5 -->
                    <a href="{{ route('kasira.laporan') }}"
                       class="p-3 rounded-xl border border-[#E5E7EB] hover:border-indigo-400 hover:bg-indigo-50/40 transition flex flex-col justify-between group">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-[#111827] block">Lihat Laporan</span>
                            <span class="text-[10px] text-[#6B7280] block">Laporan penjualan</span>
                        </div>
                        <span class="mt-2 text-[10px] font-mono text-[#9CA3AF] self-start">F5</span>
                    </a>
                </div>
            </div>

            <!-- Scan & Bayar QRIS Promo Card -->
            <div class="rounded-2xl p-5 bg-gradient-to-br from-[#1E0B36] via-[#3B0764] to-[#5300B7] text-white shadow-md relative overflow-hidden flex flex-col justify-between min-h-[170px]">
                <div class="relative z-10">
                    <span class="inline-block px-2 py-0.5 rounded-full bg-white/20 text-[10px] font-bold uppercase tracking-wider mb-2">Kasira Pay</span>
                    <h3 class="text-sm font-bold text-white leading-snug">Scan & Bayar Lebih Mudah</h3>
                    <p class="text-[11px] text-purple-200 mt-1 max-w-[210px] leading-relaxed">
                        Terima pembayaran QRIS, e-wallet, dan metode lainnya untuk toko Anda.
                    </p>
                </div>

                <div class="relative z-10 mt-4">
                    <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-purple-50 text-[#5300B7] text-xs font-bold transition shadow-xs">
                        <span>Pelajari Sekarang</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- QR Phone Graphic -->
                <div class="absolute -right-3 -bottom-4 w-28 h-36 bg-white/10 rounded-2xl border border-white/20 p-2 transform rotate-12 flex flex-col items-center justify-center opacity-85 pointer-events-none">
                    <div class="w-16 h-16 bg-white rounded-lg p-1.5 shadow-sm">
                        <!-- QR Matrix Mockup -->
                        <div class="w-full h-full bg-black/90 grid grid-cols-4 gap-0.5 p-1 rounded">
                            <div class="bg-white col-span-2 row-span-2"></div>
                            <div class="bg-white"></div>
                            <div class="bg-white"></div>
                            <div class="bg-white"></div>
                            <div class="bg-white col-span-2 row-span-2"></div>
                        </div>
                    </div>
                    <span class="text-[8px] font-bold text-white mt-1">QRIS</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live Clock updater
    function updateLiveClock() {
        const clockEl = document.getElementById('live-clock');
        if (clockEl) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            clockEl.textContent = `${hours}:${minutes} WIB`;
        }
    }
    setInterval(updateLiveClock, 30000);

    // Filter Popular Products on the Fly
    const searchInput = document.getElementById('dashboard-product-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const val = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.product-item-card');
            cards.forEach(card => {
                const title = card.querySelector('h4')?.textContent.toLowerCase() || '';
                card.style.display = title.includes(val) ? '' : 'none';
            });
        });
    }
</script>
@endpush

