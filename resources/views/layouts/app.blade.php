<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#0E0E10]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.seo')
    @include('partials.schema')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full min-h-[100dvh] bg-[#0E0E10] text-[#FFFFFF] antialiased selection:bg-[#5300B7] selection:text-white font-sans flex flex-col justify-between">

    <!-- Top Navigation Header -->
    <header class="w-full bg-[#141417]/80 backdrop-blur-md border-b border-[#222227] sticky top-0 z-30 px-4 sm:px-8 py-3.5">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-lg bg-[#1D1D22] border border-[#2B2B33] flex items-center justify-center text-white shadow-xs">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 48 48" fill="none" stroke="currentColor">
                        <path d="M24 6L38 14V34L24 42L10 34V14L24 6Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                        <path d="M10 14L24 22L38 14" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                        <path d="M24 22V42" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                        <circle cx="24" cy="22" r="3" fill="#5300B7" />
                    </svg>
                </div>
                <span class="text-base sm:text-lg font-bold tracking-tight text-white group-hover:text-[#F4F4F5] transition">
                    LifeHub<span class="text-[#5300B7]">.</span>ai
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="flex items-center gap-2 sm:gap-4 text-xs font-semibold">
                <a href="{{ url('/') }}"
                   class="px-3 py-1.5 rounded-lg text-[#8E8E93] hover:text-white hover:bg-[#1E1E24] transition {{ request()->is('/') ? 'text-white bg-[#1E1E24]' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('about') }}"
                   class="px-3 py-1.5 rounded-lg text-[#8E8E93] hover:text-white hover:bg-[#1E1E24] transition {{ request()->routeIs('about') ? 'text-white bg-[#1E1E24]' : '' }}">
                    Tentang Developer
                </a>

                @auth
                    @if(auth()->user()->hasKasira())
                        <a href="{{ route('kasira.dashboard') }}"
                           class="px-3 py-1.5 rounded-lg bg-[#5300B7] hover:bg-[#430095] text-white shadow-xs transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('lifehub.dashboard') }}"
                           class="px-3 py-1.5 rounded-lg bg-[#5300B7] hover:bg-[#430095] text-white shadow-xs transition">
                            Workspace
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="px-3 py-1.5 rounded-lg text-[#8E8E93] hover:text-white transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-3.5 py-1.5 rounded-lg bg-[#5300B7] hover:bg-[#430095] text-white shadow-xs transition hidden sm:inline-block">
                        Daftar Akun
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full bg-[#111114] border-t border-[#222227] px-4 sm:px-8 py-8 mt-12 text-xs text-[#8E8E93]">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-white">LifeHub AI Workspace</span>
                <span>&bull;</span>
                <span>&copy; {{ date('Y') }} All rights reserved.</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="hover:text-white transition">Beranda</a>
                <a href="{{ route('about') }}" class="hover:text-white transition">Tentang Developer</a>
                <a href="{{ route('login') }}" class="hover:text-white transition">Masuk</a>
                <a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

