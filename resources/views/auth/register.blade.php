<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#111111]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LifeHub AI — Daftar Akun</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full min-h-[100dvh] bg-[#111111] text-[#FFFFFF] antialiased selection:bg-[#5300B7] selection:text-white font-sans overflow-x-hidden">

    <!-- Main Viewport Wrapper -->
    <main data-parallax-container class="w-full min-h-[100dvh] lg:h-[100dvh] flex flex-col lg:flex-row bg-[#111111] overflow-x-hidden lg:overflow-hidden">

        <!-- ==================== BAGIAN KIRI: Visual & Branding (~55%) ==================== -->
        <div class="relative w-full lg:w-[55%] xl:w-[57%] flex flex-col justify-between p-5 sm:p-8 lg:p-12 xl:p-16 z-10 shrink-0 lg:shrink lg:h-full overflow-hidden">

            <!-- Background Abstract 3D Metallic Curves -->
            <div class="absolute inset-0 pointer-events-none -z-10 overflow-hidden">
                <div data-parallax-depth="8" class="absolute top-[22%] left-[18%] w-60 h-60 rounded-full bg-[#5300B7]/10 blur-[90px]"></div>

                <div data-parallax-depth="20" class="absolute inset-0 flex items-center justify-center animate-float-slow opacity-85 sm:opacity-90">
                    <svg viewBox="0 0 700 800" class="w-full h-full max-h-[420px] sm:max-h-[520px] lg:max-h-none object-contain scale-95 sm:scale-105 lg:scale-120 select-none" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="reg-chrome-grad-1" x1="120" y1="80" x2="580" y2="720" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#FFFFFF" />
                                <stop offset="15%" stop-color="#D4D4D8" />
                                <stop offset="30%" stop-color="#52525B" />
                                <stop offset="45%" stop-color="#18181B" />
                                <stop offset="60%" stop-color="#E4E4E7" />
                                <stop offset="75%" stop-color="#3F3F46" />
                                <stop offset="85%" stop-color="#5300B7" stop-opacity="0.6" />
                                <stop offset="95%" stop-color="#F4F4F5" />
                                <stop offset="100%" stop-color="#09090B" />
                            </linearGradient>

                            <linearGradient id="reg-chrome-grad-2" x1="600" y1="150" x2="100" y2="750" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#A1A1AA" />
                                <stop offset="25%" stop-color="#111111" />
                                <stop offset="50%" stop-color="#FFFFFF" />
                                <stop offset="68%" stop-color="#3F3F46" />
                                <stop offset="85%" stop-color="#D4D4D8" />
                                <stop offset="100%" stop-color="#09090B" />
                            </linearGradient>

                            <filter id="reg-chrome-shadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="16" stdDeviation="20" flood-color="#000000" flood-opacity="0.75" />
                            </filter>
                        </defs>

                        <path d="M 160,140 C 60,200 40,380 90,520 C 130,640 220,700 310,640 C 400,580 430,420 370,300 C 310,180 180,180 170,320 C 160,460 270,550 350,560"
                              stroke="url(#reg-chrome-grad-1)"
                              stroke-width="50"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              filter="url(#reg-chrome-shadow)" />

                        <path d="M 380,120 C 460,90 560,140 590,260 C 620,400 540,560 460,650 C 400,720 320,750 300,680"
                              stroke="url(#reg-chrome-grad-2)"
                              stroke-width="46"
                              stroke-linecap="round"
                              filter="url(#reg-chrome-shadow)" />

                        <path d="M 155,145 C 70,205 50,375 95,510 C 132,625 215,685 300,630"
                              stroke="#FFFFFF"
                              stroke-width="6"
                              stroke-linecap="round"
                              opacity="0.7" />
                    </svg>
                </div>

                <div data-parallax-depth="12" class="absolute inset-0 flex items-center justify-center animate-float-reverse opacity-60">
                    <svg viewBox="0 0 700 800" class="w-full h-full max-h-[420px] sm:max-h-[520px] lg:max-h-none object-contain scale-90 sm:scale-100 lg:scale-115 select-none" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="reg-purple-chrome" x1="200" y1="200" x2="600" y2="700" gradientUnits="userSpaceOnUse">
                                <stop offset="0%" stop-color="#E4E4E7" />
                                <stop offset="40%" stop-color="#27272A" />
                                <stop offset="70%" stop-color="#5300B7" stop-opacity="0.5" />
                                <stop offset="100%" stop-color="#111111" />
                            </linearGradient>
                        </defs>
                        <path d="M 280,720 C 370,760 480,720 540,620 C 610,500 620,320 540,200"
                              stroke="url(#reg-purple-chrome)"
                              stroke-width="34"
                              stroke-linecap="round"
                              opacity="0.75" />
                    </svg>
                </div>
            </div>

            <!-- Top Row: Logo & Navigation -->
            <header class="relative z-20 w-full pt-[env(safe-area-inset-top)]">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#18181B] border border-[#27272A] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 48 48" fill="none" stroke="currentColor">
                                <path d="M24 6L38 14V34L24 42L10 34V14L24 6Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                                <path d="M10 14L24 22L38 14" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                                <path d="M24 22V42" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                                <circle cx="24" cy="22" r="3" fill="#5300B7" />
                            </svg>
                        </div>
                        <div>
                            <a href="{{ url('/') }}" class="inline-flex items-center gap-1 group">
                                <span class="text-xl sm:text-2xl font-bold tracking-tight text-white group-hover:text-[#F4F4F5] transition-colors">
                                    LifeHub<span class="text-[#5300B7]">.</span>ai
                                </span>
                            </a>
                            <p class="text-[11px] sm:text-xs text-[#8E8E93] tracking-wide">
                                Your intelligent digital workspace.
                            </p>
                        </div>
                    </div>

                    <nav class="hidden md:flex items-center gap-6 lg:gap-8 text-xs sm:text-sm font-medium tracking-wider text-[#8E8E93] uppercase">
                        <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
                        <a href="#about" class="hover:text-white transition-colors">About Us</a>
                        <a href="#contact" class="hover:text-white transition-colors">Contact</a>
                        <a href="{{ route('login') }}" class="hover:text-white transition-colors">Log In</a>
                    </nav>

                    <button
                        type="button"
                        data-mobile-menu-btn
                        aria-expanded="false"
                        aria-label="Toggle navigation menu"
                        class="md:hidden flex items-center justify-center w-11 h-11 rounded-full bg-[#18181B] border border-[#27272A] text-white active:scale-95 transition-all"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <div data-mobile-menu class="hidden md:hidden mt-3 p-4 rounded-2xl bg-[#18181B] border border-[#27272A] shadow-2xl space-y-2">
                    <a href="{{ url('/') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#8E8E93] hover:text-white hover:bg-[#27272A]/50 transition-colors">Home</a>
                    <a href="#about" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#8E8E93] hover:text-white hover:bg-[#27272A]/50 transition-colors">About Us</a>
                    <a href="#contact" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#8E8E93] hover:text-white hover:bg-[#27272A]/50 transition-colors">Contact</a>
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-white">Log In</a>
                </div>
            </header>

            <!-- Bottom Left: Headline & Badges -->
            <div class="relative z-20 mt-8 sm:mt-12 lg:mt-auto pt-4 sm:pt-10 lg:pt-0">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.1] mb-2 sm:mb-3">
                    Welcome
                </h1>

                <p class="text-[#8E8E93] text-xs sm:text-sm lg:text-base max-w-md mb-4 sm:mb-6 leading-relaxed">
                    Lanjutkan perjalanan produktivitasmu bersama LifeHub AI.
                </p>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-medium bg-[#18181B]/90 border border-[#27272A] text-white backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
                        AI Chat
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-medium bg-[#18181B]/90 border border-[#27272A] text-white backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#71717A]"></span>
                        Music
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-medium bg-[#18181B]/90 border border-[#27272A] text-white backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#5300B7]"></span>
                        Tools
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-[11px] sm:text-xs font-medium bg-[#18181B]/50 border border-[#27272A]/70 text-[#8E8E93]">
                        More &rarr;
                    </span>
                </div>
            </div>
        </div>

        <!-- ==================== BAGIAN KANAN: Panel Register (~45%) ==================== -->
        <div class="relative w-full lg:w-[45%] xl:w-[43%] bg-[#171717] border-t lg:border-t-0 lg:border-l border-[#262626] rounded-t-[32px] sm:rounded-t-[40px] lg:rounded-t-none lg:rounded-l-[48px] xl:rounded-l-[56px] p-5 sm:p-8 md:p-10 lg:p-10 xl:p-14 flex flex-col justify-center lg:h-full overflow-y-auto z-20 shadow-[-15px_0_45px_rgba(0,0,0,0.65)] pb-[max(2rem,env(safe-area-inset-bottom))]">

            <div class="w-full max-w-sm sm:max-w-md mx-auto my-auto py-2">
                <div class="mb-5 sm:mb-6">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-1.5">
                        LifeHub AI
                    </h2>
                    <p class="text-[#8E8E93] text-xs sm:text-sm">
                        Buat akun untuk memulai
                    </p>
                </div>

                <!-- Google Button -->
                <a href="{{ route('auth.google') }}" class="w-full h-11 min-h-[44px] flex items-center justify-center gap-3 px-4 rounded-full bg-[#111111] border border-[#262626] hover:border-[#52525B] text-white font-medium text-sm transition-all duration-200 shadow-sm group active:scale-[0.99] touch-manipulation">
                    <svg class="w-5 h-5 shrink-0 transition-transform group-hover:scale-105" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                    <span>Lanjutkan dengan Google</span>
                </a>

                <div class="relative my-4 sm:my-5 flex items-center justify-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#262626]"></div>
                    </div>
                    <span class="relative px-3 bg-[#171717] text-[11px] font-semibold text-[#8E8E93] tracking-widest uppercase select-none">
                        ATAU
                    </span>
                </div>

                <!-- Registration Form with Loading State -->
                <form method="POST" action="{{ route('register') }}" data-auth-form class="space-y-3 sm:space-y-3.5">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#8E8E93]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Nama Lengkap"
                                class="w-full h-11 min-h-[44px] pl-11 pr-4 rounded-full bg-[#111111] border border-[#262626] focus:border-[#5300B7] focus:ring-1 focus:ring-[#5300B7]/40 focus:bg-[#0D0D0D] focus:outline-none text-sm text-white placeholder-[#8E8E93]/60 transition-all duration-200 shadow-none touch-manipulation @error('name') border-red-500 @enderror"
                            >
                        </div>
                        @error('name')
                            <p class="text-xs text-red-400 mt-1 pl-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#8E8E93]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autocomplete="username"
                                placeholder="Username"
                                class="w-full h-11 min-h-[44px] pl-11 pr-4 rounded-full bg-[#111111] border border-[#262626] focus:border-[#5300B7] focus:ring-1 focus:ring-[#5300B7]/40 focus:bg-[#0D0D0D] focus:outline-none text-sm text-white placeholder-[#8E8E93]/60 transition-all duration-200 shadow-none touch-manipulation @error('username') border-red-500 @enderror"
                            >
                        </div>
                        @error('username')
                            <p class="text-xs text-red-400 mt-1 pl-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#8E8E93]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="Email"
                                class="w-full h-11 min-h-[44px] pl-11 pr-4 rounded-full bg-[#111111] border border-[#262626] focus:border-[#5300B7] focus:ring-1 focus:ring-[#5300B7]/40 focus:bg-[#0D0D0D] focus:outline-none text-sm text-white placeholder-[#8E8E93]/60 transition-all duration-200 shadow-none touch-manipulation @error('email') border-red-500 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="text-xs text-red-400 mt-1 pl-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#8E8E93]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="reg_password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Password"
                                class="w-full h-11 min-h-[44px] pl-11 pr-12 rounded-full bg-[#111111] border border-[#262626] focus:border-[#5300B7] focus:ring-1 focus:ring-[#5300B7]/40 focus:bg-[#0D0D0D] focus:outline-none text-sm text-white placeholder-[#8E8E93]/60 transition-all duration-200 shadow-none touch-manipulation @error('password') border-red-500 @enderror"
                            >
                            <button
                                type="button"
                                data-toggle-password="reg_password"
                                class="absolute inset-y-0 right-0 w-11 h-11 flex items-center justify-center text-[#8E8E93] hover:text-white transition-colors focus:outline-none cursor-pointer touch-manipulation"
                                aria-label="Toggle password visibility"
                            >
                                <svg class="w-4 h-4 icon-eye-off hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                                <svg class="w-4 h-4 icon-eye" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-400 mt-1 pl-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#8E8E93]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Konfirmasi Password"
                                class="w-full h-11 min-h-[44px] pl-11 pr-12 rounded-full bg-[#111111] border border-[#262626] focus:border-[#5300B7] focus:ring-1 focus:ring-[#5300B7]/40 focus:bg-[#0D0D0D] focus:outline-none text-sm text-white placeholder-[#8E8E93]/60 transition-all duration-200 shadow-none touch-manipulation"
                            >
                            <button
                                type="button"
                                data-toggle-password="password_confirmation"
                                class="absolute inset-y-0 right-0 w-11 h-11 flex items-center justify-center text-[#8E8E93] hover:text-white transition-colors focus:outline-none cursor-pointer touch-manipulation"
                                aria-label="Toggle password confirmation visibility"
                            >
                                <svg class="w-4 h-4 icon-eye-off hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                                <svg class="w-4 h-4 icon-eye" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Pilih Produk -->
                    <div class="pt-2">
                        <div class="mb-2">
                            <label class="block text-xs font-bold text-white tracking-wide">
                                Pilih produk yang ingin digunakan <span class="text-[#5300B7]">*</span>
                            </label>
                            <p class="text-[11px] text-[#8E8E93]">
                                Setiap akun didedikasikan untuk 1 produk pilihan
                            </p>
                        </div>

                        <div class="space-y-2.5">
                            <!-- Option 1: Kasira -->
                            <label class="relative flex items-center p-3 rounded-2xl bg-[#111111] border-2 border-[#262626] cursor-pointer hover:border-[#3E3E3A] transition-all has-[:checked]:border-[#5300B7] has-[:checked]:bg-[#5300B7]/10 group touch-manipulation">
                                <input
                                    type="radio"
                                    name="product"
                                    value="kasira"
                                    {{ old('product', old('feature', 'kasira')) === 'kasira' ? 'checked' : '' }}
                                    class="sr-only"
                                    required
                                >
                                <div class="w-8 h-8 rounded-xl bg-[#18181B] border border-[#27272A] flex items-center justify-center text-[#A78BFA] group-hover:scale-105 transition-transform shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1 pr-6">
                                    <div class="text-xs font-bold text-white flex items-center gap-1.5 group-hover:text-[#F4F4F5]">
                                        <span>Kasira</span>
                                        <span class="px-1.5 py-0.2 text-[9px] font-bold bg-[#5300B7] text-white rounded">POS & Toko</span>
                                    </div>
                                    <p class="text-[11px] text-[#8E8E93] leading-tight mt-0.5">
                                        Kelola toko, produk, stok, transaksi, dan kasir.
                                    </p>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-[#3E3E3A] flex items-center justify-center text-transparent transition-colors group-hover:border-[#5300B7] group-has-[:checked]:border-[#5300B7] group-has-[:checked]:bg-[#5300B7] group-has-[:checked]:text-white shrink-0">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>

                            <!-- Option 2: LifeHub AI -->
                            <label class="relative flex items-center p-3 rounded-2xl bg-[#111111] border-2 border-[#262626] cursor-pointer hover:border-[#3E3E3A] transition-all has-[:checked]:border-[#5300B7] has-[:checked]:bg-[#5300B7]/10 group touch-manipulation">
                                <input
                                    type="radio"
                                    name="product"
                                    value="lifehub"
                                    {{ old('product', old('feature')) === 'lifehub' ? 'checked' : '' }}
                                    class="sr-only"
                                >
                                <div class="w-8 h-8 rounded-xl bg-[#18181B] border border-[#27272A] flex items-center justify-center text-[#A78BFA] group-hover:scale-105 transition-transform shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1 pr-6">
                                    <div class="text-xs font-bold text-white group-hover:text-[#F4F4F5]">
                                        LifeHub AI
                                    </div>
                                    <p class="text-[11px] text-[#8E8E93] leading-tight mt-0.5">
                                        Digital workspace untuk AI dan produktivitas.
                                    </p>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-[#3E3E3A] flex items-center justify-center text-transparent transition-colors group-hover:border-[#5300B7] group-has-[:checked]:border-[#5300B7] group-has-[:checked]:bg-[#5300B7] group-has-[:checked]:text-white shrink-0">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>
                        </div>
                        @error('product')
                            <p class="text-xs text-red-400 mt-1 pl-1">{{ $message }}</p>
                        @enderror
                        @error('feature')
                            <p class="text-xs text-red-400 mt-1 pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button "Daftar" (LifeHub Purple #5300B7) -->
                    <button
                        type="submit"
                        class="w-full h-11 min-h-[44px] mt-2 rounded-full bg-[#5300B7] hover:bg-[#430095] text-white font-semibold text-sm transition-all duration-200 shadow-[0_2px_12px_rgba(83,0,183,0.35)] hover:shadow-[0_4px_20px_rgba(83,0,183,0.5)] cursor-pointer active:scale-[0.99] touch-manipulation flex items-center justify-center"
                    >
                        <span class="btn-text">Daftar</span>
                        <span class="btn-spinner hidden flex items-center justify-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memproses...</span>
                        </span>
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <p class="text-xs text-[#8E8E93]">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-white hover:text-[#5300B7] hover:underline ml-1 transition-colors py-1 inline-block">
                            Masuk sekarang
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
