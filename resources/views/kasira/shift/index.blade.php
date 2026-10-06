@extends('layouts.kasira')

@section('title', 'Shift Kasir')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Shift Kasir & Rekonsiliasi Kas</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Kelola modal laci, kontrol setoran kas fisik, dan deteksi selisih penjualan kasir.</p>
        </div>

        @if(! $activeShift)
            <button type="button" onclick="document.getElementById('open-shift-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-sm self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buka Shift Baru</span>
            </button>
        @endif
    </div>

    @if($activeShift)
        <!-- Active Shift Live Panel -->
        <div class="bg-white rounded-3xl p-6 border border-[#5300B7]/30 shadow-md ring-1 ring-[#5300B7]/10 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#5300B7] text-white flex items-center justify-center font-bold shadow-md shadow-[#5300B7]/25">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-[#111827]">Shift Kasir Aktif</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Berjalan
                            </span>
                        </div>
                        <p class="text-xs text-[#6B7280] mt-0.5">
                            Kasir: <strong class="text-gray-900">{{ $activeShift->user?->name ?? 'Kasir' }}</strong> &bull;
                            Dibuka: <span class="font-medium text-gray-700">{{ $activeShift->opened_at->format('d M Y, H:i') }} WIB</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('kasira.kasir') }}" class="px-4 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-[#5300B7] text-xs font-bold transition">
                        Buka POS
                    </a>
                    <button type="button" onclick="document.getElementById('close-shift-modal').classList.remove('hidden')"
                            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition shadow-xs">
                        Tutup Shift Ini
                    </button>
                </div>
            </div>

            <!-- Shift Live Counters -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                    <span class="text-[11px] font-semibold text-[#6B7280] block">Kas Awal (Modal Laci)</span>
                    <span class="text-lg font-bold text-[#111827] mt-1 block">
                        Rp {{ number_format($activeShift->initial_cash, 0, ',', '.') }}
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                    <span class="text-[11px] font-semibold text-[#6B7280] block">Total Penjualan Shift</span>
                    <span class="text-lg font-bold text-[#5300B7] mt-1 block">
                        Rp {{ number_format($shiftSummary['total_sales'] ?? 0, 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-[#9CA3AF]">{{ $shiftSummary['transaction_count'] ?? 0 }} transaksi</span>
                </div>

                <div class="p-4 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                    <span class="text-[11px] font-semibold text-[#6B7280] block">Penjualan Tunai</span>
                    <span class="text-lg font-bold text-emerald-600 mt-1 block">
                        Rp {{ number_format($shiftSummary['cash_sales'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                    <span class="text-[11px] font-semibold text-[#6B7280] block">Penjualan QRIS</span>
                    <span class="text-lg font-bold text-blue-600 mt-1 block">
                        Rp {{ number_format($shiftSummary['qris_sales'] ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2.5 text-xs text-amber-950 font-medium">
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Uang Tunai yang Seharusnya Ada di Laci Kasir: <strong>Rp {{ number_format($shiftSummary['expected_cash'] ?? $activeShift->initial_cash, 0, ',', '.') }}</strong> (Kas Awal + Tunai Masuk)</span>
                </div>
                <button type="button" onclick="document.getElementById('close-shift-modal').classList.remove('hidden')"
                        class="text-xs font-bold text-amber-800 hover:text-amber-950 underline self-end sm:self-auto">
                    Tutup & Hitung Fisik &rsaquo;
                </button>
            </div>
        </div>
    @else
        <!-- No Active Shift Banner -->
        <div class="bg-white rounded-3xl p-8 border border-[#E5E7EB] text-center space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-[#111827]">Tidak Ada Shift Kasir yang Sedang Berjalan</h3>
            <p class="text-xs text-[#6B7280] max-w-md mx-auto">
                Buka shift kasir sebelum memulai sesi penjualan untuk memastikan modal awal dan uang tunai tercatat dengan tepat.
            </p>
            <button type="button" onclick="document.getElementById('open-shift-modal').classList.remove('hidden')"
                    class="px-5 py-2.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-sm inline-block">
                Buka Shift Kasir Sekarang
            </button>
        </div>
    @endif

    <!-- Shift History Table -->
    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="p-5 border-b border-[#E5E7EB] flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-[#111827]">Riwayat Shift Kasir</h3>
                <p class="text-xs text-[#6B7280]">Rekam jejak kas masuk dan selisih rekonsiliasi laci kasir</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F9FAFB] text-[#6B7280] font-bold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="py-3 px-4">Kasir</th>
                        <th class="py-3 px-4">Waktu Buka / Tutup</th>
                        <th class="py-3 px-4 text-right">Kas Awal</th>
                        <th class="py-3 px-4 text-right">Penjualan Shift</th>
                        <th class="py-3 px-4 text-right">Kas Diharapkan</th>
                        <th class="py-3 px-4 text-right">Kas Fisik</th>
                        <th class="py-3 px-4 text-right">Selisih</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-[#F9FAFB]/50 transition">
                            <td class="py-3.5 px-4 font-semibold text-[#111827]">
                                {{ $shift->user?->name ?? 'Kasir' }}
                                @if($shift->notes)
                                    <span class="block text-[10px] text-gray-500 italic mt-0.5 font-normal">{{ $shift->notes }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[#6B7280]">
                                <div>{{ $shift->opened_at->format('d/m/y H:i') }}</div>
                                <div class="text-[10px] text-gray-400">
                                    {{ $shift->closed_at ? $shift->closed_at->format('d/m/y H:i') : 'Masih Buka' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right font-medium">
                                Rp {{ number_format($shift->initial_cash, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-[#5300B7]">
                                Rp {{ number_format($shift->total_sales, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-gray-600">
                                Rp {{ number_format($shift->expected_cash, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-medium">
                                {{ $shift->actual_cash !== null ? 'Rp ' . number_format($shift->actual_cash, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold">
                                @if($shift->difference === null)
                                    <span class="text-gray-400">-</span>
                                @elseif($shift->difference == 0)
                                    <span class="text-emerald-600 font-bold">Pas (Rp 0)</span>
                                @elseif($shift->difference > 0)
                                    <span class="text-emerald-700">+Rp {{ number_format($shift->difference, 0, ',', '.') }} (Lebih)</span>
                                @else
                                    <span class="text-red-600">-Rp {{ number_format(abs($shift->difference), 0, ',', '.') }} (Kurang)</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($shift->isOpen())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Buka
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">
                                        Ditutup
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Belum ada riwayat shift kasir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shifts->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $shifts->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Buka Shift -->
<div id="open-shift-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-base font-bold text-[#111827]">Buka Shift Kasir Baru</h3>
            <button type="button" onclick="document.getElementById('open-shift-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kasira.shift.open') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#374151] mb-1">Kas Awal / Modal Laci (Rp) *</label>
                <input type="number" name="initial_cash" required min="0" step="500" placeholder="Contoh: 100000"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:border-[#5300B7] outline-none font-bold">
                <span class="text-[11px] text-gray-500 mt-1 block">Uang pecahan / uang kembalian di laci sebelum transaksi dimulai.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Catatan Shift (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan khusus shift pagi/siang..."
                          class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('open-shift-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-xs">
                    Mulai Shift Kasir
                </button>
            </div>
        </form>
    </div>
</div>

@if($activeShift)
<!-- Modal Tutup Shift & Rekonsiliasi -->
<div id="close-shift-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-[#111827]">Tutup Shift & Rekonsiliasi Kas</h3>
                <p class="text-xs text-[#6B7280]">Hitung fisik uang tunai di laci kasir</p>
            </div>
            <button type="button" onclick="document.getElementById('close-shift-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kasira.shift.close', $activeShift->id) }}" method="POST" class="space-y-4">
            @csrf
            <!-- Summary breakdown -->
            <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-xs space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Kas Awal:</span>
                    <strong class="text-gray-900">Rp {{ number_format($activeShift->initial_cash, 0, ',', '.') }}</strong>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Penjualan Tunai:</span>
                    <strong class="text-emerald-600">+Rp {{ number_format($shiftSummary['cash_sales'] ?? 0, 0, ',', '.') }}</strong>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Penjualan QRIS:</span>
                    <strong class="text-blue-600">Rp {{ number_format($shiftSummary['qris_sales'] ?? 0, 0, ',', '.') }}</strong>
                </div>
                <div class="pt-2 border-t border-gray-200 flex justify-between font-bold text-sm text-[#111827]">
                    <span>Kas Diharapkan di Laci:</span>
                    <span id="expected-cash-label" data-expected="{{ $shiftSummary['expected_cash'] ?? $activeShift->initial_cash }}" class="text-[#5300B7]">
                        Rp {{ number_format($shiftSummary['expected_cash'] ?? $activeShift->initial_cash, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#374151] mb-1">Jumlah Kas Fisik Aktual di Laci (Rp) *</label>
                <input type="number" id="actual-cash-input" name="actual_cash" required min="0" step="100" placeholder="Hitung uang tunai fisik di laci..."
                       oninput="calculateDifference(this.value)"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-base font-bold focus:border-[#5300B7] outline-none">
            </div>

            <!-- Difference indicator -->
            <div id="diff-container" class="p-3 rounded-xl border text-xs font-bold flex items-center justify-between hidden">
                <span id="diff-text">Selisih Kas:</span>
                <span id="diff-amount" class="text-sm">Rp 0</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Catatan Penutupan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Jelaskan bila ada selisih uang atau pengeluaran kas kecil..."
                          class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('close-shift-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition shadow-xs">
                    Konfirmasi Tutup Shift
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function calculateDifference(val) {
        const expected = parseFloat(document.getElementById('expected-cash-label').dataset.expected) || 0;
        const actual = parseFloat(val) || 0;
        const diff = actual - expected;
        const container = document.getElementById('diff-container');
        const text = document.getElementById('diff-text');
        const amount = document.getElementById('diff-amount');

        if (isNaN(val) || val === '') {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-800', 'bg-red-50', 'border-red-200', 'text-red-800', 'bg-blue-50', 'border-blue-200', 'text-blue-800');

        if (diff === 0) {
            container.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
            text.textContent = 'Status Kas: Pas';
            amount.textContent = 'Rp 0 (Sempurna)';
        } else if (diff > 0) {
            container.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-800');
            text.textContent = 'Kas Lebih:';
            amount.textContent = '+Rp ' + diff.toLocaleString('id-ID');
        } else {
            container.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
            text.textContent = 'Kas Kurang:';
            amount.textContent = '-Rp ' + Math.abs(diff).toLocaleString('id-ID');
        }
    }
</script>
@endif

@endsection
