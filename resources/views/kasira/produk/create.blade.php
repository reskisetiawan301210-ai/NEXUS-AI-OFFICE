@extends('layouts.kasira')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('kasira.produk.index') }}" class="p-2 rounded-xl bg-white border border-[#E5E7EB] text-[#6B7280] hover:text-[#111827] hover:bg-[#F9FAFB] transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#111827]">Tambah Produk Baru</h1>
                <p class="text-xs text-[#6B7280]">Lengkapi detail informasi produk untuk katalog bisnis Anda.</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kasira.produk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs space-y-6">
        @csrf

        <!-- Product Name & Barcode -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Contoh: Indomie Goreng"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none transition">
            </div>

            <div>
                <label for="barcode" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Barcode / SKU (Opsional)
                </label>
                <div class="relative">
                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $initialBarcode) }}" placeholder="Contoh: 8991234567890"
                           class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-mono text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none transition">
                    <a href="{{ route('kasira.barcode') }}" class="absolute right-2 top-2 p-1 text-[#6B7280] hover:text-[#5300B7]" title="Scan dengan Kamera">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Prices -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="cost_price" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Harga Beli / Modal (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" step="100" min="0" name="cost_price" id="cost_price" required value="{{ old('cost_price', 0) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-semibold text-[#111827] focus:border-[#5300B7] outline-none transition">
            </div>

            <div>
                <label for="selling_price" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Harga Jual (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" step="100" min="0" name="selling_price" id="selling_price" required value="{{ old('selling_price') }}" placeholder="3500"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#5300B7] focus:border-[#5300B7] outline-none transition">
            </div>
        </div>

        <!-- Stock & Min Stock -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="stock" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Stok Saat Ini <span class="text-red-500">*</span>
                </label>
                <input type="number" min="0" name="stock" id="stock" required value="{{ old('stock', 10) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-semibold text-[#111827] focus:border-[#5300B7] outline-none transition">
            </div>

            <div>
                <label for="min_stock" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Batas Minimum Stok <span class="text-red-500">*</span>
                </label>
                <input type="number" min="0" name="min_stock" id="min_stock" required value="{{ old('min_stock', 5) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-semibold text-[#111827] focus:border-[#5300B7] outline-none transition">
            </div>

            <div>
                <label for="category_id" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Kategori
                </label>
                <select name="category_id" id="category_id"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-white text-xs font-medium text-[#111827] focus:border-[#5300B7] outline-none transition">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="supplier_id" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Supplier / Agen (Opsional)
                </label>
                <select name="supplier_id" id="supplier_id"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-white text-xs font-medium text-[#111827] focus:border-[#5300B7] outline-none transition">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Product Photo Upload -->
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">Foto Produk (Opsional)</label>
            <div class="flex items-center gap-4">
                <div id="photo-preview-box" class="w-20 h-20 rounded-xl border-2 border-dashed border-[#D1D5DB] flex items-center justify-center bg-[#F9FAFB] text-[#9CA3AF] overflow-hidden shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <label for="photo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-[#E5E7EB] rounded-xl text-xs font-semibold text-[#374151] bg-white hover:bg-[#F9FAFB] transition shadow-2xs">
                        <span>Pilih Foto Produk</span>
                        <input type="file" name="photo" id="photo" accept="image/*" class="sr-only" onchange="previewProductPhoto(event)">
                    </label>
                    <p class="text-[11px] text-[#9CA3AF] mt-1">PNG, JPG, WEBP maks. 2MB. Gambar persegi dianjurkan.</p>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                Deskripsi Produk (Opsional)
            </label>
            <textarea name="description" id="description" rows="3" placeholder="Informasi tambahan produk, rasa, ukuran kemasan..."
                      class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none transition resize-none">{{ old('description') }}</textarea>
        </div>

        <!-- Active Toggle -->
        <div class="flex items-center gap-2.5 pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" checked
                   class="w-4 h-4 text-[#5300B7] rounded border-[#D1D5DB] focus:ring-[#5300B7]">
            <label for="is_active" class="text-xs font-semibold text-[#374151] select-none">
                Produk Aktif (dapat dijual di Kasir)
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F3F4F6]">
            <a href="{{ route('kasira.produk.index') }}" class="px-4 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#374151] hover:bg-[#F9FAFB] transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-md shadow-[#5300B7]/25 transition">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<script>
    function previewProductPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('photo-preview-box');
                box.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection

