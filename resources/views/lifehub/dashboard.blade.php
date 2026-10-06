<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Apply theme BEFORE paint to prevent flash of wrong theme --}}
    <script>
        (function(){
            const t = localStorage.getItem('lifehub_theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    {{-- Decide the entrance strategy BEFORE first paint so the page never double-animates --}}
    <script>
        (function () {
            try {
                var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                var seen = sessionStorage.getItem('lifehub_workspace_intro_seen');
                if (!reduced && !seen) {
                    // The workspace intro owns this entrance.
                    document.documentElement.classList.add('js-intro-pending');
                } else {
                    document.documentElement.classList.add('page-transition-ready');
                }
            } catch (e) {
                document.documentElement.classList.add('page-transition-ready');
            }
        })();
    </script>

    @include('partials.seo', [
        'productName' => 'NEXUS AI OFFICE',
        'tagline' => 'All-in-One AI & Digital Workspace',
        'defaultDescription' => 'NEXUS AI OFFICE adalah workspace digital all-in-one dengan asisten AI, pemutar musik, dan alat produktivitas terintegrasi.',
    ])
    @include('partials.schema')

    <!-- Official NEXUS AI OFFICE Favicon using New Logo Symbol -->
    <link rel="icon" type="image/png" href="{{ asset('images/lifehub-icon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Smooth clean scrollbars */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--lh-scrollbar); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--lh-scrollbar-hover); }

        .dashboard-shell {
            background-color: var(--lh-bg-shell);
            background-image:
                radial-gradient(at 0% 0%, var(--lh-ambient-1) 0px, transparent 45%),
                radial-gradient(at 100% 100%, var(--lh-ambient-2) 0px, transparent 50%),
                radial-gradient(at 80% 20%, var(--lh-ambient-3) 0px, transparent 40%),
                radial-gradient(at 20% 80%, var(--lh-ambient-2) 0px, transparent 55%);
            background-attachment: fixed;
        }
        .dark .dashboard-shell {
            background-image:
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.12) 0px, transparent 45%),
                radial-gradient(at 100% 100%, rgba(12, 15, 30, 0.9) 0px, transparent 55%),
                radial-gradient(at 80% 20%, rgba(139, 92, 246, 0.07) 0px, transparent 40%),
                radial-gradient(at 20% 80%, rgba(99, 102, 241, 0.05) 0px, transparent 55%);
        }
        /* Cinematic vignette for the dark workspace */
        .dark-vignette {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background: radial-gradient(ellipse at 50% 0%, transparent 30%, rgba(0, 0, 0, 0.5) 100%);
        }

        .safe-bottom-space {
            padding-bottom: calc(7.5rem + env(safe-area-inset-bottom, 0px));
        }
        @media (min-width: 1024px) {
            .safe-bottom-space {
                padding-bottom: 6rem;
            }
        }

        /* Scroll reveal styling */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.5s cubic-bezier(0.22, 1, 0.36, 1), transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="h-full min-h-[100dvh] dashboard-shell text-slate-800 dark:text-slate-200 antialiased selection:bg-violet-500/20 selection:text-violet-900 dark:selection:text-violet-200 font-sans flex flex-col lg:flex-row overflow-x-hidden relative">

    <!-- ============================================================
         PREMIUM WORKSPACE REVEAL OPENING OVERLAY
         (Stages 1 to 3 with Official New NEXUS AI OFFICE Logo)
         ============================================================ -->
    <div id="workspace-intro-overlay" class="flex items-center justify-center">
        <div id="intro-center-stage">
            <!-- Stage 2: Ambient Bloom & Geometric Rings Behind Logo -->
            <div id="intro-bloom-circle" class="intro-bloom"></div>

            <!-- Stage 1 & 2: Central Official Logo Reveal -->
            <div id="intro-logo-container" class="intro-logo-box flex flex-col items-center gap-4 px-6 text-center">
                <div class="relative flex items-center justify-center">
                    <img src="{{ asset('images/lifehub-logo.png') }}"
                         alt="NEXUS AI OFFICE"
                         class="h-14 sm:h-18 w-auto object-contain select-none pointer-events-none block">
                </div>
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-100/70 dark:bg-violet-900/30 border border-violet-200/80 dark:border-violet-700/40 text-violet-700 dark:text-violet-300 text-xs font-semibold tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-violet-600 dark:bg-violet-400 animate-pulse"></span>
                        <span>All-in-One AI &amp; Digital Workspace</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         LAYERED AMBIENT DEPTH (Violet Light Fields + Architectural Forms)
         Abstract, technical, masculine — Non-Floral, Non-Cyberpunk
         ============================================================ -->
    <div class="dark-vignette hidden dark:block"></div>
    <div class="ambient-field ambient-field-a"></div>
    <div class="ambient-field ambient-field-b"></div>
    <div class="ambient-field ambient-field-c"></div>

    <!-- Top Right: Abstract Architectural Arcs -->
    <div class="arch-form -top-24 -right-24 w-[420px] h-[420px] opacity-70 hidden lg:block">
        <svg viewBox="0 0 400 400" fill="none" class="w-full h-full">
            <path class="arch-form-ring" stroke-dasharray="3 7" d="M 400 40 A 250 250 0 0 0 150 290" />
            <path class="arch-form-ring" d="M 400 110 A 190 190 0 0 0 210 300" />
            <path class="arch-form-ring" stroke-dasharray="6 9" d="M 400 180 A 130 130 0 0 0 270 310" />
            <path class="arch-form-ring" d="M 400 250 A 70 70 0 0 0 330 320" />
            <circle class="arch-form-fill" cx="150" cy="290" r="3" />
            <circle class="arch-form-fill" cx="270" cy="310" r="2.5" />
        </svg>
    </div>

    <!-- Bottom Left Architectural Curves Ambient -->
    <div class="arch-form fixed bottom-0 left-0 w-96 h-96 overflow-hidden opacity-60 hidden lg:block">
        <svg viewBox="0 0 400 400" fill="none" class="w-full h-full animate-ambient-drift">
            <circle class="arch-form-ring" stroke-dasharray="4 8" cx="60" cy="380" r="160" />
            <circle class="arch-form-ring" cx="60" cy="380" r="230" />
            <circle class="arch-form-ring" stroke-dasharray="6 10" cx="60" cy="380" r="300" />
            <path class="arch-form-ring" stroke-dasharray="4 6" d="M 60 380 Q 200 240 340 280" />
            <path class="arch-form-ring" d="M 60 380 Q 150 180 300 150" />
            <circle class="arch-form-fill" cx="340" cy="280" r="3.5" />
            <circle class="arch-form-fill" cx="300" cy="150" r="4" />
        </svg>
    </div>

    <!-- Bottom Right Flowing Translucent Form -->
    <div class="arch-form -bottom-16 -right-10 w-[420px] h-[340px] opacity-60 hidden lg:block">
        <svg viewBox="0 0 400 340" fill="none" class="w-full h-full">
            <path d="M 0 340 Q 110 190 250 235 T 400 120 L 400 340 Z" fill="var(--lh-ambient-2)" />
            <path class="arch-form-ring" d="M 0 300 Q 120 160 260 205 T 400 95" />
            <path class="arch-form-ring" stroke-dasharray="4 8" d="M 0 330 Q 120 195 265 238 T 400 135" />
        </svg>
    </div>

    <!-- ============================================================
         DESKTOP SIDEBAR (Collapsible: Expanded & Collapsed Modes)
         ============================================================ -->
    <aside id="desktop-sidebar"
           class="sidebar-desktop anim-sidebar hidden lg:flex lg:flex-col w-64 shrink-0 z-30 sticky top-0 h-screen select-none border-r"
           style="background-color: var(--lh-sidebar-bg); border-color: var(--lh-border); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); box-shadow: 1px 0 0 0 var(--lh-card-inset), 12px 0 40px -28px rgba(0,0,0,0.28);">

        <!-- Brand Header & Collapse Toggle -->
        <div class="px-3 h-16 flex items-center gap-1 border-b shrink-0" style="border-color: var(--lh-border-subtle);">
            <a href="{{ route('lifehub.dashboard') }}" class="flex items-center min-w-0 flex-1 group rounded-lg" title="NEXUS AI OFFICE">
                <!-- Expanded: Full Official Logo -->
                <div class="sidebar-label flex items-center overflow-hidden pl-1">
                    <img src="{{ asset('images/lifehub-logo.png') }}"
                         alt="NEXUS AI OFFICE"
                         class="h-[26px] w-auto object-contain object-left block transition-opacity group-hover:opacity-80">
                </div>
                <!-- Collapsed: Symbol Icon Only -->
                <div class="hidden sidebar-collapsed-icon items-center justify-center w-full">
                    <img src="{{ asset('images/lifehub-icon.png') }}"
                         alt="NEXUS AI OFFICE"
                         class="w-[22px] h-[22px] object-contain block transition-transform group-hover:scale-105">
                </div>
            </a>

            <!-- Collapse Toggle Button -->
            <button type="button"
                    onclick="toggleSidebar()"
                    id="sidebar-toggle-btn"
                    title="Kecilkan / Besarkan Menu Sidebar"
                    class="sidebar-hide-on-collapse w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-900/[0.05] dark:hover:bg-white/[0.06] transition btn-press shrink-0">
                <svg id="sidebar-toggle-icon" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="sidebar-scroll flex-1 min-h-0 overflow-y-auto overflow-x-hidden px-3 py-4 flex flex-col" aria-label="Sidebar Navigation">

            <!-- GROUP 1: Workspace -->
            <div class="sidebar-group">
                <span class="sidebar-group-label sidebar-label">Ruang Kerja</span>

                <a href="{{ route('lifehub.dashboard') }}"
                   data-label="Beranda"
                   aria-current="page"
                   class="sidebar-item is-active">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="sidebar-label truncate">Beranda</span>
                </a>
            </div>

            <!-- GROUP 2: Modules -->
            <div class="sidebar-group">
                <span class="sidebar-group-label sidebar-label">Modul</span>

                <a href="{{ route('lifehub.music') }}"
                   data-label="Music"
                   class="sidebar-item">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                    <span class="sidebar-label truncate">Music</span>
                    <span class="nav-dot" aria-hidden="true"></span>
                </a>

                <button type="button"
                        data-label="AI Assistant"
                        onclick="showFeatureModal('AI Assistant', 'Asisten cerdas berbasis AI untuk diskusi, riset materi, analisis data, dan tanya jawab instan.')"
                        title="AI Assistant"
                        class="sidebar-item">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="sidebar-label truncate">AI Assistant</span>
                    <span class="nav-dot" aria-hidden="true"></span>
                </button>

                <button type="button"
                        data-label="Downloader"
                        onclick="showFeatureModal('Downloader', 'Alat pengunduh media digital, video, dan konten multi-platform.')"
                        title="Downloader"
                        class="sidebar-item">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span class="sidebar-label truncate">Downloader</span>
                    <span class="nav-dot" aria-hidden="true"></span>
                </button>
            </div>

            <!-- GROUP 3: Account — pinned to the bottom of the rail -->
            <div class="sidebar-group sidebar-group-pinned">
                <span class="sidebar-group-label sidebar-label">Akun</span>

                <button type="button"
                        data-label="Pengaturan Profil"
                        onclick="openProfileModal()"
                        title="Pengaturan Profil"
                        class="sidebar-item">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="sidebar-label truncate">Pengaturan Profil</span>
                </button>

                <form action="{{ route('logout') }}" method="POST" class="border-t mt-3 pt-3" style="border-color: var(--lh-border-subtle);">
                    @csrf
                    <button type="submit"
                            data-label="Keluar"
                            title="Keluar"
                            class="sidebar-item is-danger">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="sidebar-label truncate">Keluar</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- ============================================================
         MAIN CONTENT WRAPPER (Fullscreen & Responsive)
         ============================================================ -->
    <div class="flex-1 flex flex-col min-w-0 min-h-[100dvh] dashboard-shell safe-bottom-space z-10">

        <!-- ==================== TOPBAR HEADER ==================== -->
        <header class="anim-header h-16 border-b sticky top-0 z-20 px-4 sm:px-8 flex items-center justify-between gap-3 sm:gap-4"
                style="background-color: var(--lh-header-bg); border-color: var(--lh-border-subtle); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
            <!-- Mobile Brand Logo (Using Official New Logo) -->
            <div class="flex items-center lg:hidden shrink-0">
                <a href="{{ route('lifehub.dashboard') }}" class="flex items-center group" title="NEXUS AI OFFICE">
                    <img src="{{ asset('images/lifehub-logo.png') }}"
                         alt="NEXUS AI OFFICE"
                         class="h-6 sm:h-7 max-w-[120px] w-auto object-contain block">
                </a>
            </div>

            <!-- Mobile Search Icon (opens nothing destructive, focuses a compact field) -->
            <button type="button"
                    id="mobile-search-trigger"
                    class="lg:hidden shrink-0 p-2 rounded-full text-slate-400 dark:text-slate-500 transition btn-press"
                    style="background-color: var(--lh-surface-secondary); border: 1px solid var(--lh-border);"
                    aria-label="Cari"
                    aria-expanded="false"
                    aria-controls="mobile-search-field">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>

            <!-- Mobile Compact Search Field (hidden by default) -->
            <div id="mobile-search-field" class="lg:hidden hidden flex-1 min-w-0">
                <input type="text"
                       placeholder="Cari fitur atau alat..."
                       class="w-full pl-3 pr-3 py-2 rounded-xl text-xs outline-none transition"
                       style="background-color: var(--lh-surface-secondary); border: 1px solid var(--lh-border); color: var(--lh-text-primary);">
            </div>

            <!-- Desktop Search Bar (Rounded Pill with Icon & Shortcut) -->
            <div class="hidden lg:flex items-center flex-1 max-w-md">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           placeholder="Cari fitur, alat, atau navigasi cepat..."
                           class="w-full pl-9 pr-14 py-2 rounded-2xl text-xs outline-none transition"
                           style="background-color: var(--lh-surface-secondary); border: 1px solid var(--lh-border); color: var(--lh-text-primary);">
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                        <kbd class="px-1.5 py-0.5 text-[10px] font-mono text-slate-400 rounded shadow-2xs"
                             style="background-color: var(--lh-surface); border: 1px solid var(--lh-border);">Ctrl K</kbd>
                    </div>
                </div>
            </div>

            <!-- Topbar Right Profile & Switcher Actions -->
            <div class="flex items-center gap-2 sm:gap-3 ml-auto">
                <!-- Theme Toggle Button -->
                <button type="button" onclick="toggleTheme()" title="Ganti Tema" class="p-2 rounded-full text-slate-400 dark:text-slate-500 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-slate-100/70 dark:hover:bg-white/5 transition btn-press" aria-label="Toggle Theme">
                    <!-- Sun icon (visible in dark mode) -->
                    <svg class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon (visible in light mode) -->
                    <svg class="w-4 h-4 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Workspace Status Pill -->
                <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-50 dark:bg-violet-900/30 border border-violet-200/80 dark:border-violet-700/40 text-violet-600 dark:text-violet-300 text-[11px] font-semibold shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-violet-500 dark:bg-violet-400 animate-pulse"></span>
                    <span>Active Workspace</span>
                </div>

                <!-- Kasira Switcher if applicable -->
                @if($user->hasKasira())
                    <a href="{{ route('kasira.dashboard') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-violet-50 hover:bg-violet-100/70 border border-violet-200/80 text-xs font-semibold text-violet-700 transition btn-press shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Kasira POS</span>
                    </a>
                @endif

                <!-- User Profile Trigger Pill Button -->
                <button type="button"
                        onclick="openProfileModal()"
                        class="flex items-center gap-2 p-1 sm:px-2.5 sm:py-1 rounded-full transition group btn-press"
                        style="background-color: var(--lh-surface-secondary); border: 1px solid var(--lh-border);"
                        title="Pengaturan Profil">
                    <div class="relative w-7 h-7 sm:w-8 sm:h-8 rounded-full overflow-hidden border border-violet-300/80 dark:border-violet-600/40 bg-violet-100 dark:bg-violet-900/30 shrink-0 shadow-2xs flex items-center justify-center font-bold text-violet-700 dark:text-violet-300 text-xs">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div class="hidden sm:block text-left pr-0.5">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block leading-tight max-w-[130px] truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                            {{ $user->name }}
                        </span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-violet-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="max-w-6xl mx-auto w-full px-4 sm:px-8 mt-4">
                <div class="p-3.5 rounded-2xl bg-violet-50 border border-violet-200/80 text-xs font-semibold text-violet-700 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-violet-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-violet-400 hover:text-violet-700 font-bold">&times;</button>
                </div>
            </div>
        @endif

        <!-- ==================== MAIN DASHBOARD BODY ==================== -->
        <main class="page-transition-target flex-1 max-w-6xl mx-auto w-full px-4 sm:px-8 py-5 sm:py-7 space-y-6 sm:space-y-8">

            <!-- ==================== 1. HERO — DEVELOPER WORKSPACE ==================== -->
            <div class="anim-hero hero-shell relative rounded-3xl overflow-hidden">
                <!-- Architectural field: blueprint grid + one violet light source (no stock photography) -->
                <div class="hero-blueprint"></div>
                <div class="hero-field"></div>
                <div class="absolute inset-x-0 top-0 h-px pointer-events-none"
                     style="background: linear-gradient(90deg, transparent, rgba(139,92,246,0.45) 35%, rgba(99,102,241,0.28) 70%, transparent);"></div>

                <div class="relative z-10 p-5 sm:p-7 lg:p-9 flex flex-col lg:flex-row lg:items-center gap-7 xl:gap-10">
                    <!-- Left: identity, greeting, live clock -->
                    <div class="flex items-start gap-4 sm:gap-5 lg:gap-6 w-full lg:w-auto lg:flex-1 min-w-0">
                        <!-- User avatar (click to edit profile) -->
                        <div onclick="openProfileModal()"
                             class="anim-hero-avatar relative w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-2xl overflow-hidden shrink-0 bg-violet-100 dark:bg-violet-950/60 border border-violet-300/60 dark:border-violet-500/30 shadow-[0_10px_28px_-14px_rgba(109,40,217,0.55)] group cursor-pointer btn-press">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-extrabold text-2xl sm:text-3xl text-violet-600 dark:text-violet-300 bg-violet-50 dark:bg-violet-950/50">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-violet-950/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="min-w-0 flex-1 space-y-2.5">
                            <!-- Brand eyebrow -->
                            <span class="anim-hero-brand hero-eyebrow">Nexus AI Office</span>

                            <!-- Time-aware greeting badge -->
                            <div class="anim-hero-greeting inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-violet-700 dark:text-violet-100 text-[11px] font-bold w-fit border border-violet-200 bg-violet-50 dark:bg-violet-500/15 dark:border-violet-400/30">
                                <span class="text-violet-500 dark:text-violet-300" aria-hidden="true">&#10022;</span>
                                <span id="hero-greeting">Selamat malam,</span>
                            </div>

                            <h1 class="anim-hero-name text-2xl sm:text-3xl xl:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-[1.1] truncate">
                                {{ $user->name }}
                            </h1>

                            <p class="anim-hero-desc text-xs sm:text-sm text-slate-600 dark:text-slate-300/90 max-w-md leading-relaxed line-clamp-2 sm:line-clamp-none">
                                Ruang kerja pribadi Anda: asisten AI, pemutar musik, dan pengunduh dalam satu antarmuka.
                            </p>

                            <!-- Live date & clock (real device time) -->
                            <div class="anim-hero-chips flex flex-wrap items-center gap-2 pt-0.5">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-300 text-[10px] sm:text-xs font-medium border border-slate-900/10 dark:border-white/10 bg-slate-900/[0.03] dark:bg-white/5">
                                    <svg class="w-3.5 h-3.5 text-violet-500 dark:text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span id="hero-live-date">{{ now()->translatedFormat('l, d F Y') }}</span>
                                </div>

                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-slate-900 dark:text-white text-[10px] sm:text-xs font-mono font-bold tracking-wider border border-violet-200 dark:border-violet-400/25 bg-violet-50 dark:bg-violet-500/10">
                                    <svg class="w-3.5 h-3.5 text-violet-500 dark:text-violet-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="hero-live-clock">{{ now()->format('H:i:s') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: technology showcase — the real stack that builds NEXUS -->
                    <div class="anim-hero-quote w-full lg:w-[21rem] xl:w-[23rem] shrink-0">
                        <div class="stack-panel">
                            <div class="stack-head">
                                <span class="stack-eyebrow">Technology Stack</span>
                                <h2 class="stack-title">Dibangun dengan</h2>
                            </div>

                            <!--
                                Official brand marks from Simple Icons (CC0-1.0), vendored into
                                public/images/tech so the page needs no runtime CDN. Rendered
                                monochrome through a mask so six logos read as one system
                                instead of a colour clash.
                            -->
                            <ul class="stack-grid">
                                @foreach([
                                    ['slug' => 'laravel',     'label' => 'Laravel',    'version' => '13'],
                                    ['slug' => 'php',        'label' => 'PHP',        'version' => '8.3'],
                                    ['slug' => 'tailwindcss', 'label' => 'Tailwind',   'version' => 'v4'],
                                    ['slug' => 'vite',       'label' => 'Vite',       'version' => '8'],
                                    ['slug' => 'sqlite',     'label' => 'SQLite',     'version' => 'File'],
                                    ['slug' => 'javascript', 'label' => 'JavaScript', 'version' => 'ES6+'],
                                ] as $tech)
                                    <li class="stack-item" style="--tech-mask: url('{{ asset('images/tech/'.$tech['slug'].'.svg') }}');">
                                        <span class="stack-mark" aria-hidden="true"></span>
                                        <span class="stack-meta">
                                            <span class="stack-name">{{ $tech['label'] }}</span>
                                            <span class="stack-version">{{ $tech['version'] }}</span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            <p class="stack-foot">Fondasi teknis yang menjalankan workspace ini.</p>
                        </div>
                    </div>
                </div>

                <!-- ======== NEXUS ORB — Continuous Living Animation ======== -->
                <div class="nexus-orb-wrapper absolute bottom-4 right-4 sm:bottom-6 sm:right-6 z-20 hidden sm:block" title="Nexus AI Office — Active">
                    <div class="nexus-orb cursor-default"></div>
                </div>
            </div>

            <!-- ==================== 2. QUICK ACTIONS (3 CORE FEATURES) ==================== -->
            <div class="space-y-3.5 sm:space-y-4">
                <div class="anim-qa-heading flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-violet-600 dark:text-violet-400 font-bold text-sm">✦</span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-100 tracking-tight">Quick Actions</h2>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Akses cepat alat dan fitur utama NEXUS AI OFFICE Anda</p>
                    </div>
                    <button type="button" onclick="showFeatureModal('Quick Actions', 'Semua fitur produktivitas terintegrasi langsung dalam NEXUS AI OFFICE.')" class="text-xs font-bold text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 flex items-center gap-1 transition">
                        <span>Lihat Semua</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                <!-- Module grid: live module emphasised, planned modules visibly de-emphasised -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 items-stretch">

                    <!-- MODULE 1: AI ASSISTANT — planned, reads as unavailable -->
                    <button type="button"
                            onclick="showFeatureModal('AI Assistant', 'Modul AI Assistant akan segera hadir untuk mendukung riset dan produktivitas harian Anda.')"
                            class="anim-qa-card-1 card-premium-light qa-card-planned rounded-3xl p-5 sm:p-6 flex flex-col gap-4 group w-full text-left cursor-pointer">
                        <div class="flex items-start justify-between gap-3">
                            <span class="qa-icon icon-container-wrap w-11 h-11 rounded-2xl bg-violet-100/90 dark:bg-violet-900/30 border border-violet-200/80 dark:border-violet-700/40 flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0">
                                <svg class="icon-svg-target w-5 h-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            <span class="qa-badge px-2.5 py-1 rounded-lg text-[10px] font-bold border border-violet-200/70 dark:border-violet-700/40">Terencana</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">AI Assistant</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1.5">
                                Asisten AI untuk diskusi, riset, dan analisis data.
                            </p>
                        </div>
                    </button>

                    <!-- MODULE 2: MUSIC PLAYER — the live module, carries the violet emphasis -->
                    <a href="{{ route('lifehub.music') }}"
                       class="anim-qa-card-2 card-premium-light qa-card-active rounded-3xl p-5 sm:p-6 flex flex-col gap-4 group block">
                        <div class="flex items-start justify-between gap-3">
                            <span class="qa-icon icon-container-wrap w-11 h-11 rounded-2xl bg-violet-100/90 dark:bg-violet-900/30 border border-violet-200/80 dark:border-violet-700/40 flex items-center justify-center text-violet-600 dark:text-violet-400 shrink-0 shadow-[0_0_16px_rgba(124,58,237,0.14)]">
                                <svg class="icon-svg-target w-5 h-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </span>
                            <span class="qa-badge inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-transparent">
                                <span class="qa-live-dot" aria-hidden="true"></span>Aktif
                            </span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 group-hover:text-violet-700 dark:group-hover:text-violet-400 transition-colors">Music Player</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1.5">
                                Chart, pencarian, dan playlist audio favorit Anda.
                            </p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2">
                                {{ $musicHistoryCount }} lagu diputar &middot; {{ $musicFavoriteCount }} favorit
                            </p>
                        </div>
                        <span class="qa-launch">
                            Buka modul
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>
                    </a>

                    <!-- MODULE 3: DOWNLOADER — planned, reads as unavailable -->
                    <button type="button"
                            onclick="showFeatureModal('Downloader', 'Modul Downloader akan segera hadir untuk mempermudah unduhan konten digital Anda.')"
                            class="anim-qa-card-3 card-premium-light qa-card-planned rounded-3xl p-5 sm:p-6 flex flex-col gap-4 group w-full text-left cursor-pointer">
                        <div class="flex items-start justify-between gap-3">
                            <span class="qa-icon icon-container-wrap w-11 h-11 rounded-2xl bg-indigo-100/90 dark:bg-indigo-900/30 border border-indigo-200/80 dark:border-indigo-700/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                                <svg class="icon-svg-target w-5 h-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </span>
                            <span class="qa-badge px-2.5 py-1 rounded-lg text-[10px] font-bold border border-indigo-200/70 dark:border-indigo-700/40">Terencana</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Downloader</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-1.5">
                                Unduh video, audio, dan file dari berbagai platform.
                            </p>
                        </div>
                    </button>

                </div>
            </div>

<!-- ==================== 3. WORKSPACE STATUS STRIP (LIVE, REAL DATA) ==================== -->
            <!-- Clock and date live in the hero; this strip carries only recorded, non-duplicated facts. -->
            <div class="anim-status">
                <div class="status-strip">
                    <span class="status-chip status-chip-violet">
                        <span class="status-chip-dot"></span>
                        Workspace Aktif
                    </span>
                    <span class="status-chip">
                        <svg class="w-3 h-3 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10" />
                        </svg>
                        Terakhir dipakai:
                        <span class="text-violet-600 dark:text-violet-400">{{ $recentTracks->isNotEmpty() ? 'Music Player' : 'Dashboard' }}</span>
                    </span>
                    <span class="status-chip">
                        <svg class="w-3 h-3 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        {{ $musicHistoryCount }} lagu diputar
                    </span>
                    <span class="status-chip">
                        <svg class="w-3 h-3 text-violet-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
{{ $musicFavoriteCount }} favorit
                    </span>
                </div>
            </div>

            <!-- ==================== 4. PERANGKAT ANDA — SELURUH FAKTA BROWSER, RINGKAS ==================== -->
            <div class="anim-device space-y-3.5 sm:space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-600 dark:text-violet-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m4 10V11m4 6V9M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-100 tracking-tight">Perangkat Anda</h2>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Dibaca langsung dari peramban perangkat ini</p>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 shrink-0 hidden sm:block">Live</span>
                </div>

                <div class="surface-panel surface-sweep rounded-2xl p-3 sm:p-4">
                    <div class="device-grid">
                        <!-- Device Type -->
                        <div class="device-cell">
                            <span class="device-cell-icon">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1v-2a1 1 0 00-1-1H8a1 1 0 00-1 1v2a1 1 0 001 1zm8-11V5a2 2 0 00-2-2H6a2 2 0 00-2 2v9a2 2 0 002 2h2" />
                                </svg>
                            </span>
                            <span class="device-cell-body">
                                <span class="device-cell-label">Tipe</span>
                                <span class="device-cell-value" id="device-type">—</span>
                            </span>
                        </div>

                        <!-- Operating System -->
                        <div class="device-cell">
                            <span class="device-cell-icon">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 4h16" />
                                </svg>
                            </span>
                            <span class="device-cell-body">
                                <span class="device-cell-label">Sistem Operasi</span>
                                <span class="device-cell-value" id="device-os">—</span>
                            </span>
                        </div>

                        <!-- Browser -->
                        <div class="device-cell">
                            <span class="device-cell-icon">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 013-1h10a1 1 0 011 1v14a1 1 0 01-1 1H7a1 1 0 01-1-1V5zm-1 5h16M8 3v4m4-4v4" />
                                </svg>
                            </span>
                            <span class="device-cell-body">
                                <span class="device-cell-label">Peramban</span>
                                <span class="device-cell-value" id="device-browser">—</span>
                            </span>
                        </div>

                        <!-- Device Model (only filled when browser reports it reliably) -->
                        <div class="device-cell">
                            <span class="device-cell-icon">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14v11a1 1 0 01-1 1H6a1 1 0 01-1-1V8zm3 0V5a2 2 0 012-2h4a2 2 0 012 2v3" />
                                </svg>
                            </span>
                            <span class="device-cell-body">
                                <span class="device-cell-label">Model</span>
                                <span class="device-cell-value" id="device-model">Tidak dilaporkan</span>
                            </span>
                        </div>

<!-- Screen Size -->
                        <div class="device-cell">
                            <span class="device-cell-icon">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a1 1 0 011-1h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm6 15h6" />
                                </svg>
                            </span>
                            <span class="device-cell-body">
                                <span class="device-cell-label">Layar</span>
                                <span class="device-cell-value" id="device-screen">—</span>
                            </span>
                        </div>

                        <!-- Approximate Device Memory -->
                        <div class="device-cell">
                            <span class="device-cell-icon">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14v11a1 1 0 01-1 1H6a1 1 0 01-1-1V8zm3 0V5a2 2 0 012-2h4a2 2 0 012 2v3" />
                                </svg>
                            </span>
                            <span class="device-cell-body">
                                <span class="device-cell-label">Memori</span>
                                <span class="device-cell-value" id="device-memory">—</span>
                            </span>
                        </div>
                    </div>

                    <!-- Secondary technical facts, collapsed into one quiet meta bar -->
                    <dl class="device-details">
                        <div class="device-detail">
                            <dt>Mesin</dt>
                            <dd id="device-engine">—</dd>
                        </div>
                        <div class="device-detail">
                            <dt>Inti</dt>
                            <dd id="device-cores">—</dd>
                        </div>
                        <div class="device-detail">
                            <dt>Viewport</dt>
                            <dd id="device-viewport">—</dd>
                        </div>
                        <div class="device-detail">
                            <dt>Densitas</dt>
                            <dd id="device-dpr">—</dd>
                        </div>
                        <div class="device-detail">
                            <dt>Koneksi</dt>
                            <dd id="device-connection">—</dd>
                        </div>
                        <div class="device-detail">
                            <dt>Bahasa</dt>
                            <dd id="device-language">—</dd>
                        </div>
                    </dl>

                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-3 leading-relaxed" id="device-note">
                        Nilai bertanda ≈ adalah perkiraan yang disediakan browser, bukan angka pasti.
                    </p>
                </div>
            </div>

            <!-- ==================== 5. AKTIVITAS TERBARU (RECENT ACTIVITY) ==================== -->
            <div class="anim-recent space-y-3.5 sm:space-y-4 reveal-on-scroll">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-100 tracking-tight">Aktivitas Terbaru</h2>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Riwayat nyata dari aktivitas yang tercatat di akun Anda</p>
                </div>

                <!-- 4 Horizontal Compact Cards Grid (Matches Reference Layout) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

                    <!-- ITEM 1: Musik (real playback history) -->
                    <a href="{{ route('lifehub.music') }}"
                       class="card-activity rounded-2xl p-3 sm:p-3.5 flex items-center justify-between gap-3 group btn-press">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-violet-100/90 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Musik</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ $recentTracks->first()?->title ?? 'Belum ada lagu diputar' }}</p>
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    {{ $recentTracks->first()?->played_at?->diffForHumans() ?? 'Riwayat kosong' }}
                                </span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-violet-600 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <!-- ITEM 2: Download -->
                    <button type="button"
                            onclick="showFeatureModal('Downloader', 'Riwayat unduhan akan tampil di sini setelah modul aktif digunakan.')"
                            class="card-activity rounded-2xl p-3 sm:p-3.5 flex items-center justify-between gap-3 group text-left btn-press">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-violet-100/90 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Download</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">Belum ada unduhan tercatat</p>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Menunggu aktivitas pertama</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-violet-600 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>

                    <!-- ITEM 3: AI Assistant -->
                    <button type="button"
                            onclick="showFeatureModal('AI Assistant', 'Riwayat sesi Obrolan AI akan tampil di sini setelah modul aktif digunakan.')"
                            class="card-activity rounded-2xl p-3 sm:p-3.5 flex items-center justify-between gap-3 group text-left btn-press">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-violet-100/90 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">AI Assistant</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">Belum ada percakapan tercatat</p>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Menunggu aktivitas pertama</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-violet-600 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>

                    <!-- ITEM 4: Profile completeness (derived from real account data) -->
                    <button type="button"
                            onclick="openProfileModal()"
                            class="card-activity rounded-2xl p-2.5 sm:p-3 flex items-center justify-between gap-3 group w-full text-left cursor-pointer">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-violet-100/90 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Profil Anda</h4>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-tight">
                                    {{ $user->avatar_url ? 'Foto profil sudah diisi. Klik untuk mengubah.' : 'Belum ada foto profil. Klik untuk melengkapinya.' }}
                                </p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-violet-600 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>

                </div>
            </div>

        </main>
    </div>

    <!-- ============================================================
         FLOATING NAVIGATION DOCK (Both Desktop & Mobile Friendly)
         With Hide/Show Handle & Scroll Auto-Hide Mechanism
         Fluid width on mobile so it never overflows 360px viewports
         ============================================================ -->
    <div id="floating-dock-wrapper"
         class="anim-dock floating-dock-container fixed bottom-3 sm:bottom-5 left-1/2 z-40 rounded-[24px] sm:rounded-full py-1.5 px-1.5 sm:px-2 select-none"
         style="margin-bottom: env(safe-area-inset-bottom, 0px); max-width: calc(100vw - 1rem);"
         aria-label="Navigation Dock">
        <div class="dock-nav flex items-center gap-0.5 sm:gap-2 w-full sm:w-auto justify-between sm:justify-center">
            <!-- Shared sliding active indicator (positioned by JS) -->
            <span id="dock-indicator" class="dock-indicator" aria-hidden="true"></span>

            <!-- 1. Beranda (Active) -->
            <a href="{{ route('lifehub.dashboard') }}"
               data-dock-item
               aria-current="page"
               class="dock-item flex-1 sm:flex-none flex flex-col items-center justify-center py-1.5 px-1 sm:px-3 rounded-2xl text-violet-700 dark:text-violet-300 font-bold btn-press relative group">
                <svg class="w-4 h-4 mb-0.5 text-violet-600 dark:text-violet-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[9px] sm:text-[10px] tracking-tight whitespace-nowrap">Beranda</span>
            </a>

            <!-- 2. AI -->
            <button type="button"
                    onclick="showFeatureModal('AI Assistant', 'Modul AI Assistant akan segera hadir.')"
                    data-dock-item class="dock-item flex-1 sm:flex-none flex flex-col items-center justify-center py-1.5 px-1 sm:px-3 rounded-2xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 font-medium btn-press group">
                <svg class="w-4 h-4 mb-0.5 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span class="text-[9px] sm:text-[10px] tracking-tight whitespace-nowrap">AI</span>
            </button>

            <!-- 3. Music -->
            <a href="{{ route('lifehub.music') }}"
               data-dock-item class="dock-item flex-1 sm:flex-none flex flex-col items-center justify-center py-1.5 px-1 sm:px-3 rounded-2xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 font-medium btn-press group">
                <svg class="w-4 h-4 mb-0.5 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
                <span class="text-[9px] sm:text-[10px] tracking-tight whitespace-nowrap">Music</span>
            </a>

            <!-- 4. Downloader -->
            <button type="button"
                    onclick="showFeatureModal('Downloader', 'Modul Downloader akan segera hadir.')"
                    data-dock-item class="dock-item flex-1 sm:flex-none flex flex-col items-center justify-center py-1.5 px-1 sm:px-3 rounded-2xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 font-medium btn-press group">
                <svg class="w-4 h-4 mb-0.5 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span class="text-[9px] sm:text-[10px] tracking-tight whitespace-nowrap">Downloader</span>
            </button>

            <!-- 5. Profil -->
            <button type="button"
                    onclick="openProfileModal()"
                    data-dock-item class="dock-item flex-1 sm:flex-none flex flex-col items-center justify-center py-1.5 px-1 sm:px-3 rounded-2xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 font-medium btn-press group">
                <svg class="w-4 h-4 mb-0.5 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[9px] sm:text-[10px] tracking-tight whitespace-nowrap">Profil</span>
            </button>

            <!-- Minimal Hide Handle Button -->
            <button type="button"
                    onclick="toggleDock(false)"
                    title="Sembunyikan Menu Bawah"
                    class="shrink-0 p-1 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition ml-0.5 sm:ml-1"
                    aria-label="Sembunyikan Dock">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Small Trigger Pill When Dock is Hidden -->
    <button id="floating-dock-trigger"
            type="button"
            onclick="toggleDock(true)"
            title="Tampilkan Menu Bawah"
            class="hidden fixed bottom-3 left-1/2 -translate-x-1/2 z-40 backdrop-blur-xl shadow-lg rounded-full px-3.5 py-1 text-[11px] font-bold text-violet-700 dark:text-violet-400 flex items-center gap-1.5 btn-press"
            style="margin-bottom: env(safe-area-inset-bottom, 0px); background-color: var(--lh-dock-bg); border: 1px solid var(--lh-border-strong);">
        <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
        <span>Navigasi</span>
    </button>

    <!-- ============================================================
         PROFILE / ACCOUNT EDIT MODAL (Light Theme, Safe-Area & Keyboard Friendly)
         ============================================================ -->
    <div id="profile-modal" class="modal-layer fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="profile-modal-title">
        <div class="modal-backdrop fixed inset-0 bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm" onclick="closeProfileModal()"></div>

        <div class="modal-panel relative bg-white dark:bg-[#18181C] text-slate-800 dark:text-slate-200 rounded-3xl max-w-md w-full max-h-[88dvh] overflow-y-auto p-6 sm:p-8 shadow-2xl border border-slate-200/80 dark:border-[#27272A] z-10 space-y-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-[#27272A]/60">
                <div>
                    <h3 id="profile-modal-title" class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-100">Pengaturan Akun &amp; Profil</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ubah nama dan foto profil workspace Anda.</p>
                </div>
                <button type="button" onclick="closeProfileModal()" aria-label="Tutup modal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition btn-press">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Profile Form -->
            <form id="profile-form" action="{{ route('lifehub.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5" onsubmit="handleProfileSubmit(event)">
                @csrf
                @method('PUT')

                <!-- Error Display Inside Modal -->
                <div id="modal-error-box" class="{{ $errors->any() ? '' : 'hidden' }} p-3.5 rounded-xl bg-red-50 border border-red-200/80 text-xs font-semibold text-red-700">
                    <ul id="modal-error-list" class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Avatar Upload Section -->
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-2 border-violet-200 bg-violet-50 shrink-0 shadow-sm flex items-center justify-center font-bold text-xl text-violet-700">
                        @if($user->avatar_url)
                            <img id="avatar-preview-img" src="{{ $user->avatar_url }}" alt="Preview Foto Profil" class="w-full h-full object-cover">
                        @else
                            <img id="avatar-preview-img" src="" alt="Preview Foto Profil" class="w-full h-full object-cover hidden">
                            <span id="avatar-preview-placeholder">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div class="space-y-1.5 flex-1 min-w-0">
                        <label class="block text-xs font-bold text-slate-900 dark:text-slate-100">Foto Profil</label>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">PNG, JPG, WebP (Maks. 5 MB)</p>
                        <label for="avatar-input" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-slate-200/80 dark:hover:bg-white/10 border border-slate-200 dark:border-[#27272A] text-xs font-semibold text-slate-700 dark:text-slate-200 cursor-pointer transition btn-press">
                            <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Pilih Foto Baru</span>
                        </label>
                        <input id="avatar-input" type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden" onchange="previewAvatar(event)">
                    </div>
                </div>

                <!-- Name Input -->
                <div class="space-y-1.5">
                    <label for="profile-name" class="block text-xs font-bold text-slate-900 dark:text-slate-100">Nama Lengkap</label>
                    <input type="text" id="profile-name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0F0F13] border border-slate-200 dark:border-[#27272A] rounded-xl text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-[#18181C] focus:border-violet-400 dark:focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/15 outline-none transition">
                </div>

                <!-- Email Input (Readonly) -->
                <div class="space-y-1.5">
                    <label for="profile-email" class="block text-xs font-bold text-slate-900 dark:text-slate-100">Alamat Email</label>
                    <input type="email" id="profile-email" value="{{ $user->email }}" readonly
                           class="w-full px-3.5 py-2.5 bg-slate-100/60 dark:bg-white/5 border border-slate-200/70 dark:border-[#27272A] rounded-xl text-xs text-slate-400 dark:text-slate-500 cursor-not-allowed outline-none select-none">
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Email akun terhubung dengan autentikasi utama Anda.</p>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-[#27272A]/60">
                    <button type="button" onclick="closeProfileModal()"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#27272A] text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5 transition btn-press">
                        Batal
                    </button>
                    <button type="submit" id="profile-save-btn"
                            class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold shadow-lg shadow-violet-600/20 transition flex items-center justify-center gap-2 btn-press">
                        <span id="profile-save-text">Simpan Perubahan</span>
                        <svg id="profile-save-spinner" class="animate-spin w-3.5 h-3.5 text-white hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================
         FEATURE INFO POPUP MODAL (Light & Dark Theme)
         ============================================================ -->
    <div id="feature-modal" class="modal-layer fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="modal-backdrop fixed inset-0 bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm" onclick="closeFeatureModal()"></div>
        <div class="modal-panel relative bg-white dark:bg-[#18181C] text-slate-800 dark:text-slate-200 rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-200/80 dark:border-[#27272A] z-10 space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 mx-auto flex items-center justify-center shadow-xs">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 id="feature-modal-title" class="text-base font-bold text-slate-900 dark:text-slate-100"></h3>
                <p id="feature-modal-desc" class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed"></p>
            </div>
            <button type="button" onclick="closeFeatureModal()"
                    class="w-full py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold shadow-lg shadow-violet-600/20 transition btn-press">
                Mengerti
            </button>
        </div>
    </div>

    <!-- ============================================================
         INTERACTIVE CLIENT CONTROLS & ANIMATION SEQUENCE LOGIC
         ============================================================ -->
    <script>
        // ============================================================
        // 8-STAGE WORKSPACE REVEAL OPENING ANIMATION SEQUENCE
        // Matching Storyboard Panels 1 to 7
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const introOverlay = document.getElementById('workspace-intro-overlay');
            const introLogo = document.getElementById('intro-logo-container');
            const introBloom = document.getElementById('intro-bloom-circle');
            const introStage = document.getElementById('intro-center-stage');

            // Check reduced motion or session flag
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const hasSeenIntro = sessionStorage.getItem('lifehub_workspace_intro_seen');

            function completeRevealInstantly() {
                if (introOverlay) {
                    introOverlay.style.display = 'none';
                }
                document.body.classList.add('workspace-revealed');
                document.documentElement.classList.add('page-transition-ready');
                document.documentElement.classList.remove('js-intro-pending');
            }

            function finishIntro() {
                if (introOverlay) {
                    introOverlay.style.display = 'none';
                }
                document.body.classList.add('workspace-revealed');
                document.documentElement.classList.add('page-transition-ready');
                document.documentElement.classList.remove('js-intro-pending');
            }

            if (prefersReducedMotion || hasSeenIntro) {
                completeRevealInstantly();
            } else {
                // STAGE 1: Logo Reveal (0.0 - 0.4s) — Blur -> Sharp in Center
                setTimeout(() => {
                    if (introLogo) introLogo.classList.add('stage-active');
                }, 80);

                // STAGE 2: Geometric & Bloom Lighting Emerge (0.4 - 0.9s)
                setTimeout(() => {
                    if (introBloom) introBloom.classList.add('stage-active');
                }, 380);

                // STAGE 3 & 4: Logo Fluid Travel to Sidebar/Header & Workspace Enters (0.9 - 1.3s)
                setTimeout(() => {
                    if (introStage) introStage.classList.add('intro-traveling');
                    if (introOverlay) {
                        introOverlay.style.opacity = '0';
                    }
                    // Trigger Cascading Stages 4 to 8
                    document.body.classList.add('workspace-revealed');
                }, 900);

                // Final Overlay Dismissal (after complete settle ~1.7s)
                setTimeout(() => {
                    finishIntro();
                    sessionStorage.setItem('lifehub_workspace_intro_seen', 'true');
                }, 1700);
            }

            // Allow bypass on keypress or click
            window.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' || e.key === ' ') {
                    completeRevealInstantly();
                }
            }, { once: true });
        });

        // Real-Time Live Clock & Dynamic Greeting
        function updateLiveClock() {
            const now = new Date();

            // Format Clock: HH:mm:ss
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('hero-live-clock');
            if (clockEl) {
                clockEl.textContent = `${hours}:${minutes}:${seconds}`;
            }

            // Real-Time Date (Hari, DD Bulan YYYY)
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            const dateEl = document.getElementById('hero-live-date');
            if (dateEl) {
                dateEl.textContent = dateStr;
            }

            // Dynamic Greeting
            const h = now.getHours();
            let greeting = 'Selamat malam,';
            if (h >= 4 && h < 11) {
                greeting = 'Selamat pagi,';
            } else if (h >= 11 && h < 15) {
                greeting = 'Selamat siang,';
            } else if (h >= 15 && h < 19) {
                greeting = 'Selamat sore,';
            } else {
                greeting = 'Selamat malam,';
            }

            const greetingEl = document.getElementById('hero-greeting');
            if (greetingEl) {
                greetingEl.textContent = greeting;
            }
        }

        // Initialize clock
        updateLiveClock();
        setInterval(updateLiveClock, 1000);

        // ============================================================
        // YOUR DEVICE MODULE — reads only real, browser-reported data.
        // Nothing is invented: unsupported fields report "Tidak tersedia".
        // Device model is only shown when the browser exposes it reliably.
        // ============================================================
        (function initDeviceModule() {
            const set = (id, value) => {
                const el = document.getElementById(id);
                if (el && value !== null && value !== undefined && value !== '') {
                    el.textContent = value;
                }
            };

            const ua = navigator.userAgent || '';
            const uaData = navigator.userAgentData || null;

            // --- Operating system ---
            function detectOs() {
                if (uaData && uaData.platform) return uaData.platform;

                if (/Windows NT 10/.test(ua)) return 'Windows 10/11';
                if (/Windows NT 6\.3/.test(ua)) return 'Windows 8.1';
                if (/Windows NT 6\.[12]/.test(ua)) return 'Windows 7/8';
                if (/Windows/.test(ua)) return 'Windows';
                if (/iPhone|iPad|iPod/.test(ua)) return 'iOS';
                if (/Mac OS X/.test(ua)) return 'macOS';
                if (/Android/.test(ua)) return 'Android';
                if (/CrOS/.test(ua)) return 'ChromeOS';
                if (/Linux|X11/.test(ua)) return 'Linux';

                return 'Tidak tersedia';
            }

            // --- Device type ---
            function detectType() {
                if (uaData && typeof uaData.mobile === 'boolean') {
                    return uaData.mobile ? 'Ponsel / Tablet' : 'Desktop';
                }

                if (/iPad|Tablet|PlayBook|Silk|Android(?!.*Mobile)/i.test(ua)) return 'Tablet';
                if (/Mobi|iPhone|iPod|Android.*Mobile|Windows Phone/i.test(ua)) return 'Ponsel';
                if (ua === '') return 'Tidak tersedia';

                return 'Desktop';
            }

            // --- Browser + engine ---
            function detectBrowser() {
                if (uaData && Array.isArray(uaData.brands)) {
                    const brands = uaData.brands
                        .map(b => b.brand)
                        .filter(b => b && !/Not.?A.?Brand/i.test(b) && !/Chromium$/i.test(b));
                    if (brands.length) return brands.join(' / ');
                }

                if (/Edg\//.test(ua)) return 'Microsoft Edge';
                if (/OPR\/|Opera/.test(ua)) return 'Opera';
                if (/Firefox\//.test(ua)) return 'Firefox';
                if (/Chrome\//.test(ua)) return 'Chrome';
                if (/Safari\//.test(ua)) return 'Safari';
                if (/^Mozilla\/5\.0$/.test(ua)) return 'Tidak tersedia';

                return 'Tidak tersedia';
            }

            function detectEngine() {
                if (uaData && Array.isArray(uaData.brands)) {
                    const brands = uaData.brands.map(b => b.brand);
                    if (brands.some(b => /Chrom/i.test(b))) return 'Blink';
                }

                if (/Firefox\/|Gecko\//.test(ua) && !/like Gecko/.test(ua)) return 'Gecko';
                if (/Safari\//.test(ua) && !/Chrome\//.test(ua)) return 'WebKit';
                if (/Trident\/|MSIE /.test(ua)) return 'Trident';
                if (ua === '') return 'Tidak tersedia';

                return 'Tidak tersedia';
            }

            // --- Connection type ---
            function detectConnection() {
                const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
                if (!conn) return 'Tidak dilaporkan';

                const type = conn.effectiveType || conn.type;
                if (!type) return 'Tidak dilaporkan';

                const map = { '4g': '4G', '3g': '3G', '2g': '2G', 'slow-2g': '2G (lambat)', ethernet: 'Ethernet', wifi: 'Wi-Fi', cellular: 'Seluler', none: 'Tanpa Koneksi' };
                return map[type] || String(type).toUpperCase();
            }

            function detectMemory() {
                // deviceMemory is an estimate in GB and rounded to a power of two by design.
                if (typeof navigator.deviceMemory === 'number' && navigator.deviceMemory > 0) {
                    return '≈ ' + navigator.deviceMemory + ' GB';
                }
                return 'Tidak dilaporkan';
            }

            set('device-type', detectType());
            set('device-os', detectOs());
            set('device-browser', detectBrowser());
            set('device-engine', detectEngine());
            set('device-screen', window.screen ? window.screen.width + ' × ' + window.screen.height : '—');
            set('device-viewport', window.innerWidth + ' × ' + window.innerHeight);
            set('device-dpr', (Math.round((window.devicePixelRatio || 1) * 100) / 100) + '×');
            set('device-cores', typeof navigator.hardwareConcurrency === 'number' && navigator.hardwareConcurrency > 0
                ? navigator.hardwareConcurrency + ' inti'
                : 'Tidak dilaporkan');
            set('device-memory', detectMemory());
            set('device-connection', detectConnection());
            set('device-language', navigator.language || 'Tidak tersedia');

            // Device model: ask the browser only when it supports the reliable high-entropy API.
            if (uaData && typeof uaData.getHighEntropyValues === 'function') {
                uaData.getHighEntropyValues(['model', 'platformVersion'])
                    .then(hints => {
                        const model = (hints && hints.model || '').trim();
                        if (model) set('device-model', model);
                    })
                    .catch(() => {
                        // Silently keep the honest "Tidak dilaporkan" default.
                    });
            }

            // Keep viewport live while the window resizes or the phone rotates.
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    set('device-viewport', window.innerWidth + ' × ' + window.innerHeight);
                }, 150);
            });
        })();

        // ============================================================
        // MOBILE COMPACT SEARCH TOGGLE
        // ============================================================
        (function initMobileSearch() {
            const trigger = document.getElementById('mobile-search-trigger');
            const field = document.getElementById('mobile-search-field');
            if (!trigger || !field) return;

            const input = field.querySelector('input');

            trigger.addEventListener('click', function() {
                const willOpen = field.classList.contains('hidden');
                field.classList.toggle('hidden', !willOpen);
                trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                if (willOpen && input) input.focus();
            });

            // Close on Escape so the header never traps focus.
            field.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    field.classList.add('hidden');
                    trigger.setAttribute('aria-expanded', 'false');
                    trigger.focus();
                }
            });
        })();

        // ============================================================
        // DESKTOP SIDEBAR COLLAPSE / EXPAND MECHANISM
        // Matches Panel 8 in Storyboard
        // ============================================================
        let isSidebarCollapsed = localStorage.getItem('lifehub_sidebar_collapsed') === 'true';

        function applySidebarState() {
            const sidebar = document.getElementById('desktop-sidebar');
            const toggleIcon = document.getElementById('sidebar-toggle-icon');
            const collapsedIcon = document.querySelector('.sidebar-collapsed-icon');
            if (!sidebar) return;

            if (isSidebarCollapsed) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16', 'sidebar-collapsed');
                if (collapsedIcon) {
                    collapsedIcon.classList.remove('hidden');
                    collapsedIcon.classList.add('flex');
                }
                if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                sidebar.classList.remove('w-16', 'sidebar-collapsed');
                sidebar.classList.add('w-64');
                if (collapsedIcon) {
                    collapsedIcon.classList.remove('flex');
                    collapsedIcon.classList.add('hidden');
                }
                if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleSidebar() {
            isSidebarCollapsed = !isSidebarCollapsed;
            localStorage.setItem('lifehub_sidebar_collapsed', isSidebarCollapsed);
            applySidebarState();
        }

        // Apply on initial load
        applySidebarState();

        // Ctrl/Cmd + B collapses the rail without leaving the keyboard.
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
                e.preventDefault();
                toggleSidebar();
            }
        });

        // ============================================================
        // DOCK ACTIVE INDICATOR
        // One pill slides to whichever dock item is the current page.
        // ============================================================
        function syncDockIndicator() {
            const indicator = document.getElementById('dock-indicator');
            const nav = indicator ? indicator.parentElement : null;
            if (!indicator || !nav) return;

            const active = nav.querySelector('[data-dock-item][aria-current="page"]');
            if (!active) {
                indicator.classList.remove('is-ready');
                return;
            }

            // Measure the pill, then animate it into place with transform/width only.
            indicator.style.width = active.offsetWidth + 'px';
            indicator.style.transform = 'translateX(' + active.offsetLeft + 'px)';
            indicator.classList.add('is-ready');
        }

        window.addEventListener('resize', syncDockIndicator);
        window.addEventListener('orientationchange', syncDockIndicator);
        document.addEventListener('DOMContentLoaded', syncDockIndicator);
        // Late web-font/layout shifts must not leave the pill misaligned.
        window.addEventListener('load', syncDockIndicator);

        // ============================================================
        // FLOATING DOCK HIDE / SHOW & SCROLL BEHAVIOR
        // Matches Panels 7, 9 & 10 in Storyboard
        // ============================================================
        let lastScrollY = window.scrollY;
        let isDockHiddenManually = false;

        function toggleDock(show) {
            const dock = document.getElementById('floating-dock-wrapper');
            const trigger = document.getElementById('floating-dock-trigger');
            if (!dock || !trigger) return;

            if (show) {
                dock.classList.remove('dock-hidden');
                trigger.classList.add('hidden');
                isDockHiddenManually = false;
            } else {
                dock.classList.add('dock-hidden');
                trigger.classList.remove('hidden');
                isDockHiddenManually = true;
            }
        }

        // Auto-hide on scroll down, show on scroll up (with debounce)
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (isDockHiddenManually) return;

            const currentScrollY = window.scrollY;
            const dock = document.getElementById('floating-dock-wrapper');
            const trigger = document.getElementById('floating-dock-trigger');
            if (!dock || !trigger) return;

            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(function() {
                if (currentScrollY > lastScrollY && currentScrollY > 120) {
                    // Scrolling down: hide dock smoothly
                    dock.classList.add('dock-hidden');
                    trigger.classList.remove('hidden');
                } else if (currentScrollY < lastScrollY) {
                    // Scrolling up: show dock smoothly
                    dock.classList.remove('dock-hidden');
                    trigger.classList.add('hidden');
                }
                lastScrollY = currentScrollY;
            }, 50);
        }, { passive: true });

        // ============================================================
        // SCROLL REVEAL VIA INTERSECTION OBSERVER
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

                revealElements.forEach(function(el) {
                    observer.observe(el);
                });
            } else {
                revealElements.forEach(function(el) {
                    el.classList.add('revealed');
                });
            }
        });

        // ============================================================
        // MODALS CONTROL & SUBMIT
        // ============================================================
        function openModalLayer(id) {
            const layer = document.getElementById(id);
            if (!layer) return;
            layer.classList.remove('hidden', 'is-closing');
            layer.classList.add('is-open');
        }

        function closeModalLayer(id) {
            const layer = document.getElementById(id);
            if (!layer || layer.classList.contains('hidden')) return;

            layer.classList.remove('is-open');
            layer.classList.add('is-closing');

            // Finish on whichever of the backdrop/panel transition ends first,
            // with a timer fallback so a suppressed animation can't strand it open.
            let settled = false;
            const settle = function () {
                if (settled) return;
                settled = true;
                layer.classList.add('hidden');
                layer.classList.remove('is-closing');
            };

            layer.querySelectorAll('.modal-panel, .modal-backdrop').forEach(function (node) {
                node.addEventListener('animationend', settle, { once: true });
                node.addEventListener('transitionend', settle, { once: true });
            });
            window.setTimeout(settle, 260);
        }

        function openProfileModal() {
            openModalLayer('profile-modal');
        }

        function closeProfileModal() {
            closeModalLayer('profile-modal');
        }

        function showFeatureModal(title, desc) {
            document.getElementById('feature-modal-title').textContent = title;
            document.getElementById('feature-modal-desc').textContent = desc;
            openModalLayer('feature-modal');
        }

        function closeFeatureModal() {
            closeModalLayer('feature-modal');
        }

        // Escape closes whichever dialog is open, so the close animation
        // can never leave a modal stuck on screen.
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            ['profile-modal', 'feature-modal'].forEach(function (id) {
                const layer = document.getElementById(id);
                if (layer && !layer.classList.contains('hidden')) {
                    closeModalLayer(id);
                }
            });
        });

        // Live Avatar Preview with Size Limit Check
        function previewAvatar(event) {
            const file = event.target.files[0];
            const errorBox = document.getElementById('modal-error-box');
            const errorList = document.getElementById('modal-error-list');

            if (!file) return;

            // 5MB limit
            if (file.size > 5 * 1024 * 1024) {
                if (errorBox && errorList) {
                    errorList.innerHTML = '<li>Ukuran file foto profil maksimal 5 MB.</li>';
                    errorBox.classList.remove('hidden');
                }
                event.target.value = '';
                return;
            }

            if (errorBox) {
                errorBox.classList.add('hidden');
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('avatar-preview-img');
                const placeholder = document.getElementById('avatar-preview-placeholder');
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }

        function handleProfileSubmit(e) {
            const saveBtn = document.getElementById('profile-save-btn');
            const saveText = document.getElementById('profile-save-text');
            const saveSpinner = document.getElementById('profile-save-spinner');

            if (saveBtn && saveSpinner && saveText) {
                saveBtn.disabled = true;
                saveText.textContent = 'Menyimpan...';
                saveSpinner.classList.remove('hidden');
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            html.classList.add('theme-transitioning');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('lifehub_theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('lifehub_theme', 'dark');
            }
            
            setTimeout(() => {
                html.classList.remove('theme-transitioning');
            }, 250);
        }
    </script>

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openProfileModal();
        });
    </script>
    @endif

    @include('partials.page-transitions')
</body>
</html>
