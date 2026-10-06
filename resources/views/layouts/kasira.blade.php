<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F8F9FC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.seo')
    @include('partials.schema')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-[#111827] bg-[#F8F9FC] selection:bg-[#5300B7]/10 selection:text-[#5300B7]">
@php
    $currentBusiness = auth()->user()?->currentBusiness();
    $businessName = $currentBusiness?->name ?? 'Bisnis Saya';
    $businessType = $currentBusiness?->business_type ?? 'Warung';
    $lowStockCount = $currentBusiness ? $currentBusiness->products()->whereColumn('stock', '<=', 'min_stock')->count() : 0;
    $planName = $currentBusiness?->planName() ?? 'Free';
@endphp

<div class="min-h-full flex flex-col lg:flex-row">
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0E131F] text-white shrink-0 border-r border-[#1F2937] z-30 sticky top-0 h-screen overflow-y-auto select-none">
        <!-- Logo Header -->
        <div class="p-5 flex items-center gap-3 border-b border-[#1A2233]">
            <div class="w-10 h-10 rounded-xl bg-[#5300B7] flex items-center justify-center text-white shadow-md shadow-[#5300B7]/30 shrink-0 overflow-hidden">
                @if($currentBusiness?->logo_path)
                    <img src="{{ asset('storage/' . $currentBusiness->logo_path) }}" alt="{{ $currentBusiness->name }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-bold tracking-tight text-white block leading-tight">Kasira</span>
                    <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded {{ $currentBusiness?->isPro() ? 'bg-amber-400 text-black' : ($currentBusiness?->isStarter() ? 'bg-[#5300B7] text-white' : 'bg-[#1F2937] text-gray-400') }}">
                        {{ $planName }}
                    </span>
                </div>
                <span class="text-[11px] text-[#9CA3AF] font-medium block">Kelola Bisnis, Lebih Mudah</span>
            </div>
        </div>


        <!-- Navigation Menu -->
        <div class="flex-1 px-3 py-4 space-y-6">
            <!-- MAIN MENU -->
            <div>
                <div class="px-3 text-[10px] font-bold uppercase tracking-wider text-[#6B7280] mb-2">Main Menu</div>
                <nav class="space-y-1">
                    <a href="{{ route('kasira.dashboard') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.dashboard') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.dashboard') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    <a href="{{ route('kasira.kasir') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.kasir') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.kasir') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span>Kasir POS</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[10px] rounded bg-[#1F2937] text-[#9CA3AF] font-mono">F1</span>
                    </a>

                    <a href="{{ route('kasira.shift.index') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.shift.*') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.shift.*') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Shift Kasir</span>
                        </div>
                        @if($currentBusiness?->activeShift())
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" title="Shift sedang aktif"></span>
                        @endif
                    </a>

                    <a href="{{ route('kasira.barcode') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.barcode') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.barcode') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span>Scan Barcode</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[10px] rounded bg-[#1F2937] text-[#9CA3AF] font-mono">F2</span>
                    </a>

                    <a href="{{ route('kasira.produk.index') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.produk.*') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.produk.*') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Produk</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[10px] rounded bg-[#1F2937] text-[#9CA3AF] font-mono">F3</span>
                    </a>

                    <a href="{{ route('kasira.stok') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.stok') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.stok') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Stok</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if($lowStockCount > 0)
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            @endif
                            <span class="px-1.5 py-0.5 text-[10px] rounded bg-[#1F2937] text-[#9CA3AF] font-mono">F4</span>
                        </div>
                    </a>

                    <a href="{{ route('kasira.piutang.index') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.piutang.*') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.piutang.*') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Piutang / Hutang</span>
                        </div>
                    </a>

                    <a href="{{ route('kasira.supplier.index') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.supplier.*') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.supplier.*') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Supplier</span>
                        </div>
                    </a>

                    <a href="{{ route('kasira.penjualan') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.penjualan*') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.penjualan*') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Riwayat Transaksi</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[10px] rounded bg-[#1F2937] text-[#9CA3AF] font-mono">F5</span>
                    </a>

                    <a href="{{ route('kasira.pelanggan') }}"
                       class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.pelanggan') ? 'bg-[#5300B7] text-white shadow-sm shadow-[#5300B7]/40' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.pelanggan') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Pelanggan</span>
                        </div>
                        <span class="px-1.5 py-0.5 text-[10px] rounded bg-[#1F2937] text-[#9CA3AF] font-mono">F6</span>
                    </a>
                </nav>
            </div>

            <!-- ANALITIK & PENGATURAN -->
            <div>
                <div class="px-3 text-[10px] font-bold uppercase tracking-wider text-[#6B7280] mb-2">Laporan & Pengaturan</div>
                <nav class="space-y-1">
                    <a href="{{ route('kasira.laporan') }}"
                       class="group flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.laporan') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[#9CA3AF] group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Laporan Penjualan</span>
                        </div>
                    </a>

                    <a href="{{ route('kasira.statistik') }}"
                       class="group flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.statistik') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-[#9CA3AF] group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                            <span>Statistik & Grafik</span>
                        </div>
                    </a>

                    <a href="{{ route('kasira.pricing') }}"
                       class="group flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.pricing') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span>Paket Langganan</span>
                        </div>
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-400/20 text-amber-300">PRO</span>
                    </a>

                    <a href="{{ route('kasira.pengaturan') }}"
                       class="group flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('kasira.pengaturan') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('kasira.pengaturan') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pengaturan</span>
                        </div>
                    </a>

                    <a href="{{ route('about') }}"
                       class="group flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition {{ request()->routeIs('about') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white hover:bg-[#161D2E]' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 {{ request()->routeIs('about') ? 'text-white' : 'text-[#9CA3AF] group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Tentang Developer</span>
                        </div>
                    </a>
                </nav>
            </div>

            <!-- SISTEM / LOGOUT -->
            <div>
                <nav class="space-y-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full group flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-[#EF4444] hover:bg-red-950/30 transition text-left">
                            <svg class="w-4 h-4 text-[#EF4444]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Upgrade Pro / Tier Badge Card -->
        <div class="p-3 mt-auto">
            <div class="rounded-2xl p-3.5 bg-gradient-to-b from-[#1C2033] to-[#121626] border {{ $currentBusiness?->isPro() ? 'border-amber-400/50 shadow-sm shadow-amber-500/20' : 'border-[#2B354C]' }} relative overflow-hidden">
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="{{ $currentBusiness?->isPro() ? 'text-amber-400' : 'text-[#A78BFA]' }}">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5m14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                            </svg>
                        </span>
                        <span class="text-xs font-bold text-white">Kasira {{ $planName }}</span>
                    </div>
                </div>
                <p class="text-[10px] text-[#9CA3AF] leading-relaxed mb-2.5">
                    @if($currentBusiness?->isPro())
                        Fitur bisnis terlengkap & QRIS dinamis aktif.
                    @elseif($currentBusiness?->isStarter())
                        Mulai dari Rp 8.000/bln. Upgrade ke Pro untuk QRIS.
                    @else
                        Upgrade mulai Rp 8rb/bln untuk fitur tanpa batas.
                    @endif
                </p>
                <a href="{{ route('kasira.pricing') }}" class="inline-flex items-center justify-center w-full py-1.5 px-3 rounded-lg bg-[#5300B7] hover:bg-[#430095] text-white text-[11px] font-semibold transition shadow-xs">
                    {{ $currentBusiness?->isPro() ? 'Kelola Paket' : 'Pilih Paket &rsaquo;' }}
                </a>
            </div>
        </div>
    </aside>

    <!-- Mobile Drawer -->
    <div id="mobile-sidebar-drawer" class="fixed inset-0 z-50 lg:hidden hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" onclick="toggleMobileSidebar()"></div>
        <div class="fixed inset-y-0 left-0 w-72 bg-[#0E131F] text-white p-5 flex flex-col justify-between overflow-y-auto z-10 shadow-2xl">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-[#1A2233]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#5300B7] flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-base font-bold text-white block">Kasira</span>
                            <span class="text-[10px] text-[#9CA3AF]">Kelola Bisnis, Lebih Mudah</span>
                        </div>
                    </div>
                    <button type="button" onclick="toggleMobileSidebar()" class="text-[#9CA3AF] hover:text-white p-1">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="mt-4 space-y-1 text-sm font-medium">
                    <a href="{{ route('kasira.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.dashboard') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('kasira.kasir') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.kasir') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Kasir POS</span>
                        <span class="text-xs font-mono bg-[#1F2937] px-1.5 py-0.5 rounded text-[#9CA3AF]">F1</span>
                    </a>
                    <a href="{{ route('kasira.shift.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.shift.*') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Shift Kasir</span>
                        @if($currentBusiness?->activeShift())
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold">BUKA</span>
                        @endif
                    </a>
                    <a href="{{ route('kasira.barcode') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.barcode') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Scan Barcode</span>
                        <span class="text-xs font-mono bg-[#1F2937] px-1.5 py-0.5 rounded text-[#9CA3AF]">F2</span>
                    </a>
                    <a href="{{ route('kasira.produk.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.produk.*') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Produk</span>
                        <span class="text-xs font-mono bg-[#1F2937] px-1.5 py-0.5 rounded text-[#9CA3AF]">F3</span>
                    </a>
                    <a href="{{ route('kasira.stok') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.stok') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Stok</span>
                        <span class="text-xs font-mono bg-[#1F2937] px-1.5 py-0.5 rounded text-[#9CA3AF]">F4</span>
                    </a>
                    <a href="{{ route('kasira.piutang.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.piutang.*') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Piutang Pelanggan</span>
                    </a>
                    <a href="{{ route('kasira.supplier.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.supplier.*') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Supplier</span>
                    </a>
                    <a href="{{ route('kasira.penjualan') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.penjualan*') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Penjualan</span>
                        <span class="text-xs font-mono bg-[#1F2937] px-1.5 py-0.5 rounded text-[#9CA3AF]">F5</span>
                    </a>
                    <a href="{{ route('kasira.pelanggan') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.pelanggan') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Pelanggan</span>
                        <span class="text-xs font-mono bg-[#1F2937] px-1.5 py-0.5 rounded text-[#9CA3AF]">F6</span>
                    </a>
                    <a href="{{ route('kasira.pricing') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.pricing') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Paket Langganan</span>
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-400 text-black">PRO</span>
                    </a>
                    <a href="{{ route('kasira.pengaturan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('kasira.pengaturan') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('about') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('about') ? 'bg-[#5300B7] text-white' : 'text-[#9CA3AF] hover:text-white' }}">
                        <span>Tentang Developer</span>
                    </a>
                </nav>
            </div>

            <div class="pt-4 border-t border-[#1A2233] space-y-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-400 hover:bg-red-950/30">
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-[#E5E7EB] sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between gap-3">
            <!-- Left: Mobile Menu Toggle & Store Identity -->
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl text-[#6B7280] hover:text-[#111827] hover:bg-[#F3F4F6]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Store Badge -->
                <div class="flex items-center gap-2.5 bg-[#F9FAFB] border border-[#E5E7EB] px-3 py-1.5 rounded-xl shrink-0">
                    <div class="w-6 h-6 rounded-lg bg-[#5300B7]/10 text-[#5300B7] flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-[#111827] truncate max-w-[130px] sm:max-w-[160px]">{{ $businessName }}</span>
                    <span class="text-[10px] font-semibold text-[#5300B7] bg-[#5300B7]/10 px-1.5 py-0.5 rounded hidden sm:inline">{{ $businessType }}</span>
                </div>
            </div>

            <!-- Center: Real-Time Digital Clock (WIB) -->
            <div class="hidden md:flex items-center gap-3 px-3.5 py-1.5 rounded-xl bg-[#F9FAFB] border border-[#E5E7EB]">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-[#111827] font-mono tabular-nums tracking-wide realtime-clock-time">--:--:-- WIB</span>
                </div>
                <div class="w-px h-4 bg-[#E5E7EB]"></div>
                <span class="text-xs font-medium text-[#6B7280] realtime-clock-date">--</span>
            </div>

            <!-- Right: Search Bar & Actions & Profile -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <!-- Fast Global Search -->
                <div class="hidden xl:block w-48">
                    <form action="{{ route('kasira.produk.index') }}" method="GET" class="relative">
                        <input type="text" name="q" id="global-search-input" placeholder="Cari..."
                               value="{{ request('q') }}"
                               class="w-full pl-7 pr-3 py-1.5 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:bg-white focus:border-[#5300B7] outline-none">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-[#9CA3AF]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>
                </div>

                <!-- Scan Barcode Quick Button -->
                <a href="{{ route('kasira.barcode') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#E5E7EB] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#111827] transition shadow-2xs">
                    <svg class="w-4 h-4 text-[#5300B7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span class="hidden sm:inline">Scan</span>
                </a>

                <!-- User Profile -->
                <div class="flex items-center gap-2.5 pl-2 sm:border-l sm:border-[#E5E7EB]">
                    <div class="w-8 h-8 rounded-full bg-[#5300B7] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="text-xs font-bold text-[#111827] block leading-tight truncate max-w-[100px]">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-[#6B7280] font-medium block">Kasira</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">&times;</button>
            </div>
        @endif

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-6 min-w-0">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleMobileSidebar() {
        const drawer = document.getElementById('mobile-sidebar-drawer');
        if (drawer) {
            drawer.classList.toggle('hidden');
        }
    }

    // Real-Time Digital Clock in WIB (Asia/Jakarta)
    function updateRealTimeClock() {
        const now = new Date();
        const dateOptions = {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const timeOptions = {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        const dateStr = new Intl.DateTimeFormat('id-ID', dateOptions).format(now);
        const timeStr = new Intl.DateTimeFormat('id-ID', timeOptions).format(now).replace(/\./g, ':') + ' WIB';

        document.querySelectorAll('.realtime-clock-date').forEach(el => el.textContent = dateStr);
        document.querySelectorAll('.realtime-clock-time').forEach(el => el.textContent = timeStr);
    }
    updateRealTimeClock();
    setInterval(updateRealTimeClock, 1000);

    // Keyboard Shortcuts (F1-F6, Ctrl+K)
    window.addEventListener('keydown', function(e) {
        if (e.ctrlKey && (e.key === 'k' || e.key === 'K')) {
            e.preventDefault();
            const searchInput = document.getElementById('global-search-input');
            if (searchInput) searchInput.focus();
        } else if (e.key === 'F1') {
            e.preventDefault();
            window.location.href = "{{ route('kasira.kasir') }}";
        } else if (e.key === 'F2') {
            e.preventDefault();
            window.location.href = "{{ route('kasira.barcode') }}";
        } else if (e.key === 'F3') {
            e.preventDefault();
            window.location.href = "{{ route('kasira.produk.index') }}";
        } else if (e.key === 'F4') {
            e.preventDefault();
            window.location.href = "{{ route('kasira.stok') }}";
        } else if (e.key === 'F5') {
            e.preventDefault();
            window.location.href = "{{ route('kasira.penjualan') }}";
        } else if (e.key === 'F6') {
            e.preventDefault();
            window.location.href = "{{ route('kasira.pelanggan') }}";
        }
    });
</script>

@stack('scripts')
</body>
</html>

