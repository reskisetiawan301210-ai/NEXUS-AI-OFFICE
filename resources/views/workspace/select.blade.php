<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#111111]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pilih Workspace — LifeHub</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full min-h-[100dvh] bg-[#111111] text-white antialiased selection:bg-[#5300B7] selection:text-white font-sans flex flex-col justify-between p-5 sm:p-8">

    <!-- Topbar -->
    <header class="w-full max-w-4xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#18181B] border border-[#27272A] flex items-center justify-center">
                <svg class="w-5 h-5 text-white" viewBox="0 0 48 48" fill="none" stroke="currentColor">
                    <path d="M24 6L38 14V34L24 42L10 34V14L24 6Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                    <path d="M10 14L24 22L38 14" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                    <path d="M24 22V42" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                    <circle cx="24" cy="22" r="3" fill="#5300B7" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-white">
                LifeHub<span class="text-[#5300B7]">.</span>ai
            </span>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-[#5300B7] text-white font-bold text-xs flex items-center justify-center shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="text-xs font-semibold text-[#8E8E93] hidden sm:inline">{{ auth()->user()->name }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-full bg-[#18181B] hover:bg-[#27272A] border border-[#27272A] text-xs font-medium text-[#8E8E93] hover:text-white transition">
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <!-- Main Section -->
    <main class="w-full max-w-3xl mx-auto my-auto py-10">
        <div class="text-center mb-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#5300B7]/20 border border-[#5300B7]/40 text-[#C4B5FD] mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
                Workspace Selector
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                Welcome to LifeHub
            </h1>
            <p class="text-sm sm:text-base text-[#8E8E93] mt-2">
                Pilih workspace kamu untuk mulai bekerja
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Card 1: LifeHub AI -->
            <a href="{{ route('lifehub.dashboard') }}"
               class="group relative flex flex-col justify-between p-6 sm:p-7 rounded-3xl bg-[#171717] hover:bg-[#1A1A1A] border-2 border-[#262626] hover:border-[#5300B7] transition-all duration-200 shadow-xl hover:shadow-[0_0_30px_rgba(83,0,183,0.25)] active:scale-[0.99]">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-[#5300B7]/20 border border-[#5300B7]/40 flex items-center justify-center text-[#C4B5FD] group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-[#8E8E93] group-hover:text-white flex items-center gap-1">
                            Buka <span class="transition-transform group-hover:translate-x-0.5">&rarr;</span>
                        </span>
                    </div>

                    <h2 class="text-xl font-bold text-white tracking-tight mb-2 group-hover:text-[#F4F4F5]">
                        LifeHub AI
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8E8E93] leading-relaxed mb-6">
                        AI & Digital Workspace untuk asisten cerdas, pembuatan konten, riset, dan produktivitas harian.
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-[#262626] text-[11px] text-[#A1A1AA]">
                    <span class="inline-flex items-center gap-1 bg-[#111111] px-2.5 py-1 rounded-full border border-[#27272A]">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        AI Models
                    </span>
                    <span class="inline-flex items-center gap-1 bg-[#111111] px-2.5 py-1 rounded-full border border-[#27272A]">
                        Workspace
                    </span>
                </div>
            </a>

            <!-- Card 2: Kasira -->
            <a href="{{ route('kasira.dashboard') }}"
               class="group relative flex flex-col justify-between p-6 sm:p-7 rounded-3xl bg-[#171717] hover:bg-[#1A1A1A] border-2 border-[#262626] hover:border-[#5300B7] transition-all duration-200 shadow-xl hover:shadow-[0_0_30px_rgba(83,0,183,0.25)] active:scale-[0.99]">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-[#5300B7]/20 border border-[#5300B7]/40 flex items-center justify-center text-[#C4B5FD] group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-[#8E8E93] group-hover:text-white flex items-center gap-1">
                            Buka <span class="transition-transform group-hover:translate-x-0.5">&rarr;</span>
                        </span>
                    </div>

                    <h2 class="text-xl font-bold text-white tracking-tight mb-2 group-hover:text-[#F4F4F5]">
                        Kasira
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8E8E93] leading-relaxed mb-6">
                        Business & POS untuk kasir toko, manajemen produk, stok gudang, laporan penjualan, dan pelanggan UMKM.
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-[#262626] text-[11px] text-[#A1A1AA]">
                    <span class="inline-flex items-center gap-1 bg-[#111111] px-2.5 py-1 rounded-full border border-[#27272A]">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                        POS & Kasir
                    </span>
                    <span class="inline-flex items-center gap-1 bg-[#111111] px-2.5 py-1 rounded-full border border-[#27272A]">
                        Manajemen Toko
                    </span>
                </div>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-4xl mx-auto text-center py-4">
        <p class="text-xs text-[#6B7280]">
            &copy; {{ date('Y') }} LifeHub AI Platform. Anda dapat beralih workspace kapan saja melalui menu aplikasi.
        </p>
    </footer>

</body>
</html>

