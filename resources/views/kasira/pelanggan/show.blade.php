@extends('layouts.kasira')

@section('title', 'Detail Pelanggan — ' . $customer->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('kasira.pelanggan') }}"
           class="p-2 rounded-xl border border-[#E5E7EB] bg-white text-[#6B7280] hover:text-[#111827] hover:bg-[#F9FAFB] transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">{{ $customer->name }}</h1>
            <p class="text-xs text-[#6B7280] mt-0.5">Detail dan riwayat transaksi pelanggan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Customer Info Card --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#5300B7]/10 text-[#5300B7] flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#111827]">{{ $customer->name }}</h2>
                    @if($customer->phone)
                        <p class="text-xs text-[#6B7280] font-mono mt-0.5">{{ $customer->phone }}</p>
                    @endif
                    @if($customer->email)
                        <p class="text-xs text-[#6B7280] mt-0.5">{{ $customer->email }}</p>
                    @endif
                    @if($customer->address)
                        <p class="text-xs text-[#9CA3AF] mt-0.5">{{ $customer->address }}</p>
                    @endif
                </div>
            </div>

            <div class="text-right shrink-0">
                <p class="text-[10px] text-[#9CA3AF] uppercase font-bold tracking-wider">Total Belanja</p>
                <p class="text-xl font-bold text-[#5300B7]">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                <p class="text-[10px] text-[#9CA3AF] mt-0.5">Bergabung {{ $customer->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        {{-- Edit inline --}}
        <div class="mt-5 pt-4 border-t border-[#F3F4F6] flex items-center gap-2">
            <button type="button" onclick="document.getElementById('edit-customer-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E5E7EB] text-xs font-semibold text-[#374151] bg-white hover:bg-[#F9FAFB] transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Data
            </button>

            <form action="{{ route('kasira.pelanggan.destroy', $customer) }}" method="POST"
                  onsubmit="return confirm('Hapus pelanggan ini? Riwayat transaksi tetap tersimpan.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-red-200 text-xs font-semibold text-red-600 bg-white hover:bg-red-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Pelanggan
                </button>
            </form>
        </div>
    </div>

    {{-- Transaction History --}}
    <div>
        <h2 class="text-sm font-bold text-[#111827] mb-3">Riwayat Transaksi ({{ $transactions->total() }})</h2>

        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
            @if($transactions->isEmpty())
                <div class="py-12 text-center text-[#9CA3AF]">
                    <svg class="w-10 h-10 mx-auto mb-2 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm font-semibold text-[#111827]">Belum ada transaksi</p>
                    <p class="text-xs mt-1">Pelanggan ini belum pernah bertransaksi melalui Kasir POS.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#E5E7EB] bg-[#F9FAFB] text-[#6B7280] font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3 px-4">Invoice</th>
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Item</th>
                                <th class="py-3 px-4">Metode</th>
                                <th class="py-3 px-4 text-right">Total</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F3F4F6]">
                            @foreach($transactions as $trx)
                                <tr class="hover:bg-[#F9FAFB]/70 transition">
                                    <td class="py-3 px-4">
                                        <span class="font-mono font-bold text-[#111827]">{{ $trx->invoice_number }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-[#6B7280]">
                                        {{ $trx->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-[#4B5563]">
                                        {{ $trx->items->sum('quantity') }} pcs
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $trx->payment_method === 'cash' ? 'bg-emerald-100 text-emerald-800' : ($trx->payment_method === 'qris' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $trx->payment_method }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-[#111827]">
                                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                            {{ $trx->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $trx->status === 'completed' ? 'Selesai' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('kasira.penjualan.show', $trx) }}"
                                           class="text-[10px] font-bold text-[#5300B7] hover:underline">Lihat →</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="p-4 border-t border-[#E5E7EB]">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- Edit Customer Modal --}}
<div id="edit-customer-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-[#E5E7EB] z-10 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-[#F3F4F6]">
            <h3 class="text-base font-bold text-[#111827]">Edit Data Pelanggan</h3>
            <button type="button" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')" class="text-[#9CA3AF] hover:text-[#111827]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('kasira.pelanggan.update', $customer) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" required value="{{ old('name', $customer->name) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">No. WhatsApp / Telp</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-mono text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Alamat</label>
                <textarea name="address" rows="2"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none resize-none">{{ old('address', $customer->address) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-[#F3F4F6]">
                <button type="button" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-semibold text-[#6B7280]">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
