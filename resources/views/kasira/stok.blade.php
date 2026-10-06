@extends('layouts.kasira')

@section('title', 'Manajemen Stok')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Manajemen Stok & Persediaan</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Pantau jumlah unit produk, batas minimum, dan perbarui stok secara instan.</p>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <span class="text-xs font-semibold text-[#6B7280]">Total Seluruh Unit</span>
            <div class="text-xl font-bold text-[#111827] mt-1">{{ number_format($totalStockUnits, 0, ',', '.') }} unit</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <span class="text-xs font-semibold text-[#6B7280]">Stok Menipis</span>
            <div class="text-xl font-bold text-amber-600 mt-1">{{ $lowStockCount }} produk</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <span class="text-xs font-semibold text-[#6B7280]">Stok Habis (0)</span>
            <div class="text-xl font-bold text-red-600 mt-1">{{ $outOfStockCount }} produk</div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('kasira.stok') }}" class="flex-1 flex flex-col sm:flex-row items-center gap-2.5 w-full">
            <div class="relative flex-1 max-w-md w-full">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk atau barcode..."
                       class="w-full pl-9 pr-4 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none">
                <svg class="w-4 h-4 text-[#9CA3AF] absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select name="filter" onchange="this.form.submit()"
                    class="px-3 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] focus:border-[#5300B7] outline-none w-full sm:w-auto">
                <option value="">Semua Stok</option>
                <option value="low_stock" {{ request('filter') === 'low_stock' ? 'selected' : '' }}>Hanya Stok Menipis & Habis</option>
            </select>
        </form>

        @if(request('q') || request('filter'))
            <a href="{{ route('kasira.stok') }}" class="text-xs text-[#5300B7] font-semibold hover:underline">Reset</a>
        @endif
    </div>

    <!-- Stock Table -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-[#E5E7EB] bg-[#F9FAFB] text-[#6B7280] font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Barcode</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Stok Saat Ini</th>
                        <th class="py-3 px-4 text-center">Batas Minimum</th>
                        <th class="py-3 px-4 text-right">Perbarui Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($products as $product)
                        <tr class="hover:bg-[#F9FAFB]/70 transition">
                            <td class="py-3 px-4">
                                <span class="font-bold text-[#111827] block">{{ $product->name }}</span>
                                <span class="text-[10px] text-[#6B7280]">{{ $product->category?->name ?? 'Umum' }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono text-[#4B5563]">
                                {{ $product->barcode ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($product->stock <= 0)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">Habis</span>
                                @elseif($product->stock <= $product->min_stock)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Menipis</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aman</span>
                                @endif
                            </td>
                            <form action="{{ route('kasira.stok.update', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <td class="py-3 px-4 text-center">
                                    <input type="number" name="stock" value="{{ $product->stock }}" min="0"
                                           class="w-20 px-2 py-1 text-center bg-[#F9FAFB] border border-[#E5E7EB] rounded-lg text-xs font-bold text-[#111827] focus:bg-white focus:border-[#5300B7] outline-none">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <input type="number" name="min_stock" value="{{ $product->min_stock }}" min="0"
                                           class="w-16 px-2 py-1 text-center bg-[#F9FAFB] border border-[#E5E7EB] rounded-lg text-xs font-medium text-[#6B7280] focus:bg-white focus:border-[#5300B7] outline-none">
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button type="submit" class="px-3 py-1 bg-[#5300B7] hover:bg-[#430095] text-white text-[11px] font-bold rounded-lg shadow-2xs transition">
                                        Simpan
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-[#9CA3AF]">
                                Tidak ada data stok yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

