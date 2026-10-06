@extends('layouts.kasira')

@section('title', 'Pengaturan Toko')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-[#111827] tracking-tight">Pengaturan Toko & Bisnis</h1>
        <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5">Kustomisasi identitas toko, batas notifikasi stok, dan format struk belanja.</p>
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

    <form action="{{ route('kasira.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs space-y-5">
        @csrf
        @method('PUT')

        {{-- Logo Upload --}}
        <div>
            <label class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-2">Logo Toko (Opsional)</label>
            <div class="flex items-center gap-4">
                <div id="logo-preview-box"
                     class="w-20 h-20 rounded-xl border-2 border-dashed border-[#D1D5DB] flex items-center justify-center bg-[#F9FAFB] text-[#9CA3AF] overflow-hidden shrink-0">
                    @if($business->logo_path)
                        <img src="{{ asset('storage/' . $business->logo_path) }}" alt="Logo Toko" class="w-full h-full object-cover">
                    @else
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    @endif
                </div>
                <div class="space-y-2">
                    <label for="logo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-[#E5E7EB] rounded-xl text-xs font-semibold text-[#374151] bg-white hover:bg-[#F9FAFB] transition shadow-2xs">
                        <span>{{ $business->logo_path ? 'Ganti Logo' : 'Pilih Logo' }}</span>
                        <input type="file" name="logo" id="logo" accept="image/*" class="sr-only" onchange="previewLogo(event)">
                    </label>
                    <p class="text-[11px] text-[#9CA3AF]">PNG, JPG, WEBP maks. 5 MB.</p>
                    @if($business->logo_path)
                        <div class="flex items-center gap-1.5">
                            <input type="checkbox" name="remove_logo" id="remove_logo" value="1"
                                   class="w-3.5 h-3.5 text-red-600 rounded">
                            <label for="remove_logo" class="text-[11px] text-red-600 font-medium cursor-pointer">Hapus logo saat ini</label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Nama Usaha <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name', $business->name) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label for="business_type" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Jenis Usaha <span class="text-red-500">*</span>
                </label>
                <input type="text" name="business_type" id="business_type" required value="{{ old('business_type', $business->business_type) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="phone" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Nomor WhatsApp / Telepon
                </label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $business->phone) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
            </div>

            <div>
                <label for="low_stock_threshold" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Batas Peringatan Stok Menipis <span class="text-red-500">*</span>
                </label>
                <input type="number" name="low_stock_threshold" id="low_stock_threshold" min="1" required value="{{ old('low_stock_threshold', $business->low_stock_threshold) }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#111827] focus:border-[#5300B7] outline-none">
            </div>
        </div>

        <div>
            <label for="address" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                Alamat Toko
            </label>
            <textarea name="address" id="address" rows="2"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none resize-none">{{ old('address', $business->address) }}</textarea>
        </div>

        <div>
            <label for="receipt_footer" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                Catatan Kaki Struk Belanja
            </label>
            <input type="text" name="receipt_footer" id="receipt_footer" value="{{ old('receipt_footer', $business->receipt_footer) }}" placeholder="Contoh: Barang yang sudah dibeli tidak dapat ditukar."
                   class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] text-xs text-[#111827] focus:border-[#5300B7] outline-none">
        </div>

        <div class="pt-4 border-t border-[#F3F4F6] flex justify-end">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-md shadow-[#5300B7]/25 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    function previewLogo(event) {
        const file = event.target.files[0];
        if (!file) { return; }
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran logo terlalu besar. Maksimal 5 MB.');
            event.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const box = document.getElementById('logo-preview-box');
            box.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview Logo">`;
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
