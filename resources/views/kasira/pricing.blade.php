@extends('layouts.kasira')

@section('title', 'Paket & Langganan')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="text-xs font-bold uppercase tracking-wider text-[#5300B7] bg-[#5300B7]/10 px-3 py-1 rounded-full inline-block">
            Paket Bisnis Kasira
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">
            Pilih Paket Terbaik untuk Pertumbuhan Bisnis Anda
        </h1>
        <p class="text-sm text-[#6B7280]">
            Tingkatkan omzet dan efisiensi operasional toko Anda dengan fitur kasir modern, laporan laba rugi real-time, dan pembayaran QRIS otomatis.
        </p>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
        <!-- 1. Paket Free -->
        <div class="bg-white rounded-3xl p-6 border {{ $business->isFree() ? 'border-[#5300B7] ring-2 ring-[#5300B7]/20 shadow-md' : 'border-[#E5E7EB] shadow-xs' }} flex flex-col justify-between relative">
            @if($business->isFree())
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#5300B7] text-white text-[11px] font-bold px-3 py-0.5 rounded-full shadow-sm">
                    Paket Anda Saat Ini
                </div>
            @endif

            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-[#111827]">Free</h3>
                        <p class="text-xs text-[#6B7280] mt-0.5">Cocok untuk toko baru mulai</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-sm">
                        Rp0
                    </div>
                </div>

                <div class="my-6">
                    <span class="text-3xl font-extrabold text-[#111827]">Gratis</span>
                    <span class="text-xs text-[#6B7280]">/ selamanya</span>
                </div>

                <div class="space-y-3 text-xs text-[#374151] pt-4 border-t border-[#F3F4F6]">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Maksimal 50 Produk</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Kasir POS & Transaksi Tunai</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Struk Digital & Cetak Nota</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-gray-400">
                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="line-through">Pembayaran QRIS Otomatis</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-gray-400">
                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="line-through">Pencatatan Hutang / Piutang</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-gray-400">
                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="line-through">Manajemen Shift Kasir</span>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                @if($business->isFree())
                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-xl bg-gray-100 text-gray-400 text-xs font-bold cursor-not-allowed">
                        Paket Aktif
                    </button>
                @else
                    <form action="{{ route('kasira.pricing.select') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="free">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold transition">
                            Pilih Paket Free
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- 2. Paket Starter (Rp 8.000 / bln) -->
        <div class="bg-white rounded-3xl p-6 border {{ $business->isStarter() ? 'border-[#5300B7] ring-2 ring-[#5300B7]/20 shadow-md' : 'border-[#E5E7EB] shadow-xs' }} flex flex-col justify-between relative">
            @if($business->isStarter())
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#5300B7] text-white text-[11px] font-bold px-3 py-0.5 rounded-full shadow-sm">
                    Paket Anda Saat Ini
                </div>
            @endif

            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-[#111827]">Starter</h3>
                        <p class="text-xs text-[#6B7280] mt-0.5">Usaha UMKM berkembang</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-[#5300B7] flex items-center justify-center font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>

                <div class="my-6">
                    <span class="text-3xl font-extrabold text-[#111827]">Rp 8.000</span>
                    <span class="text-xs text-[#6B7280]">/ bulan</span>
                </div>

                <div class="space-y-3 text-xs text-[#374151] pt-4 border-t border-[#F3F4F6]">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Hingga <strong>500 Produk</strong></span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Manajemen Shift Kasir & Rekonsiliasi Kas</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Pencatatan Piutang / Hutang Pelanggan</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Laporan Laba Kotor & HPP Otomatis</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Manajemen Supplier</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-gray-400">
                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="line-through">Pembayaran QRIS Dinamis</span>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                @if($business->isStarter())
                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-xl bg-purple-50 text-[#5300B7] text-xs font-bold cursor-not-allowed">
                        Paket Aktif
                    </button>
                @else
                    <form action="{{ route('kasira.pricing.select') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="starter">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-sm">
                            Aktifkan Starter (Rp 8.000)
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- 3. Paket Pro (Rp 18.000 / bln) - BEST VALUE -->
        <div class="bg-gradient-to-b from-[#1C2033] to-[#0E131F] text-white rounded-3xl p-6 border {{ $business->isPro() ? 'border-amber-400 ring-2 ring-amber-400/30' : 'border-amber-500/50' }} shadow-xl flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-3 right-6 bg-gradient-to-r from-amber-400 to-amber-500 text-black text-[10px] font-black uppercase tracking-wider px-3 py-0.5 rounded-full shadow-md">
                ⭐ BEST VALUE &bull; QRIS LENGKAP
            </div>

            @if($business->isPro())
                <div class="absolute -top-3 left-6 bg-emerald-500 text-white text-[10px] font-bold px-3 py-0.5 rounded-full shadow-sm">
                    Paket Aktif Anda
                </div>
            @endif

            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span>Pro</span>
                            <span class="text-amber-400 text-xs">👑</span>
                        </h3>
                        <p class="text-xs text-gray-300 mt-0.5">Solusi bisnis modern tanpa batas</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-amber-400/20 text-amber-300 flex items-center justify-center font-bold text-sm">
                        PRO
                    </div>
                </div>

                <div class="my-6">
                    <span class="text-3xl font-extrabold text-white">Rp 18.000</span>
                    <span class="text-xs text-gray-300">/ bulan</span>
                </div>

                <div class="space-y-3 text-xs text-gray-200 pt-4 border-t border-gray-700">
                    <div class="flex items-center gap-2.5 text-amber-300 font-semibold">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Pembayaran QRIS Dinamis Midtrans (Real-time)</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span><strong>Produk Tanpa Batas (Unlimited)</strong></span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Manajemen Shift Kasir & Rekonsiliasi Kas</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Piutang & Cicilan Pelanggan Lengkap</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Laba Kotor & Analitik Penjualan Lanjutan</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Prioritas Layanan CS & Integrasi WhatsApp</span>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                @if($business->isPro())
                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-xl bg-amber-400/20 text-amber-300 text-xs font-bold cursor-not-allowed border border-amber-400/40">
                        Paket Pro Aktif
                    </button>
                @else
                    <form action="{{ route('kasira.pricing.select') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="pro">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-400 hover:bg-amber-300 text-black text-xs font-extrabold transition shadow-lg shadow-amber-500/20">
                            Upgrade ke Pro Sekarang (Rp 18.000)
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
