@extends('layouts.kasira')

@section('title', 'Produk')

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Produk</h1>
            <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Kelola produk, harga, stok, kategori, dan barcode bisnis kamu.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('kasira.barcode') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-[#E5E7EB] hover:bg-[#F9FAFB] text-xs font-bold text-[#111827] shadow-2xs transition">
                <svg class="w-4 h-4 text-[#5300B7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <span>Scan Barcode</span>
            </a>

            <a href="{{ route('kasira.produk.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-sm shadow-[#5300B7]/25 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('kasira.produk.index') }}" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk atau barcode..."
                       class="w-full pl-9 pr-4 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:bg-white focus:border-[#5300B7] outline-none transition">
                <svg class="w-4 h-4 text-[#9CA3AF] absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()"
                    class="px-3 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] focus:bg-white focus:border-[#5300B7] outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <!-- Filter Status -->
            <select name="filter" onchange="this.form.submit()"
                    class="px-3 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] focus:bg-white focus:border-[#5300B7] outline-none">
                <option value="">Semua Produk</option>
                <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Status Aktif</option>
                <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Status Nonaktif</option>
                <option value="low_stock" {{ request('filter') === 'low_stock' ? 'selected' : '' }}>Stok Menipis</option>
                <option value="out_of_stock" {{ request('filter') === 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
            </select>

            <select name="sort" onchange="this.form.submit()"
                    class="px-3 py-2 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] focus:bg-white focus:border-[#5300B7] outline-none">
                <option value="">Terbaru</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="stock_asc" {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#F3F4F6] hover:bg-[#E5E7EB] text-xs font-semibold text-[#374151] transition">
                Filter
            </button>
        </form>

        @if(request('q') || request('category') || request('filter') || request('sort'))
            <a href="{{ route('kasira.produk.index') }}" class="text-xs text-[#5300B7] font-semibold hover:underline self-start sm:self-auto">
                Reset Filter
            </a>
        @endif
    </div>

    <!-- Products Table -->
    <div class="hidden md:block bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-[#E5E7EB] bg-[#F9FAFB] text-[#6B7280] font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Barcode</th>
                        <th class="py-3 px-4 text-right">Harga Beli</th>
                        <th class="py-3 px-4 text-right">Harga Jual</th>
                        <th class="py-3 px-4 text-center">Stok</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($products as $product)
                        <tr class="hover:bg-[#F9FAFB]/70 transition">
                            <!-- Product Photo & Name -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#F3F4F6] border border-[#E5E7EB] overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-[#111827] truncate max-w-[200px]">{{ $product->name }}</h4>
                                        @if($product->description)
                                            <p class="text-[10px] text-[#6B7280] truncate max-w-[200px]">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-lg bg-[#F3F4F6] text-[#4B5563] font-medium text-[11px]">
                                    {{ $product->category?->name ?? 'Umum' }}
                                </span>
                            </td>

                            <!-- Barcode -->
                            <td class="py-3 px-4">
                                @if($product->barcode)
                                    <span class="font-mono text-[11px] text-[#374151] bg-[#F9FAFB] border border-[#E5E7EB] px-2 py-0.5 rounded">
                                        {{ $product->barcode }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-[#9CA3AF] italic">-</span>
                                @endif
                            </td>

                            <!-- Cost Price -->
                            <td class="py-3 px-4 text-right font-medium text-[#6B7280]">
                                Rp {{ number_format($product->cost_price, 0, ',', '.') }}
                            </td>

                            <!-- Selling Price -->
                            <td class="py-3 px-4 text-right font-bold text-[#111827]">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </td>

                            <!-- Stock -->
                            <td class="py-3 px-4 text-center">
                                @if($product->stock <= 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                        Habis (0)
                                    </span>
                                @elseif($product->stock <= $product->min_stock)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Min. {{ $product->min_stock }}">
                                        {{ $product->stock }} (Menipis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $product->stock }}
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3 px-4 text-center">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">Nonaktif</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('kasira.produk.edit', $product) }}"
                                       class="p-1.5 rounded-lg text-[#6B7280] hover:text-[#5300B7] hover:bg-[#5300B7]/10 transition" title="Edit Produk">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('kasira.produk.status', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}">
                                        <button type="submit" class="p-1.5 rounded-lg text-[#6B7280] hover:text-[#5300B7] hover:bg-[#5300B7]/10 transition" title="{{ $product->is_active ? 'Nonaktifkan Produk' : 'Aktifkan Produk' }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m4-2a8 8 0 11-16 0 8 8 0 0116 0z" />
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('kasira.produk.destroy', $product) }}" method="POST" onsubmit="return confirm('Produk yang sudah memiliki riwayat penjualan akan dinonaktifkan. Lanjutkan?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-[#6B7280] hover:text-red-600 hover:bg-red-50 transition" title="Hapus atau nonaktifkan produk">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-[#9CA3AF]">
                                <svg class="w-10 h-10 mx-auto mb-2 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-sm font-semibold text-[#111827]">Belum ada produk ditemukan</p>
                                <p class="text-xs text-[#6B7280] mt-1">Coba sesuaikan kata kunci pencarian atau daftarkan produk baru.</p>
                                <a href="{{ route('kasira.produk.create') }}" class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#5300B7] text-white text-xs font-semibold shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Tambah Produk Baru</span>
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </div>

    <div class="md:hidden space-y-3">
        @forelse($products as $product)
            <article class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-4">
                <div class="flex gap-3">
                    <div class="w-14 h-14 rounded-xl bg-[#F3F4F6] border border-[#E5E7EB] overflow-hidden shrink-0 flex items-center justify-center">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-6 h-6 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h2 class="font-bold text-sm text-[#111827] truncate">{{ $product->name }}</h2>
                                <p class="text-xs font-bold text-[#5300B7] mt-0.5">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-[10px] font-bold {{ $product->is_active ? 'text-emerald-700' : 'text-gray-500' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <p class="mt-2 text-[11px] text-[#6B7280]">Barcode: <span class="font-mono text-[#374151]">{{ $product->barcode ?: '-' }}</span></p>
                        <div class="mt-2 flex items-center justify-between gap-2 text-[11px]">
                            <span class="text-[#6B7280]">{{ $product->category?->name ?? 'Umum' }}</span>
                            <span class="font-bold {{ $product->stock <= 0 ? 'text-red-600' : ($product->stock <= $product->min_stock ? 'text-amber-600' : 'text-emerald-600') }}">{{ $product->stock <= 0 ? 'Habis' : ($product->stock <= $product->min_stock ? 'Menipis: '.$product->stock : 'Stok: '.$product->stock) }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#F3F4F6] flex justify-end gap-2">
                    <a href="{{ route('kasira.produk.edit', $product) }}" class="px-3 py-2 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#374151]">Edit</a>
                    <form action="{{ route('kasira.produk.status', $product) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}">
                        <button type="submit" class="px-3 py-2 rounded-xl bg-[#F3F4F6] text-xs font-bold text-[#374151]">{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="bg-white rounded-2xl border border-[#E5E7EB] p-8 text-center">
                <p class="text-sm font-bold text-[#111827]">{{ request()->hasAny(['q', 'category', 'filter']) ? 'Produk tidak ditemukan.' : 'Belum ada produk.' }}</p>
                <p class="text-xs text-[#6B7280] mt-1">Tambahkan produk pertama untuk mulai menggunakan Kasira.</p>
            </div>
        @endforelse
    </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
