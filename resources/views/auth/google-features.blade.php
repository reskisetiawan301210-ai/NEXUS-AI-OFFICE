<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#111111]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pilih Pengalaman — LifeHub AI</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full min-h-[100dvh] bg-[#111111] text-[#FFFFFF] antialiased selection:bg-[#5300B7] selection:text-white font-sans flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-lg bg-[#171717] border border-[#262626] rounded-3xl p-6 sm:p-8 shadow-2xl relative">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#18181B] border border-[#27272A] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" viewBox="0 0 48 48" fill="none" stroke="currentColor">
                    <path d="M24 6L38 14V34L24 42L10 34V14L24 6Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                    <path d="M10 14L24 22L38 14" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                    <path d="M24 22V42" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                    <circle cx="24" cy="22" r="3" fill="#5300B7" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
                <p class="text-xs text-[#8E8E93]">Pilih pengalaman yang ingin kamu gunakan</p>
            </div>
        </div>

        <form method="POST" action="{{ route('auth.google.features') }}" class="space-y-4">
            @csrf

            <div class="space-y-3">
                <!-- LifeHub AI Card -->
                <label class="relative flex items-start p-4 rounded-2xl bg-[#111111] border-2 border-[#262626] cursor-pointer hover:border-[#3E3E3A] transition-all has-[:checked]:border-[#5300B7] has-[:checked]:bg-[#5300B7]/10 group">
                    <input type="radio" name="feature" value="lifehub" class="sr-only">
                    <div class="w-9 h-9 rounded-xl bg-[#18181B] border border-[#27272A] flex items-center justify-center text-[#A78BFA] group-hover:scale-105 transition-transform shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-3.5 flex-1 pr-6">
                        <div class="text-sm font-bold text-white flex items-center gap-2">
                            <span>LifeHub AI</span>
                        </div>
                        <p class="text-xs text-[#8E8E93] mt-1 leading-relaxed">
                            Digital workspace untuk AI, asisten cerdas, dan produktivitas harian.
                        </p>
                    </div>
                    <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 border-[#3E3E3A] flex items-center justify-center text-transparent transition-colors group-hover:border-[#5300B7] group-has-[:checked]:border-[#5300B7] group-has-[:checked]:bg-[#5300B7] group-has-[:checked]:text-white">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </label>

                <!-- Kasira Card -->
                <label class="relative flex items-start p-4 rounded-2xl bg-[#111111] border-2 border-[#262626] cursor-pointer hover:border-[#3E3E3A] transition-all has-[:checked]:border-[#5300B7] has-[:checked]:bg-[#5300B7]/10 group">
                    <input type="radio" name="feature" value="kasira" class="sr-only">
                    <div class="w-9 h-9 rounded-xl bg-[#18181B] border border-[#27272A] flex items-center justify-center text-[#A78BFA] group-hover:scale-105 transition-transform shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-3.5 flex-1 pr-6">
                        <div class="text-sm font-bold text-white flex items-center gap-2">
                            <span>Kasira</span>
                        </div>
                        <p class="text-xs text-[#8E8E93] mt-1 leading-relaxed">
                            Kelola toko, produk, stok, transaksi, kasir POS, dan laporan UMKM.
                        </p>
                    </div>
                    <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 border-[#3E3E3A] flex items-center justify-center text-transparent transition-colors group-hover:border-[#5300B7] group-has-[:checked]:border-[#5300B7] group-has-[:checked]:bg-[#5300B7] group-has-[:checked]:text-white">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </label>

                <!-- Both Card -->
                <label class="relative flex items-start p-4 rounded-2xl bg-[#111111] border-2 border-[#262626] cursor-pointer hover:border-[#3E3E3A] transition-all has-[:checked]:border-[#5300B7] has-[:checked]:bg-[#5300B7]/10 group">
                    <input type="radio" name="feature" value="both" checked class="sr-only">
                    <div class="w-9 h-9 rounded-xl bg-[#5300B7]/20 border border-[#5300B7]/40 flex items-center justify-center text-[#C4B5FD] group-hover:scale-105 transition-transform shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <div class="ml-3.5 flex-1 pr-6">
                        <div class="text-sm font-bold text-white flex items-center gap-2">
                            <span>LifeHub AI + Kasira</span>
                            <span class="px-1.5 py-0.5 text-[10px] font-semibold bg-[#5300B7] text-white rounded">Semua Fitur</span>
                        </div>
                        <p class="text-xs text-[#8E8E93] mt-1 leading-relaxed">
                            Gunakan keduanya dalam satu akun. Beralih antar workspace secara seamless.
                        </p>
                    </div>
                    <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 border-[#3E3E3A] flex items-center justify-center text-transparent transition-colors group-hover:border-[#5300B7] group-has-[:checked]:border-[#5300B7] group-has-[:checked]:bg-[#5300B7] group-has-[:checked]:text-white">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </label>
            </div>

            @error('feature')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror

            <button
                type="submit"
                class="w-full h-11 min-h-[44px] mt-6 rounded-full bg-[#5300B7] hover:bg-[#430095] text-white font-semibold text-sm transition-all duration-200 shadow-[0_2px_12px_rgba(83,0,183,0.35)] cursor-pointer active:scale-[0.99] flex items-center justify-center"
            >
                Lanjutkan ke Aplikasi
            </button>
        </form>
    </div>
</body>
</html>

