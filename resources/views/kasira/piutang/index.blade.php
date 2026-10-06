@extends('layouts.kasira')

@section('title', 'Piutang Pelanggan')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Piutang & Hutang Pelanggan</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Catat bon/kasbon pelanggan toko, jatuh tempo pembayaran, dan riwayat cicilan.</p>
        </div>

        <button type="button" onclick="document.getElementById('add-debt-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Catat Piutang Baru</span>
        </button>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-red-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-red-800 block">Total Piutang Belum Lunas</span>
                <span class="text-2xl font-extrabold text-red-600 tracking-tight block mt-1">
                    Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-gray-500 mt-0.5 block">Harus ditagih ke pelanggan</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-[#6B7280] block">Jumlah Tagihan Aktif</span>
                <span class="text-2xl font-extrabold text-[#111827] tracking-tight block mt-1">
                    {{ $unpaidCount }} Tagihan
                </span>
                <span class="text-[11px] text-gray-500 mt-0.5 block">Belum selesai dilunasi</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-[#5300B7] flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-emerald-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-emerald-800 block">Status Piutang</span>
                <span class="text-sm font-bold text-emerald-700 block mt-1">Terkontrol & Aman</span>
                <span class="text-[11px] text-gray-500 mt-0.5 block">Kirim pengingat tagihan via WhatsApp</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
            <a href="{{ route('kasira.piutang.index') }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap {{ !request('status') ? 'bg-[#5300B7] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua Piutang
            </a>
            <a href="{{ route('kasira.piutang.index', ['status' => 'unpaid']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap {{ request('status') === 'unpaid' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Belum Lunas
            </a>
            <a href="{{ route('kasira.piutang.index', ['status' => 'partial']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap {{ request('status') === 'partial' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Sedang Dicicil
            </a>
            <a href="{{ route('kasira.piutang.index', ['status' => 'paid']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap {{ request('status') === 'paid' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Lunas
            </a>
        </div>

        <form action="{{ route('kasira.piutang.index') }}" method="GET" class="w-full sm:w-64 relative">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="q" placeholder="Cari pelanggan..."
                   value="{{ request('q') }}"
                   class="w-full pl-8 pr-3 py-1.5 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] focus:bg-white focus:border-[#5300B7] outline-none">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-[#9CA3AF]">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </form>
    </div>

    <!-- Debts Table -->
    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F9FAFB] text-[#6B7280] font-bold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4">Tanggal Bon</th>
                        <th class="py-3 px-4">Jatuh Tempo</th>
                        <th class="py-3 px-4 text-right">Total Bon</th>
                        <th class="py-3 px-4 text-right">Sudah Dibayar</th>
                        <th class="py-3 px-4 text-right">Sisa Tagihan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($debts as $debt)
                        <tr class="hover:bg-[#F9FAFB]/50 transition">
                            <td class="py-3.5 px-4 font-bold text-[#111827]">
                                <div>{{ $debt->customer?->name ?? 'Pelanggan Umum' }}</div>
                                @if($debt->customer?->phone)
                                    <div class="text-[11px] font-normal text-[#5300B7]">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $debt->customer->phone) }}?text={{ urlencode('Halo Kak ' . $debt->customer->name . ', ini dari ' . $business->name . ' menginfokan sisa tagihan bon sebesar Rp ' . number_format($debt->remaining_amount, 0, ',', '.') . '. Terima kasih!') }}"
                                           target="_blank" class="hover:underline flex items-center gap-1 mt-0.5">
                                            <span>WA: {{ $debt->customer->phone }}</span>
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if($debt->notes)
                                    <span class="text-[10px] text-gray-500 italic font-normal block">{{ $debt->notes }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[#6B7280]">
                                {{ $debt->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($debt->due_date)
                                    <span class="{{ $debt->due_date->isPast() && $debt->status !== 'paid' ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                        {{ $debt->due_date->format('d M Y') }}
                                        @if($debt->due_date->isPast() && $debt->status !== 'paid')
                                            <span class="block text-[10px] text-red-600">Lewat tempo</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-semibold text-gray-900">
                                Rp {{ number_format($debt->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-emerald-600 font-medium">
                                Rp {{ number_format($debt->paid_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-extrabold text-red-600">
                                Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($debt->status === 'paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Lunas
                                    </span>
                                @elseif($debt->status === 'partial')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        Dicicil
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($debt->status !== 'paid')
                                    <button type="button"
                                            onclick='openPayDebtModal(@json($debt))'
                                            class="px-3 py-1.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-[11px] font-bold transition shadow-xs">
                                        Bayar / Cicil
                                    </button>
                                @else
                                    <span class="text-[11px] text-emerald-600 font-semibold">Tuntas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">Tidak ada data piutang pelanggan saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($debts->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $debts->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Piutang Baru -->
<div id="add-debt-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-base font-bold text-[#111827]">Catat Piutang / Bon Pelanggan</h3>
            <button type="button" onclick="document.getElementById('add-debt-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kasira.piutang.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#374151] mb-1">Pilih Pelanggan *</label>
                <select name="customer_id" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?: 'Tanpa nomor' }})</option>
                    @endforeach
                </select>
                <span class="text-[10px] text-gray-500 mt-1 block">Jika pelanggan belum ada, daftarkan terlebih dahulu di menu Pelanggan.</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#374151] mb-1">Nominal Hutang / Bon (Rp) *</label>
                <input type="number" name="total_amount" required min="1000" step="500" placeholder="Contoh: 50000"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm font-bold focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Tanggal Jatuh Tempo (Opsional)</label>
                <input type="date" name="due_date"
                       class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#374151] mb-1">Catatan / Rincian Barang</label>
                <textarea name="notes" rows="2" placeholder="Barang yang dibon: Beras 5kg, Telur 1kg..."
                          class="w-full px-3.5 py-2 rounded-xl border border-gray-300 text-xs focus:border-[#5300B7] outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('add-debt-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition shadow-xs">
                    Simpan Piutang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catat Pembayaran / Cicilan -->
<div id="pay-debt-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-[#111827]">Catat Pembayaran Piutang</h3>
                <p id="pay-customer-name" class="text-xs text-[#5300B7] font-semibold"></p>
            </div>
            <button type="button" onclick="document.getElementById('pay-debt-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="pay-debt-form" method="POST" class="space-y-3.5">
            @csrf
            <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-xs space-y-1.5">
                <div class="flex justify-between text-gray-600">
                    <span>Total Hutang:</span>
                    <span id="pay-total-amount" class="font-bold text-gray-900"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Sudah Dibayar:</span>
                    <span id="pay-paid-amount" class="font-bold text-emerald-600"></span>
                </div>
                <div class="pt-1.5 border-t border-gray-200 flex justify-between font-bold text-[#111827]">
                    <span>Sisa Yang Harus Dibayar:</span>
                    <span id="pay-remaining-amount" class="text-red-600 text-sm"></span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#374151] mb-1">Nominal Pembayaran Sekarang (Rp) *</label>
                <input type="number" id="pay-amount-input" name="amount" required min="1000" step="500"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-base font-bold focus:border-[#5300B7] outline-none">
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="document.getElementById('pay-debt-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs">
                    Konfirmasi Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPayDebtModal(debt) {
        const form = document.getElementById('pay-debt-form');
        form.action = `/piutang/${debt.id}/bayar`;
        document.getElementById('pay-customer-name').textContent = 'Pelanggan: ' + (debt.customer ? debt.customer.name : 'Umum');
        document.getElementById('pay-total-amount').textContent = 'Rp ' + Number(debt.total_amount).toLocaleString('id-ID');
        document.getElementById('pay-paid-amount').textContent = 'Rp ' + Number(debt.paid_amount).toLocaleString('id-ID');
        document.getElementById('pay-remaining-amount').textContent = 'Rp ' + Number(debt.remaining_amount).toLocaleString('id-ID');

        const input = document.getElementById('pay-amount-input');
        input.value = debt.remaining_amount;
        input.max = debt.remaining_amount;

        document.getElementById('pay-debt-modal').classList.remove('hidden');
    }
</script>
@endsection
