<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#090A0F]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Music') — LifeHub AI Workspace</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.02); }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.12); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(139, 92, 246, 0.4); }

        .music-shell {
            background-color: #090A0F;
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.04) 0px, transparent 50%);
        }

        .card-obsidian {
            background: rgba(18, 19, 26, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-obsidian:hover {
            background: rgba(26, 27, 38, 0.85);
            border-color: rgba(139, 92, 246, 0.35);
            transform: translateY(-2px);
        }

        .player-bar-bg {
            background: rgba(10, 11, 16, 0.94);
            backdrop-filter: blur(24px);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-obsidian {
            background: rgba(10, 11, 16, 0.85);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        .progress-rail {
            cursor: pointer;
            height: 4px;
            transition: height 0.15s ease;
        }
        .progress-rail:hover {
            height: 6px;
        }

        .volume-slider {
            -webkit-appearance: none;
            appearance: none;
            height: 4px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 9999px;
            outline: none;
        }
        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 12px;
            height: 12px;
            background: #A78BFA;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 8px rgba(124, 58, 237, 0.5);
        }

        .nav-link-active {
            background: rgba(124, 58, 237, 0.15);
            color: #C4B5FD;
            border-color: rgba(139, 92, 246, 0.3);
        }
        .nav-link-inactive {
            color: #94A3B8;
            border-color: transparent;
        }
        .nav-link-inactive:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.04);
        }

        .safe-bottom-space {
            padding-bottom: calc(12.5rem + env(safe-area-inset-bottom, 0px));
        }
        @media (min-width: 1024px) {
            .safe-bottom-space {
                padding-bottom: 9rem;
            }
        }

        #persistent-player {
            bottom: 3.5rem;
        }
        @media (min-width: 1024px) {
            #persistent-player {
                bottom: 0;
            }
        }

        #yt-player-frame-wrap iframe,
        #yt-iframe-player {
            width: 100% !important;
            height: 100% !important;
            border: 0;
        }
    </style>
