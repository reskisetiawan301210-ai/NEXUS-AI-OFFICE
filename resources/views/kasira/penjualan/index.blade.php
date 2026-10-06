@extends('layouts.kasira')

@section('title', 'Riwayat Penjualan')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Riwayat Penjualan & Transaksi</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Daftar rekaman transaksi penjualan, metode bayar, dan cetak invoice toko.</p>
        </div>

        <a href="{{ route('kasira.kasir') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-sm shadow-[#5300B7]/25 transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Transaksi Baru</span>
        </a>
    </div>

    <!-- Summary Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <span class="text-xs font-semibold text-[#6B7280]">Total Penjualan Keseluruhan</span>
            <div class="text-xl font-bold text-[#111827] mt-1">Rp {{ number_format($allTimeSales, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <span class="text-xs font-semibold text-[#6B7280]">Total Transaksi Selesai</span>
            <div class="text-xl font-bold text-[#5300B7] mt-1">{{ number_format($allTimeCount, 0, ',', '.') }} transaksi</div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('kasira.penjualan') }}" class="flex-1 flex flex-col sm:flex-row items-center gap-2.5 w-full">
            <div class="relative flex-1 max-w-md w-full">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor invoice #TRX-..."
                       class="w-full pl-9 pr-4 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none">
                <svg class="w-4 h-4 text-[#9CA3AF] absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select name="method" onchange="this.form.submit()"
                    class="px-3 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] focus:border-[#5300B7] outline-none w-full sm:w-auto">
                <option value="">Semua Metode</option>
                <option value="cash" {{ request('method') === 'cash' ? 'selected' : '' }}>Tunai</option>
                <option value="qris" {{ request('method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="transfer" {{ request('method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </form>

        @if(request('q') || request('method'))
            <a href="{{ route('kasira.penjualan') }}" class="text-xs text-[#5300B7] font-semibold hover:underline">Reset Filter</a>
        @endif
    </div>

    <!-- Transactions List Table -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-[#E5E7EB] bg-[#F9FAFB] text-[#6B7280] font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Invoice</th>
                        <th class="py-3 px-4">Tanggal & Waktu</th>
                        <th class="py-3 px-4">Item Terjual</th>
                        <th class="py-3 px-4">Metode Bayar</th>
                        <th class="py-3 px-4 text-right">Total Belanja</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-[#F9FAFB]/70 transition">
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-[#111827]">{{ $trx->invoice_number }}</span>
                            </td>
                            <td class="py-3 px-4 text-[#6B7280]">
                                {{ $trx->created_at->translatedFormat('d F Y, H:i') }}
                            </td>
                            <td class="py-3 px-4 text-[#4B5563]">
                                {{ $trx->items->count() }} macam ({{ $trx->items->sum('quantity') }} pcs)
                            </td>
                            <td class="py-3 px-4">
                                <span class="uppercase font-semibold text-[10px] px-2 py-0.5 rounded bg-[#F3F4F6] text-[#374151]">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-[#111827]">
                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Selesai
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('kasira.penjualan.show', $trx) }}"
                                   class="text-[#5300B7] font-bold hover:underline inline-flex items-center gap-0.5">
                                    <span>Lihat</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#9CA3AF]">
                                <svg class="w-10 h-10 mx-auto mb-2 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-semibold text-[#111827]">Belum ada data transaksi</p>
                                <p class="text-xs text-[#6B7280] mt-1">Transaksi yang diproses di kasir akan tercatat di sini secara otomatis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

