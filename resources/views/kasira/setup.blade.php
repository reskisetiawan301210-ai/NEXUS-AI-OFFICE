<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F8F9FC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Siapkan Bisnis Anda — Kasira</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full font-sans antialiased text-[#111827] bg-[#F8F9FC] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Kasira Logo -->
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#5300B7] text-white shadow-lg shadow-[#5300B7]/20 mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-[#111827]">KASIRA</h1>
        <p class="text-sm text-[#6B7280] mt-1 font-medium">Kelola Bisnis, Lebih Mudah</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-sm border border-[#E5E7EB] rounded-2xl">
            <div class="mb-6 border-b border-[#F3F4F6] pb-4">
                <h2 class="text-xl font-bold text-[#111827]">Siapkan bisnis Anda</h2>
                <p class="text-sm text-[#6B7280] mt-1">Lengkapi informasi dasar untuk mulai mengelola penjualan dan stok toko Anda.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kasira.setup.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Nama Usaha -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-[#111827]">
                        Nama Usaha <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1.5">
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Contoh: Warung Suka Maju"
                               class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] bg-white text-sm text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] focus:ring-2 focus:ring-[#5300B7]/20 outline-none transition">
                    </div>
                </div>

                <!-- Jenis Usaha -->
                <div>
                    <label for="business_type" class="block text-sm font-semibold text-[#111827]">
                        Jenis Usaha <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1.5">
                        <select name="business_type" id="business_type" required
                                class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] bg-white text-sm text-[#111827] focus:border-[#5300B7] focus:ring-2 focus:ring-[#5300B7]/20 outline-none transition">
                            <option value="Warung" {{ old('business_type') === 'Warung' ? 'selected' : '' }}>Warung</option>
                            <option value="Toko Kelontong" {{ old('business_type') === 'Toko Kelontong' ? 'selected' : '' }}>Toko Kelontong</option>
                            <option value="Cafe / Kedai Kopi" {{ old('business_type') === 'Cafe / Kedai Kopi' ? 'selected' : '' }}>Cafe / Kedai Kopi</option>
                            <option value="Makanan & Minuman" {{ old('business_type') === 'Makanan & Minuman' ? 'selected' : '' }}>Makanan & Minuman (F&B)</option>
                            <option value="Retail / Toko" {{ old('business_type') === 'Retail / Toko' ? 'selected' : '' }}>Retail / Toko</option>
                            <option value="Jasa" {{ old('business_type') === 'Jasa' ? 'selected' : '' }}>Jasa</option>
                            <option value="Lainnya" {{ old('business_type') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Logo / Foto Usaha -->
                <div>
                    <label class="block text-sm font-semibold text-[#111827]">Logo / Foto Usaha (Opsional)</label>
                    <div class="mt-1.5 flex items-center gap-4">
                        <div id="logo-preview-box" class="w-16 h-16 rounded-xl border-2 border-dashed border-[#D1D5DB] flex items-center justify-center bg-[#F9FAFB] text-[#9CA3AF] overflow-hidden shrink-0">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <label for="logo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-[#E5E7EB] rounded-xl text-xs font-semibold text-[#374151] bg-white hover:bg-[#F9FAFB] transition shadow-xs">
                            <span>Pilih Foto</span>
                            <input type="file" name="logo" id="logo" accept="image/*" class="sr-only" onchange="previewLogo(event)">
                        </label>
                    </div>
                </div>

                <!-- WhatsApp -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-[#111827]">Nomor WhatsApp (Opsional)</label>
                    <div class="mt-1.5">
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] bg-white text-sm text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] focus:ring-2 focus:ring-[#5300B7]/20 outline-none transition">
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address" class="block text-sm font-semibold text-[#111827]">Alamat Usaha (Opsional)</label>
                    <div class="mt-1.5">
                        <textarea name="address" id="address" rows="3" placeholder="Jl. Merdeka No. 12, Jakarta"
                                  class="w-full px-4 py-2.5 rounded-xl border border-[#E5E7EB] bg-white text-sm text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] focus:ring-2 focus:ring-[#5300B7]/20 outline-none transition resize-none">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-3">
                    <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white font-semibold text-sm shadow-md shadow-[#5300B7]/25 transition flex items-center justify-center gap-2">
                        <span>Simpan & Mulai</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-[#9CA3AF] mt-6">
            LifeHub AI Platform &copy; {{ date('Y') }} Kasira. Hak cipta dilindungi.
        </p>
    </div>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const box = document.getElementById('logo-preview-box');
                    box.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-xl" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>