</head>
<body class="h-full min-h-[100dvh] music-shell text-slate-100 antialiased font-sans flex flex-col overflow-hidden selection:bg-[#7C3AED]/30 selection:text-white">

    {{-- Music has no workspace intro, so page-enter owns the entrance --}}
    <script>
        document.documentElement.classList.add('page-transition-ready');
    </script>

    <!-- Overall Workspace Shell -->
    <div class="flex flex-1 h-screen overflow-hidden">

        <!-- ════════════════════════════════════════════════════════════
             DESKTOP SIDEBAR (Compact, Premium, Violet Identity)
             ════════════════════════════════════════════════════════════ -->
        <aside class="hidden lg:flex lg:flex-col lg:w-60 sidebar-obsidian shrink-0 z-30 select-none">
            <!-- Brand Logo & LifeHub Return -->
            <div class="p-5 pb-4 flex items-center justify-between border-b border-white/[0.06]">
                <a href="{{ route('lifehub.dashboard') }}" class="flex items-center gap-2.5 group" title="Kembali ke LifeHub Dashboard">
                    <div class="w-8 h-8 rounded-xl bg-[#141520] border border-violet-500/30 flex items-center justify-center text-white shadow-xs group-hover:scale-105 group-hover:border-violet-400 transition-all">
                        <svg class="w-4 h-4 text-violet-400" viewBox="0 0 48 48" fill="none" stroke="currentColor">
                            <path d="M24 6L38 14V34L24 42L10 34V14L24 6Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                            <path d="M10 14L24 22L38 14" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                            <path d="M24 22V42" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" />
                            <circle cx="24" cy="22" r="3" fill="#8B5CF6" />
                        </svg>
                    </div>
                    <div class="flex items-center tracking-tight text-sm font-bold text-white">
                        <span>LifeHub</span>
                        <span class="text-violet-400 ml-1">Music</span>
                    </div>
                </a>

                <!-- Back to dashboard badge -->
                <a href="{{ route('lifehub.dashboard') }}"
                   class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition"
                   title="Ke Dashboard Utama">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto" aria-label="Music Navigation">
                <!-- Group: Discover -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500 block mb-1.5">Discover</span>

                    <!-- Beranda -->
                    <a href="{{ route('lifehub.music') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'beranda' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'beranda' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Beranda</span>
                    </a>

                    <!-- Search -->
                    <a href="{{ route('lifehub.music.search.page') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'search' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'search' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Pencarian</span>
                    </a>

                    <!-- Trending -->
                    <a href="{{ route('lifehub.music.trending.page') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'trending' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'trending' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span>Trending</span>
                    </a>

                    <!-- Top Charts -->
                    <a href="{{ route('lifehub.music.charts.page') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'charts' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'charts' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        <span>Top Charts</span>
                    </a>
                </div>

                <!-- Group: Koleksi Pribadi -->
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500 block mb-1.5">Koleksi Saya</span>

                    <!-- Playlists -->
                    <a href="{{ route('lifehub.music.playlists.page') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'playlists' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'playlists' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Playlist</span>
                    </a>

                    <!-- History -->
                    <a href="{{ route('lifehub.music.history.page') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'history' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'history' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Riwayat Putar</span>
                    </a>

                    <!-- Favorites -->
                    <a href="{{ route('lifehub.music.favorites.page') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'favorites' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'favorites' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span>Lagu Favorit</span>
                    </a>
                </div>
            </nav>

            <!-- Authenticated User Profile (Compact) -->
            <div class="p-4 border-t border-white/[0.06] bg-black/20">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full overflow-hidden border border-violet-500/30 bg-violet-950/40 shrink-0">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="text-xs font-bold text-white block truncate">{{ $user->name }}</span>
                        <span class="text-[10px] text-violet-400 font-medium block">Personal Workspace</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ════════════════════════════════════════════════════════════
             MAIN CONTENT WORKSPACE (Header, Viewport, Safe Bottom)
             ════════════════════════════════════════════════════════════ -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

            <!-- Topbar / Search Header -->
            <header class="h-16 px-4 sm:px-8 border-b border-white/[0.06] bg-[#0A0B10]/80 backdrop-blur-xl flex items-center justify-between gap-4 shrink-0 z-20">
                
                <!-- Mobile brand & back to dashboard -->
                <div class="flex items-center gap-2.5 lg:hidden">
                    <a href="{{ route('lifehub.dashboard') }}" class="p-1.5 rounded-lg bg-white/5 text-slate-300 hover:text-white" title="Ke Dashboard">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <span class="text-sm font-bold text-white">LifeHub <span class="text-violet-400">Music</span></span>
                </div>

                <!-- Fully Functional Search Bar (Desktop & Mobile) -->
                <form action="{{ route('lifehub.music.search.page') }}" method="GET" class="flex-1 max-w-xl relative" id="top-search-form" autocomplete="off">
                    <div class="relative w-full">
                        <button type="submit" class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 hover:text-violet-300 transition" title="Cari musik">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <input type="text"
                               name="q"
                               id="global-search-input"
                               value="{{ request('q', '') }}"
                               placeholder="Cari lagu, artis, album, atau playlist..."
                               autocomplete="off"
                               class="w-full pl-10 pr-10 py-2 rounded-xl bg-white/[0.06] border border-white/10 text-xs text-white placeholder-slate-400 focus:bg-[#12131C] focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none transition cursor-text">
                        @if(request('q'))
                            <a href="{{ route('lifehub.music.search.page') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white" title="Hapus pencarian">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <!-- Autocomplete Suggestions Dropdown -->
                    <div id="search-suggestions-box"
                         class="absolute left-0 right-0 top-full mt-1.5 bg-[#12131C]/95 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50 hidden divide-y divide-white/[0.04] max-h-80 overflow-y-auto">
                    </div>
                </form>

                <!-- Topbar Actions & User Avatar -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <button type="button" onclick="openCreatePlaylistModal()"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-semibold shadow-xs transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Playlist</span>
                    </button>

                    <a href="{{ route('lifehub.dashboard') }}"
                       class="w-8 h-8 rounded-full overflow-hidden border border-white/10 shrink-0"
                       title="Akun: {{ $user->name }}">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </a>

                    <!-- Mobile Hamburger Menu Button -->
                    <button type="button" onclick="openMobileNavDrawer()"
                            class="lg:hidden p-2 rounded-xl bg-white/[0.05] hover:bg-white/10 border border-white/10 text-slate-300 hover:text-white transition shrink-0"
                            title="Buka Menu Musik" aria-label="Buka Menu Navigasi Musik">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Scrollable Body Content -->
            <main class="flex-1 overflow-y-auto safe-bottom-space" id="main-content-scroll">
                <div class="page-transition-target">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         PERSISTENT MUSIC PLAYER (Fixed Bottom Bar)
         ════════════════════════════════════════════════════════════ -->
    <footer id="persistent-player" class="fixed bottom-0 inset-x-0 z-50 player-bar-bg transition-all duration-300">
        <!-- Interactive Progress Bar (Seekable) -->
        <div class="progress-rail w-full bg-white/10 relative cursor-pointer group select-none" id="progress-container" onclick="playerSeek(event)">
            <div id="progress-fill" class="h-full bg-gradient-to-r from-violet-600 to-violet-400 rounded-r-full relative transition-all" style="width: 0%">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-2.5 h-2.5 bg-white rounded-full shadow-md scale-0 group-hover:scale-100 transition-transform"></div>
            </div>
        </div>

        <div class="px-3 sm:px-6 py-2.5 flex items-center justify-between gap-3">
            
            <!-- Left: Current Track Info (Clickable on Mobile to Expand Now Playing) -->
            <div class="flex items-center gap-3 min-w-0 flex-1 sm:flex-initial sm:w-[22rem] cursor-pointer group" onclick="openNowPlayingModal()">
                <div id="yt-mini-slot"
                     class="relative w-[200px] h-[113px] rounded-xl overflow-hidden bg-black border border-white/10 shrink-0 shadow-lg">
                    <img id="player-thumb" src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=200&q=80" alt="Album Art" class="absolute inset-0 w-full h-full object-cover">
                    <div id="yt-player-frame-wrap" class="absolute inset-0 z-[1]">
                        <div id="yt-iframe-player"></div>
                    </div>
                    <div id="yt-embed-error" class="hidden absolute inset-0 z-10 bg-[#10111A]/95 p-2.5 flex flex-col items-center justify-center text-center gap-2">
                        <p id="yt-embed-error-text" class="text-[11px] font-semibold text-white leading-snug">Video ini tidak dapat diputar di LifeHub Music.</p>
                        <a id="yt-watch-link"
                           href="https://www.youtube.com"
                           target="_blank"
                           rel="noopener noreferrer"
                           onclick="event.stopPropagation();"
                           class="inline-flex items-center px-2.5 py-1 rounded-lg bg-violet-600 hover:bg-violet-500 text-[10px] font-bold text-white">
                            Tonton di YouTube
                        </a>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p id="player-title" class="text-xs font-bold text-white truncate leading-tight group-hover:text-violet-300 transition-colors">Pilih lagu untuk memutar</p>
                    <p id="player-artist" class="text-[11px] text-slate-400 truncate mt-0.5">LifeHub AI Music</p>
                </div>
                <!-- Favorite Action Button -->
                <button type="button" id="player-fav-btn" onclick="event.stopPropagation(); toggleCurrentSongFavorite();"
                        class="p-2 rounded-lg text-slate-400 hover:text-violet-400 hover:bg-white/5 transition shrink-0 hidden sm:block" title="Tambah ke Favorit">
                    <svg id="player-fav-icon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            </div>

            <!-- Center: Audio Controls (Prev, Play/Pause, Next, Shuffle, Repeat) -->
            <div class="flex flex-col items-center gap-1 shrink-0">
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Shuffle Button -->
                    <button type="button" id="player-shuffle-btn" onclick="toggleShuffle()"
                            class="hidden sm:block p-1.5 rounded-lg text-slate-400 hover:text-white transition" title="Acak Lagu (Shuffle)">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h4l3 8-3 8H4m16-16h-4l-3 8 3 8h4" />
                        </svg>
                    </button>

                    <!-- Previous Button -->
                    <button type="button" onclick="playPreviousSong()"
                            class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition" title="Lagu Sebelumnya">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" />
                        </svg>
                    </button>

                    <!-- Main Play / Pause Button -->
                    <button type="button" id="player-play-btn" onclick="togglePlayPause()"
                            class="w-10 h-10 rounded-full bg-violet-600 hover:bg-violet-500 text-white flex items-center justify-center shadow-lg shadow-violet-600/30 hover:scale-105 transition active:scale-95" title="Putar / Jeda">
                        <svg id="icon-play" class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <svg id="icon-pause" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button type="button" onclick="playNextSong()"
                            class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition" title="Lagu Berikutnya">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z" />
                        </svg>
                    </button>

                    <!-- Repeat Button -->
                    <button type="button" id="player-repeat-btn" onclick="toggleRepeat()"
                            class="hidden sm:block p-1.5 rounded-lg text-slate-400 hover:text-white transition" title="Ulangi (Repeat)">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>

                <!-- Time counter (Mobile & Desktop) -->
                <div class="text-[10px] text-slate-400 font-mono flex items-center gap-1 select-none">
                    <span id="player-cur-time">0:00</span>
                    <span>/</span>
                    <span id="player-dur-time">0:00</span>
                </div>
            </div>

            <!-- Right: Volume & Queue Tools (Desktop) -->
            <div class="hidden sm:flex items-center gap-3 w-72 justify-end">
                <!-- Queue Toggle -->
                <button type="button" onclick="toggleQueueDrawer()"
                        class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition relative" title="Antrian Lagu">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                    <span id="queue-badge" class="hidden absolute top-1 right-1 w-2 h-2 bg-violet-500 rounded-full"></span>
                </button>

                <!-- Volume Controls -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="toggleMute()" class="p-1.5 text-slate-400 hover:text-white transition" title="Volume / Mute">
                        <svg id="icon-vol-high" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                        </svg>
                        <svg id="icon-vol-mute" class="w-4 h-4 hidden text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                        </svg>
                    </button>
                    <input type="range" id="player-volume-slider" min="0" max="100" value="80"
                           class="volume-slider w-20" oninput="changeVolume(this.value)">
                </div>

                <!-- Expand Now Playing on Desktop -->
                <button type="button" onclick="openNowPlayingModal()" class="p-2 text-slate-400 hover:text-white transition" title="Buka Tampilan Penuh">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                </button>
            </div>
        </div>

    </footer>

    <!-- ════════════════════════════════════════════════════════════
         MOBILE BOTTOM NAVIGATION (Icons Placed Strictly ON TOP)
         ════════════════════════════════════════════════════════════ -->
    <nav class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-[#090A0F]/95 backdrop-blur-xl border-t border-white/[0.08]"
         style="padding-bottom: env(safe-area-inset-bottom, 0px);">
        <div class="grid grid-cols-5 text-center items-center py-1.5">
            <!-- 1. Beranda -->
            <a href="{{ route('lifehub.music') }}"
               class="flex flex-col items-center py-1 {{ ($activeNav ?? '') === 'beranda' ? 'text-violet-400' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[10px] font-semibold">Beranda</span>
            </a>

            <!-- 2. Search -->
            <a href="{{ route('lifehub.music.search.page') }}"
               class="flex flex-col items-center py-1 {{ ($activeNav ?? '') === 'search' ? 'text-violet-400' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="text-[10px] font-semibold">Cari</span>
            </a>

            <!-- 3. Trending -->
            <a href="{{ route('lifehub.music.trending.page') }}"
               class="flex flex-col items-center py-1 {{ ($activeNav ?? '') === 'trending' ? 'text-violet-400' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span class="text-[10px] font-semibold">Trending</span>
            </a>

            <!-- 4. Playlist -->
            <a href="{{ route('lifehub.music.playlists.page') }}"
               class="flex flex-col items-center py-1 {{ ($activeNav ?? '') === 'playlists' ? 'text-violet-400' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-[10px] font-semibold">Playlist</span>
            </a>

            <!-- 5. Koleksi (History/Favorit) -->
            <a href="{{ route('lifehub.music.favorites.page') }}"
               class="flex flex-col items-center py-1 {{ in_array(($activeNav ?? ''), ['favorites', 'history']) ? 'text-violet-400' : 'text-slate-400 hover:text-white' }}">
                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span class="text-[10px] font-semibold">Favorit</span>
            </a>
        </div>
    </nav>

    <!-- ════════════════════════════════════════════════════════════
         MOBILE NAVIGATION DRAWER (Premium Compact Sheet)
         ════════════════════════════════════════════════════════════ -->
    <div id="mobile-nav-drawer" class="fixed inset-0 z-[80] hidden select-none" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity" onclick="closeMobileNavDrawer()"></div>
        
        <!-- Drawer Panel -->
        <div class="fixed inset-y-0 right-0 w-72 max-w-[85vw] bg-[#0E0F17] border-l border-white/10 shadow-2xl p-6 flex flex-col justify-between z-10">
            <div>
                <!-- Drawer Header -->
                <div class="flex items-center justify-between pb-4 border-b border-white/[0.08]">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-[#141520] border border-violet-500/30 flex items-center justify-center text-violet-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                        <span class="text-sm font-extrabold text-white">LifeHub <span class="text-violet-400">Music</span></span>
                    </div>
                    <button type="button" onclick="closeMobileNavDrawer()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tutup Menu">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation List (Dedicated Pages) -->
                <nav class="py-4 space-y-1">
                    <a href="{{ route('lifehub.music') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'beranda' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'beranda' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('lifehub.music.search.page') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'search' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'search' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Pencarian</span>
                    </a>

                    <a href="{{ route('lifehub.music.trending.page') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'trending' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'trending' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span>Trending</span>
                    </a>

                    <a href="{{ route('lifehub.music.charts.page') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'charts' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'charts' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                        <span>Top Charts</span>
                    </a>

                    <a href="{{ route('lifehub.music.playlists.page') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'playlists' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'playlists' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Playlist</span>
                    </a>

                    <a href="{{ route('lifehub.music.history.page') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'history' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'history' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Riwayat Putar</span>
                    </a>

                    <a href="{{ route('lifehub.music.favorites.page') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold border transition {{ ($activeNav ?? '') === 'favorites' ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="w-4 h-4 {{ ($activeNav ?? '') === 'favorites' ? 'text-violet-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span>Lagu Favorit</span>
                    </a>
                </nav>
            </div>

            <!-- Drawer Bottom: Back to main Dashboard -->
            <div class="pt-4 border-t border-white/[0.08]">
                <a href="{{ route('lifehub.dashboard') }}"
                   class="flex items-center gap-3 p-2.5 rounded-xl bg-white/[0.03] hover:bg-white/[0.06] transition text-xs font-medium text-slate-300 hover:text-white">
                    <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         MODALS & OVERLAYS (Now Playing, Queue, Playlist Add/Create)
         ════════════════════════════════════════════════════════════ -->

    <!-- 1. FULLSCREEN NOW PLAYING MODAL (Desktop & Mobile) -->
    <div id="now-playing-modal" class="fixed inset-0 z-[70] hidden flex flex-col justify-end lg:items-center lg:justify-center p-0 lg:p-6 select-none" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/85 backdrop-blur-xl transition-opacity" onclick="closeNowPlayingModal()"></div>
        <div class="relative w-full lg:max-w-md bg-[#10111A] border-t lg:border border-white/10 rounded-t-3xl lg:rounded-3xl p-6 shadow-2xl z-10 space-y-6 max-h-[92dvh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-violet-400 tracking-wider uppercase">Now Playing</span>
                <button type="button" onclick="closeNowPlayingModal()" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <!-- Big Artwork / Visible YouTube Player -->
            <div id="yt-np-slot" class="w-full aspect-square rounded-2xl overflow-hidden shadow-2xl border border-white/10 relative bg-black/40">
                <img id="np-artwork" src="" alt="Song Cover" class="absolute inset-0 w-full h-full object-cover">
            </div>

            <!-- Title & Artist -->
            <div class="space-y-1">
                <h3 id="np-title" class="text-lg font-bold text-white truncate">Pilih lagu</h3>
                <p id="np-artist" class="text-sm text-slate-400 truncate">LifeHub AI Music</p>
            </div>

            <!-- Progress Bar -->
            <div class="space-y-1.5">
                <div class="progress-rail w-full bg-white/10 rounded-full relative cursor-pointer" onclick="playerSeek(event)">
                    <div id="np-progress-fill" class="h-full bg-violet-500 rounded-full" style="width: 0%"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-400 font-mono">
                    <span id="np-cur-time">0:00</span>
                    <span id="np-dur-time">0:00</span>
                </div>
            </div>

            <!-- Big Controls -->
            <div class="flex items-center justify-center gap-6 pt-2">
                <button type="button" onclick="playPreviousSong()" class="p-3 text-slate-300 hover:text-white transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" /></svg>
                </button>
                <button type="button" id="np-play-btn" onclick="togglePlayPause()" class="w-14 h-14 rounded-full bg-violet-600 text-white flex items-center justify-center shadow-xl shadow-violet-600/40 hover:scale-105 transition">
                    <svg id="np-icon-play" class="w-7 h-7 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                    <svg id="np-icon-pause" class="w-7 h-7 hidden" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" /></svg>
                </button>
                <button type="button" onclick="playNextSong()" class="p-3 text-slate-300 hover:text-white transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z" /></svg>
                </button>
            </div>

            <!-- Extra Actions -->
            <div class="flex items-center justify-around border-t border-white/[0.06] pt-4 text-slate-400">
                <button type="button" onclick="toggleCurrentSongFavorite()" class="flex flex-col items-center gap-1 hover:text-violet-400 transition">
                    <svg id="np-fav-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="text-[10px]">Favorit</span>
                </button>
                <button type="button" onclick="showAddToPlaylistModalCurrent()" class="flex flex-col items-center gap-1 hover:text-violet-400 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-[10px]">Playlist</span>
                </button>
                <button type="button" onclick="closeNowPlayingModal(); toggleQueueDrawer();" class="flex flex-col items-center gap-1 hover:text-violet-400 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                    <span class="text-[10px]">Antrian</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. QUEUE DRAWER (Right sidebar / modal) -->
    <div id="queue-drawer" class="fixed inset-0 z-[70] hidden select-none" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="toggleQueueDrawer()"></div>
        <div class="fixed inset-y-0 right-0 max-w-sm w-full bg-[#10111A] border-l border-white/10 p-6 flex flex-col shadow-2xl z-10 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-sm font-bold text-white">Antrian Pemutaran</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5" id="queue-count-text">0 lagu dalam antrian</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="clearQueue()" class="text-xs text-red-400 hover:text-red-300 font-semibold px-2 py-1 rounded transition">Bersihkan</button>
                    <button type="button" onclick="toggleQueueDrawer()" class="p-1 rounded-lg text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <!-- Queue list -->
            <div id="queue-items-container" class="flex-1 overflow-y-auto space-y-1.5 pr-1">
                <!-- Dynamically rendered -->
            </div>
        </div>
    </div>

    <!-- 3. CREATE PLAYLIST MODAL -->
    <div id="create-playlist-modal" class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" onclick="closeCreatePlaylistModal()"></div>
        <div class="relative w-full max-w-sm bg-[#12131F] border border-white/10 rounded-2xl p-6 shadow-2xl z-10 space-y-4">
            <div>
                <h3 class="text-base font-bold text-white">Buat Playlist Baru</h3>
                <p class="text-xs text-slate-400 mt-1">Kumpulkan lagu favoritmu dalam satu wadah khusus.</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Playlist <span class="text-red-400">*</span></label>
                    <input type="text" id="new-pl-name" placeholder="Misal: Senja Akustik..." maxlength="100"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/[0.06] border border-white/10 text-xs text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi (Opsional)</label>
                    <textarea id="new-pl-desc" rows="2" placeholder="Catatan singkat playlist..." maxlength="500"
                              class="w-full px-3.5 py-2.5 rounded-xl bg-white/[0.06] border border-white/10 text-xs text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-white/[0.06]">
                <button type="button" onclick="closeCreatePlaylistModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white transition">Batal</button>
                <button type="button" onclick="submitCreatePlaylist()" class="px-5 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-xs font-bold text-white shadow-xs transition">Buat Playlist</button>
            </div>
        </div>
    </div>

    <!-- 4. ADD TO PLAYLIST MODAL -->
    <div id="add-to-playlist-modal" class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" onclick="closeAddToPlaylistModal()"></div>
        <div class="relative w-full max-w-sm bg-[#12131F] border border-white/10 rounded-2xl p-6 shadow-2xl z-10 space-y-4 max-h-[80dvh] flex flex-col">
            <div class="flex items-center justify-between pb-2 border-b border-white/[0.06]">
                <h3 class="text-sm font-bold text-white">Tambahkan ke Playlist</h3>
                <button type="button" onclick="closeAddToPlaylistModal()" class="p-1 text-slate-400 hover:text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div id="add-to-playlist-list" class="flex-1 overflow-y-auto space-y-1.5 pr-1">
                <!-- Dynamically loaded -->
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         PERSISTENT JAVASCRIPT AUDIO CLIENT ENGINE
         ════════════════════════════════════════════════════════════ -->
    <script>
    (function () {
        'use strict';

        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const STORAGE_KEY = 'lifehub_music_player_state';

        // Internal Player State
        window.MusicApp = {
            ytPlayer: null,
            isReady: false,
            isInitializing: false,
            isPlaying: false,
            pendingSong: null,
            currentSong: null,
            queue: [],
            volume: 80,
            isMuted: false,
            isShuffle: false,
            repeatMode: 0, // 0: off, 1: all, 2: one
            progressTimer: null,
            activeSongToAdd: null,
        };

        // Load Persistent State from LocalStorage
        function loadSavedState() {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved) {
                    const data = JSON.parse(saved);
                    MusicApp.currentSong = data.currentSong || null;
                    MusicApp.queue = data.queue || [];
                    MusicApp.volume = typeof data.volume === 'number' ? data.volume : 80;
                    MusicApp.isMuted = !!data.isMuted;
                    MusicApp.isShuffle = !!data.isShuffle;
                    MusicApp.repeatMode = typeof data.repeatMode === 'number' ? data.repeatMode : 0;
                }
            } catch (e) {
                console.warn('Could not restore music player state:', e);
            }
        }

        function saveCurrentState() {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify({
                    currentSong: MusicApp.currentSong,
                    queue: MusicApp.queue,
                    volume: MusicApp.volume,
                    isMuted: MusicApp.isMuted,
                    isShuffle: MusicApp.isShuffle,
                    repeatMode: MusicApp.repeatMode,
                }));
            } catch (e) {
                // Ignore storage limits
            }
        }

        // Initialize YouTube IFrame API
        function initYouTubeAPI() {
            if (window.YT && window.YT.Player) {
                createYTPlayer();
            } else if (!document.querySelector('script[src*="youtube.com/iframe_api"]')) {
                const tag = document.createElement('script');
                tag.src = 'https://www.youtube.com/iframe_api';
                document.head.appendChild(tag);
            }
        }

        window.onYouTubeIframeAPIReady = function () {
            createYTPlayer();
        };

        function attachPlayerToSlot(slotId) {
            // Player iframe is stably mounted in #yt-mini-slot to prevent browser iframe destruction and reload
        }

        function resizeYtPlayer(slot) {
            // Size is fixed to the container
        }

        function hideEmbedError() {
            const box = document.getElementById('yt-embed-error');
            if (box) {
                box.classList.add('hidden');
            }
        }

        function showEmbedError(videoId, message) {
            const box = document.getElementById('yt-embed-error');
            const text = document.getElementById('yt-embed-error-text');
            const link = document.getElementById('yt-watch-link');
            if (text) {
                text.textContent = message;
            }
            if (link && videoId) {
                link.href = 'https://www.youtube.com/watch?v=' + encodeURIComponent(videoId);
            }
            if (box) {
                box.classList.remove('hidden');
            }
        }

        function createYTPlayer() {
            if (MusicApp.ytPlayer || MusicApp.isInitializing) return;
            const container = document.getElementById('yt-iframe-player');
            if (!container) return;

            MusicApp.isInitializing = true;
            try {
                MusicApp.ytPlayer = new YT.Player('yt-iframe-player', {
                    height: '113',
                    width: '200',
                    playerVars: {
                        autoplay: 0,
                        controls: 0,
                        disablekb: 1,
                        playsinline: 1,
                        enablejsapi: 1,
                        origin: window.location.origin,
                        widget_referrer: window.location.origin,
                    },
                    events: {
                        onReady: onPlayerReady,
                        onStateChange: onPlayerStateChange,
                        onError: onPlayerError,
                    }
                });
            } catch (e) {
                console.warn('YT.Player init warning:', e);
                MusicApp.isInitializing = false;
            }
        }

        function onPlayerReady(event) {
            MusicApp.isReady = true;
            MusicApp.isInitializing = false;
            if (event && event.target) {
                MusicApp.ytPlayer = event.target;
            }

            if (MusicApp.isMuted) {
                try { MusicApp.ytPlayer.mute(); } catch (e) {}
            } else {
                try {
                    MusicApp.ytPlayer.unMute();
                    MusicApp.ytPlayer.setVolume(MusicApp.volume);
                } catch (e) {}
            }

            // If a song was clicked while player was initializing, play it immediately
            if (MusicApp.pendingSong) {
                const toPlay = MusicApp.pendingSong;
                MusicApp.pendingSong = null;
                window.playSong(toPlay);
            } else if (MusicApp.currentSong) {
                renderPlayerBar();
            }
        }

        function onPlayerStateChange(event) {
            const state = event.data;
            if (state === YT.PlayerState.PLAYING) {
                MusicApp.isPlaying = true;
                hideEmbedError();
                updatePlayPauseIcons(true);
                startProgressTracker();
                updateTimeAndProgress();
            } else if (state === YT.PlayerState.PAUSED) {
                MusicApp.isPlaying = false;
                updatePlayPauseIcons(false);
                stopProgressTracker();
                updateTimeAndProgress();
            } else if (state === YT.PlayerState.BUFFERING || state === YT.PlayerState.CUED) {
                updateTimeAndProgress();
            } else if (state === YT.PlayerState.ENDED) {
                handleSongEnded();
            }
        }

        function onPlayerError(e) {
            const code = e.data;
            const videoId = MusicApp.currentSong ? MusicApp.currentSong.youtube_id : '';
            console.warn('YouTube Player error code:', code, 'for video:', videoId);

            MusicApp.isPlaying = false;
            updatePlayPauseIcons(false);
            stopProgressTracker();

            let errorMessage = 'Pemutaran video gagal (error ' + code + '). Silakan pilih lagu lain.';

            if (code === 101 || code === 150) {
                errorMessage = 'Pemilik video membatasi pemutaran di luar YouTube.';
                showEmbedError(videoId, errorMessage);
                showToast(errorMessage);
                return;
            }

            hideEmbedError();

            if (code === 100) {
                errorMessage = 'Video musik tidak ditemukan atau telah dihapus.';
            } else if (code === 2) {
                errorMessage = 'Format ID video tidak valid.';
            } else if (code === 5) {
                errorMessage = 'Pemutar HTML5 tidak mendukung video ini.';
            }
            showToast(errorMessage);
        }

        // Play a chosen track
        window.playSong = function (song) {
            if (!song || !song.youtube_id) return;

            MusicApp.currentSong = {
                youtube_id: song.youtube_id,
                title: song.title || 'Lagu Tanpa Judul',
                channel_title: song.channel_title || 'Artis',
                thumbnail_url: song.thumbnail_url || 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=120&q=80',
            };

            saveCurrentState();
            renderPlayerBar();
            hideEmbedError();

            // If player not yet ready, save pending and wait for onPlayerReady
            if (!MusicApp.isReady || !MusicApp.ytPlayer || typeof MusicApp.ytPlayer.loadVideoById !== 'function') {
                MusicApp.pendingSong = song;
                showToast('Menyiapkan pemutar audio...');
                initYouTubeAPI();
                return;
            }

            try {
                if (MusicApp.isMuted) {
                    try { MusicApp.ytPlayer.mute(); } catch (e) {}
                } else {
                    try {
                        MusicApp.ytPlayer.unMute();
                        MusicApp.ytPlayer.setVolume(MusicApp.volume);
                    } catch (e) {}
                }

                MusicApp.ytPlayer.loadVideoById(song.youtube_id);
                if (typeof MusicApp.ytPlayer.playVideo === 'function') {
                    MusicApp.ytPlayer.playVideo();
                }
            } catch (err) {
                console.warn('loadVideoById error:', err);
                MusicApp.pendingSong = song;
            }

            // Post to history backend
            recordHistory(MusicApp.currentSong);
            // Check favorites status
            checkFavoriteStatus(song.youtube_id);
        };

        // Extract song payload safely from HTML5 data attributes
        window.extractSongData = function (el) {
            if (!el) return null;
            const target = el.closest('[data-youtube-id]') || el;
            return {
                youtube_id: target.getAttribute('data-youtube-id') || '',
                title: target.getAttribute('data-title') || '',
                channel_title: target.getAttribute('data-channel') || '',
                thumbnail_url: target.getAttribute('data-thumbnail') || '',
            };
        };

        window.playFromEl = function (el) {
            const song = extractSongData(el);
            if (song && song.youtube_id) {
                playSong(song);
            }
        };

        window.queueFromEl = function (el) {
            const song = extractSongData(el);
            if (song && song.youtube_id) {
                addToQueue(song);
            }
        };

        window.favFromEl = function (el) {
            const song = extractSongData(el);
            if (song && song.youtube_id) {
                toggleSongFavorite(song, el);
            }
        };

        window.playlistFromEl = function (el) {
            const song = extractSongData(el);
            if (song && song.youtube_id) {
                openAddToPlaylistModal(song);
            }
        };

        // UI Updates for Player Bar
        function renderPlayerBar() {
            const s = MusicApp.currentSong;
            if (!s) return;

            document.getElementById('player-title').textContent = s.title;
            document.getElementById('player-artist').textContent = s.channel_title;
            const thumb = document.getElementById('player-thumb');
            if (thumb) thumb.src = s.thumbnail_url;

            // Now playing modal sync
            const npTitle = document.getElementById('np-title');
            const npArtist = document.getElementById('np-artist');
            const npArt = document.getElementById('np-artwork');
            if (npTitle) npTitle.textContent = s.title;
            if (npArtist) npArtist.textContent = s.channel_title;
            if (npArt) npArt.src = s.thumbnail_url;

            // Shuffle & Repeat icons sync
            updateShuffleRepeatUI();
        }

        function updatePlayPauseIcons(playing) {
            const iconPlay = document.getElementById('icon-play');
            const iconPause = document.getElementById('icon-pause');
            const npPlay = document.getElementById('np-icon-play');
            const npPause = document.getElementById('np-icon-pause');

            if (iconPlay && iconPause) {
                iconPlay.classList.toggle('hidden', playing);
                iconPause.classList.toggle('hidden', !playing);
            }
            if (npPlay && npPause) {
                npPlay.classList.toggle('hidden', playing);
                npPause.classList.toggle('hidden', !playing);
            }
        }

        window.togglePlayPause = function () {
            if (!MusicApp.currentSong) return;

            if (!MusicApp.isReady || !MusicApp.ytPlayer || typeof MusicApp.ytPlayer.getPlayerState !== 'function') {
                window.playSong(MusicApp.currentSong);
                return;
            }

            try {
                const state = MusicApp.ytPlayer.getPlayerState();
                if (state === YT.PlayerState.PLAYING) {
                    MusicApp.ytPlayer.pauseVideo();
                } else if (state === YT.PlayerState.PAUSED || state === YT.PlayerState.CUED) {
                    MusicApp.ytPlayer.playVideo();
                } else {
                    MusicApp.ytPlayer.loadVideoById(MusicApp.currentSong.youtube_id);
                    if (typeof MusicApp.ytPlayer.playVideo === 'function') {
                        MusicApp.ytPlayer.playVideo();
                    }
                }
            } catch (e) {
                console.warn('togglePlayPause fallback:', e);
                window.playSong(MusicApp.currentSong);
            }
        };

        // Progress & Seeking
        function startProgressTracker() {
            stopProgressTracker();
            MusicApp.progressTimer = setInterval(updateTimeAndProgress, 400);
        }

        function stopProgressTracker() {
            if (MusicApp.progressTimer) {
                clearInterval(MusicApp.progressTimer);
                MusicApp.progressTimer = null;
            }
        }

        function updateTimeAndProgress() {
            if (!MusicApp.isReady || !MusicApp.ytPlayer) return;

            let cur = 0;
            let dur = 0;
            try {
                if (typeof MusicApp.ytPlayer.getCurrentTime === 'function') {
                    cur = MusicApp.ytPlayer.getCurrentTime() || 0;
                }
                if (typeof MusicApp.ytPlayer.getDuration === 'function') {
                    dur = MusicApp.ytPlayer.getDuration() || 0;
                }
            } catch (e) {}

            if (dur > 0) {
                const pct = Math.min(100, (cur / dur) * 100);
                const fill = document.getElementById('progress-fill');
                const npFill = document.getElementById('np-progress-fill');
                if (fill) fill.style.width = pct + '%';
                if (npFill) npFill.style.width = pct + '%';

                const curFormatted = formatSeconds(cur);
                const durFormatted = formatSeconds(dur);

                const elCur = document.getElementById('player-cur-time');
                const elDur = document.getElementById('player-dur-time');
                if (elCur) elCur.textContent = curFormatted;
                if (elDur) elDur.textContent = durFormatted;

                const npCur = document.getElementById('np-cur-time');
                const npDur = document.getElementById('np-dur-time');
                if (npCur) npCur.textContent = curFormatted;
                if (npDur) npDur.textContent = durFormatted;
            }
        }

        window.playerSeek = function (e) {
            if (!MusicApp.isReady || !MusicApp.ytPlayer || !MusicApp.ytPlayer.getDuration) return;

            const rail = e.currentTarget;
            const rect = rail.getBoundingClientRect();
            const clickPos = (e.clientX - rect.left) / rect.width;
            const targetTime = clickPos * (MusicApp.ytPlayer.getDuration() || 0);

            MusicApp.ytPlayer.seekTo(targetTime, true);
        };

        function formatSeconds(sec) {
            const m = Math.floor(sec / 60);
            const s = Math.floor(sec % 60);
            return m + ':' + (s < 10 ? '0' : '') + s;
        }

        // Next & Previous
        window.playNextSong = function () {
            if (MusicApp.queue.length > 0) {
                const next = MusicApp.queue.shift();
                playSong(next);
                renderQueueUI();
                saveCurrentState();
            } else if (MusicApp.repeatMode === 1 && MusicApp.currentSong) {
                MusicApp.ytPlayer.seekTo(0);
                MusicApp.ytPlayer.playVideo();
            }
        };

        window.playPreviousSong = function () {
            if (!MusicApp.isReady || !MusicApp.ytPlayer) return;
            try {
                if (typeof MusicApp.ytPlayer.seekTo === 'function') {
                    MusicApp.ytPlayer.seekTo(0, true);
                    MusicApp.ytPlayer.playVideo();
                }
            } catch (e) {}
        };

        function handleSongEnded() {
            if (MusicApp.repeatMode === 2) {
                // Repeat single
                MusicApp.ytPlayer.seekTo(0);
                MusicApp.ytPlayer.playVideo();
                return;
            }

            if (MusicApp.queue.length > 0) {
                playNextSong();
            } else {
                MusicApp.isPlaying = false;
                updatePlayPauseIcons(false);
                stopProgressTracker();
            }
        }

        // Shuffle & Repeat
        window.toggleShuffle = function () {
            MusicApp.isShuffle = !MusicApp.isShuffle;
            if (MusicApp.isShuffle && MusicApp.queue.length > 1) {
                MusicApp.queue.sort(() => Math.random() - 0.5);
                renderQueueUI();
            }
            updateShuffleRepeatUI();
            saveCurrentState();
        };

        window.toggleRepeat = function () {
            MusicApp.repeatMode = (MusicApp.repeatMode + 1) % 3; // 0: off, 1: all, 2: one
            updateShuffleRepeatUI();
            saveCurrentState();
        };

        function updateShuffleRepeatUI() {
            const shufBtn = document.getElementById('player-shuffle-btn');
            if (shufBtn) {
                shufBtn.classList.toggle('text-violet-400', MusicApp.isShuffle);
                shufBtn.classList.toggle('text-slate-400', !MusicApp.isShuffle);
            }

            const repBtn = document.getElementById('player-repeat-btn');
            if (repBtn) {
                repBtn.classList.toggle('text-violet-400', MusicApp.repeatMode > 0);
                repBtn.classList.toggle('text-slate-400', MusicApp.repeatMode === 0);
            }
        }

        // Volume Controls
        window.changeVolume = function (val) {
            MusicApp.volume = parseInt(val, 10);
            MusicApp.isMuted = MusicApp.volume === 0;

            if (MusicApp.isReady && MusicApp.ytPlayer) {
                MusicApp.ytPlayer.unMute();
                MusicApp.ytPlayer.setVolume(MusicApp.volume);
            }
            updateVolumeUI();
            saveCurrentState();
        };

        window.toggleMute = function () {
            MusicApp.isMuted = !MusicApp.isMuted;
            if (MusicApp.isReady && MusicApp.ytPlayer) {
                if (MusicApp.isMuted) {
                    MusicApp.ytPlayer.mute();
                } else {
                    MusicApp.ytPlayer.unMute();
                    MusicApp.ytPlayer.setVolume(MusicApp.volume);
                }
            }
            updateVolumeUI();
            saveCurrentState();
        };

        function updateVolumeUI() {
            const high = document.getElementById('icon-vol-high');
            const mute = document.getElementById('icon-vol-mute');
            if (high && mute) {
                high.classList.toggle('hidden', MusicApp.isMuted);
                mute.classList.toggle('hidden', !MusicApp.isMuted);
            }
            const slider = document.getElementById('player-volume-slider');
            if (slider) {
                slider.value = MusicApp.isMuted ? 0 : MusicApp.volume;
            }
        }

        // Queue Functions
        window.addToQueue = function (song) {
            if (!song || !song.youtube_id) return;
            MusicApp.queue.push({
                youtube_id: song.youtube_id,
                title: song.title || 'Lagu',
                channel_title: song.channel_title || '',
                thumbnail_url: song.thumbnail_url || '',
            });
            renderQueueUI();
            saveCurrentState();
            showToast('Lagu ditambahkan ke antrian');
        };

        window.clearQueue = function () {
            MusicApp.queue = [];
            renderQueueUI();
            saveCurrentState();
            showToast('Antrian telah dikosongkan');
        };

        window.toggleQueueDrawer = function () {
            const drawer = document.getElementById('queue-drawer');
            if (drawer) {
                drawer.classList.toggle('hidden');
                if (!drawer.classList.contains('hidden')) {
                    renderQueueUI();
                }
            }
        };

        function renderQueueUI() {
            const countText = document.getElementById('queue-count-text');
            const container = document.getElementById('queue-items-container');
            const badge = document.getElementById('queue-badge');

            if (badge) {
                badge.classList.toggle('hidden', MusicApp.queue.length === 0);
            }

            if (countText) {
                countText.textContent = `${MusicApp.queue.length} lagu dalam antrian`;
            }

            if (!container) return;

            if (MusicApp.queue.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 text-slate-500">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <p class="text-xs">Antrian sedang kosong.</p>
                        <p class="text-[10px] mt-1 text-slate-600">Gunakan tombol + pada lagu untuk menambahkan.</p>
                    </div>`;
                return;
            }

            container.innerHTML = MusicApp.queue.map((s, index) => `
                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-white/[0.03] hover:bg-white/[0.08] transition group">
                    <span class="text-[10px] font-mono text-slate-500 w-4 text-center shrink-0">${index + 1}</span>
                    <div class="w-9 h-9 rounded-lg overflow-hidden shrink-0 bg-black/40">
                        <img src="${s.thumbnail_url}" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1 cursor-pointer" onclick="playQueueIndex(${index})">
                        <p class="text-xs font-bold text-white truncate leading-tight group-hover:text-violet-300 transition-colors">${escapeHtml(s.title)}</p>
                        <p class="text-[10px] text-slate-400 truncate mt-0.5">${escapeHtml(s.channel_title)}</p>
                    </div>
                    <button type="button" onclick="removeQueueIndex(${index})" class="p-1 text-slate-500 hover:text-red-400 transition" title="Hapus dari antrian">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `).join('');
        }

        window.playQueueIndex = function (index) {
            if (MusicApp.queue[index]) {
                const song = MusicApp.queue.splice(index, 1)[0];
                playSong(song);
                renderQueueUI();
                saveCurrentState();
            }
        };

        window.removeQueueIndex = function (index) {
            MusicApp.queue.splice(index, 1);
            renderQueueUI();
            saveCurrentState();
        };

        // Fullscreen Now Playing Modal
        window.openNowPlayingModal = function () {
            if (!MusicApp.currentSong) return;
            const modal = document.getElementById('now-playing-modal');
            if (modal) {
                modal.classList.remove('hidden');
                renderPlayerBar();
            }
        };

        window.closeNowPlayingModal = function () {
            const modal = document.getElementById('now-playing-modal');
            if (modal) modal.classList.add('hidden');
        };

        // Favorites Engine
        window.toggleSongFavorite = async function (song, btnEl) {
            if (!song || !song.youtube_id) return;
            try {
                const res = await fetch('/api/lifehub/music/favorites/toggle', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        youtube_id: song.youtube_id,
                        title: song.title,
                        channel_title: song.channel_title || '',
                        thumbnail_url: song.thumbnail_url || ''
                    })
                });
                const data = await res.json();
                if (data.status === 'added') {
                    showToast('Lagu ditambahkan ke Favorit');
                    if (btnEl) updateFavBtnStyle(btnEl, true);
                } else {
                    showToast('Lagu dihapus dari Favorit');
                    if (btnEl) updateFavBtnStyle(btnEl, false);
                }
            } catch (err) {
                console.warn('Favorite error:', err);
            }
        };

        window.toggleCurrentSongFavorite = function () {
            if (!MusicApp.currentSong) return;
            const barBtn = document.getElementById('player-fav-btn');
            window.toggleSongFavorite(MusicApp.currentSong, barBtn);
        };

        async function checkFavoriteStatus(youtubeId) {
            try {
                const res = await fetch('/api/lifehub/music/favorites', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                const isFav = (data.items || []).some(f => f.youtube_id === youtubeId);
                const barBtn = document.getElementById('player-fav-btn');
                const npIcon = document.getElementById('np-fav-icon');
                if (barBtn) updateFavBtnStyle(barBtn, isFav);
                if (npIcon) {
                    npIcon.classList.toggle('text-violet-400', isFav);
                    npIcon.setAttribute('fill', isFav ? 'currentColor' : 'none');
                }
            } catch (e) {}
        }

        function updateFavBtnStyle(btn, isFav) {
            const icon = btn.querySelector('svg');
            if (icon) {
                icon.classList.toggle('text-violet-400', isFav);
                icon.classList.toggle('text-slate-400', !isFav);
                icon.setAttribute('fill', isFav ? 'currentColor' : 'none');
            }
        }

        // History recorder
        async function recordHistory(song) {
            try {
                await fetch('/api/lifehub/music/history', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(song)
                });
            } catch (e) {}
        }

        // Mobile Navigation Drawer
        window.openMobileNavDrawer = function () {
            const drawer = document.getElementById('mobile-nav-drawer');
            if (drawer) {
                drawer.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeMobileNavDrawer = function () {
            const drawer = document.getElementById('mobile-nav-drawer');
            if (drawer) {
                drawer.classList.add('hidden');
                document.body.style.overflow = '';
            }
        };

        // Playlist Modals & Actions
        window.openCreatePlaylistModal = function () {
            document.getElementById('create-playlist-modal').classList.remove('hidden');
            document.getElementById('new-pl-name').focus();
        };

        window.closeCreatePlaylistModal = function () {
            document.getElementById('create-playlist-modal').classList.add('hidden');
            document.getElementById('new-pl-name').value = '';
            document.getElementById('new-pl-desc').value = '';
        };

        window.submitCreatePlaylist = async function () {
            const nameInput = document.getElementById('new-pl-name');
            const descInput = document.getElementById('new-pl-desc');
            const name = nameInput.value.trim();
            if (!name) {
                alert('Nama playlist wajib diisi.');
                return;
            }

            try {
                const res = await fetch('/api/lifehub/music/playlists', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        description: descInput.value.trim()
                    })
                });

                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.message || 'Gagal membuat playlist');
                }

                closeCreatePlaylistModal();
                showToast('Playlist berhasil dibuat!');
                // Reload if on playlists page
                if (window.location.pathname.includes('/playlists')) {
                    window.location.reload();
                }
            } catch (err) {
                alert(err.message);
            }
        };

        window.openAddToPlaylistModal = async function (song) {
            MusicApp.activeSongToAdd = song;
            const modal = document.getElementById('add-to-playlist-modal');
            const listEl = document.getElementById('add-to-playlist-list');
            modal.classList.remove('hidden');
            listEl.innerHTML = '<p class="text-xs text-slate-500 py-6 text-center">Memuat playlist Anda...</p>';

            try {
                // Fetch playlists
                const res = await fetch('/lifehub/music/playlists', {
                    headers: { 'Accept': 'application/json' }
                });
                // Fallback to custom playlists endpoint if needed
                const plRes = await fetch('/api/lifehub/music/playlists/0').catch(() => null);
            } catch (e) {}

            // Populate playlists from DOM or API
            populateAddToPlaylistDOM(song);
        };

        window.showAddToPlaylistModalCurrent = function () {
            if (MusicApp.currentSong) {
                openAddToPlaylistModal(MusicApp.currentSong);
            }
        };

        window.closeAddToPlaylistModal = function () {
            document.getElementById('add-to-playlist-modal').classList.add('hidden');
            MusicApp.activeSongToAdd = null;
        };

        async function populateAddToPlaylistDOM(song) {
            const listEl = document.getElementById('add-to-playlist-list');
            try {
                const res = await fetch('/api/lifehub/music/history'); // quick ping test or fetch playlists
            } catch (e) {}

            // Fetch user's playlists
            fetch('/lifehub/music/playlists')
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const cards = doc.querySelectorAll('[data-playlist-id]');
                    if (cards.length === 0) {
                        listEl.innerHTML = `
                            <div class="text-center py-6">
                                <p class="text-xs text-slate-400">Belum ada playlist.</p>
                                <button type="button" onclick="closeAddToPlaylistModal(); openCreatePlaylistModal();"
                                        class="mt-3 px-3 py-1.5 rounded-lg bg-violet-600 text-white text-xs font-bold">
                                    + Buat Playlist Sekarang
                                </button>
                            </div>`;
                        return;
                    }

                    listEl.innerHTML = Array.from(cards).map(card => {
                        const id = card.getAttribute('data-playlist-id');
                        const name = card.getAttribute('data-playlist-name') || 'Playlist';
                        return `
                            <button type="button" onclick="executeAddToPlaylist(${id})"
                                    class="w-full flex items-center justify-between p-3 rounded-xl bg-white/[0.03] hover:bg-violet-600/20 hover:border-violet-500/40 border border-transparent transition text-left group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-violet-950/60 border border-violet-500/30 flex items-center justify-center text-violet-400 shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                        </svg>
                                    </div>
                                    <span class="text-xs font-bold text-white group-hover:text-violet-300 transition-colors">${escapeHtml(name)}</span>
                                </div>
                                <span class="text-[11px] text-violet-400 font-semibold">+ Tambah</span>
                            </button>
                        `;
                    }).join('');
                })
                .catch(() => {
                    listEl.innerHTML = '<p class="text-xs text-red-400 py-4 text-center">Gagal memuat daftar playlist</p>';
                });
        }

        window.executeAddToPlaylist = async function (playlistId) {
            const song = MusicApp.activeSongToAdd;
            if (!song || !song.youtube_id) return;

            try {
                const res = await fetch(`/api/lifehub/music/playlists/${playlistId}/items`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        youtube_id: song.youtube_id,
                        title: song.title,
                        channel_title: song.channel_title || '',
                        thumbnail_url: song.thumbnail_url || ''
                    })
                });

                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.error || data.message || 'Gagal menambahkan lagu.');
                }

                closeAddToPlaylistModal();
                showToast('Lagu berhasil ditambahkan ke playlist!');
            } catch (err) {
                alert(err.message);
            }
        };

        // Utility: Toast Notification
        function showToast(msg) {
            let toast = document.getElementById('music-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'music-toast';
                toast.className = 'fixed top-4 right-4 z-[99] px-4 py-2.5 rounded-xl bg-violet-600 text-white text-xs font-semibold shadow-2xl transition-all duration-300 pointer-events-none transform translate-y-[-20px] opacity-0';
                document.body.appendChild(toast);
            }

            toast.textContent = msg;
            toast.classList.remove('translate-y-[-20px]', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-[-20px]', 'opacity-0');
            }, 2500);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // ── Search Autocomplete Engine ────────────────────────────────
        function initSearchAutocomplete(inputId, boxId, formId) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            const form = document.getElementById(formId);
            if (!input || !box) return;

            let debounceTimer = null;
            let activeIndex = -1;
            let currentSuggestions = [];

            function closeBox() {
                box.classList.add('hidden');
                box.innerHTML = '';
                activeIndex = -1;
                currentSuggestions = [];
            }

            function renderSuggestions(suggestions) {
                currentSuggestions = suggestions;
                if (!suggestions || suggestions.length === 0) {
                    closeBox();
                    return;
                }

                box.innerHTML = suggestions.map((sug, idx) => {
                    let iconHtml = `
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>`;
                    if (sug.type === 'history') {
                        iconHtml = `
                            <svg class="w-3.5 h-3.5 text-violet-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>`;
                    } else if (sug.type === 'favorite') {
                        iconHtml = `
                            <svg class="w-3.5 h-3.5 text-rose-400 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>`;
                    }

                    return `
                        <div class="sug-item flex items-center justify-between px-3.5 py-2.5 hover:bg-violet-600/20 cursor-pointer transition text-xs select-none"
                             data-index="${idx}">
                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                ${iconHtml}
                                <span class="text-slate-200 font-medium truncate">${escapeHtml(sug.text)}</span>
                            </div>
                            <span class="text-[10px] text-slate-500 shrink-0 ml-2">${escapeHtml(sug.sub || '')}</span>
                        </div>
                    `;
                }).join('');

                box.classList.remove('hidden');
                activeIndex = -1;

                box.querySelectorAll('.sug-item').forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const idx = parseInt(item.getAttribute('data-index'), 10);
                        selectSuggestion(idx);
                    });
                });
            }

            function selectSuggestion(index) {
                const sug = currentSuggestions[index];
                if (!sug) return;

                input.value = sug.text;
                closeBox();

                if (form) {
                    form.submit();
                }
            }

            function highlightItem(index) {
                const items = box.querySelectorAll('.sug-item');
                items.forEach((it, i) => {
                    if (i === index) {
                        it.classList.add('bg-violet-600/30', 'text-white');
                        it.scrollIntoView({ block: 'nearest' });
                    } else {
                        it.classList.remove('bg-violet-600/30', 'text-white');
                    }
                });
            }

            // Input event with 300ms debounce
            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                const q = input.value.trim();
                if (q.length < 2) {
                    closeBox();
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    try {
                        const res = await fetch(`/api/lifehub/music/suggestions?q=${encodeURIComponent(q)}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (res.ok) {
                            const data = await res.json();
                            renderSuggestions(data.suggestions || []);
                        }
                    } catch (e) {
                        closeBox();
                    }
                }, 300);
            });

            // Keyboard navigation
            input.addEventListener('keydown', (e) => {
                if (box.classList.contains('hidden') || currentSuggestions.length === 0) {
                    return;
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = (activeIndex + 1) % currentSuggestions.length;
                    highlightItem(activeIndex);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = (activeIndex - 1 + currentSuggestions.length) % currentSuggestions.length;
                    highlightItem(activeIndex);
                } else if (e.key === 'Enter') {
                    if (activeIndex >= 0 && activeIndex < currentSuggestions.length) {
                        e.preventDefault();
                        selectSuggestion(activeIndex);
                    }
                } else if (e.key === 'Escape') {
                    closeBox();
                }
            });

            // Close on click outside
            document.addEventListener('click', (e) => {
                if (form && !form.contains(e.target)) {
                    closeBox();
                }
            });
        }

        // Initialize On Load
        document.addEventListener('DOMContentLoaded', () => {
            loadSavedState();
            initYouTubeAPI();
            renderPlayerBar();
            updateVolumeUI();
            initSearchAutocomplete('global-search-input', 'search-suggestions-box', 'top-search-form');
            initSearchAutocomplete('page-search-input', 'page-search-suggestions-box', 'page-search-form');
        });

    })();
    </script>

    @include('partials.page-transitions')
</body>
</html>

