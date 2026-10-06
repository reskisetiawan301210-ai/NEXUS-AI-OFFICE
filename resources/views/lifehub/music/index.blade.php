@extends('lifehub.music.layout')

@section('title', 'Beranda')

@section('content')
<div class="px-4 sm:px-8 py-6 max-w-7xl mx-auto space-y-8">

    @php
        $hour = (int) now()->format('H');
        if ($hour >= 4 && $hour < 11) {
            $greetingText = 'Selamat pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greetingText = 'Selamat siang';
        } elseif ($hour >= 15 && $hour < 19) {
            $greetingText = 'Selamat sore';
        } else {
            $greetingText = 'Selamat malam';
        }
    @endphp

    <!-- ════════════════════════════════════════════════════════════
         1. CLEAN CINEMATIC HERO (NO BLUR, VIVID SCENERY)
         ════════════════════════════════════════════════════════════ -->
    <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/10 bg-[#0F1018] group">
        <!-- Natural Landscape Photo without heavy blur -->
        <img src="{{ asset('images/hero-landscape.jpg') }}"
             alt="LifeHub AI Music Ambient"
             class="absolute inset-0 w-full h-full object-cover object-center transform group-hover:scale-[1.02] transition-transform duration-700 select-none">

        <!-- Subtle directional dark gradient: left is readable, right scenery stays vivid -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#090A0F]/95 via-[#090A0F]/65 to-transparent pointer-events-none"></div>

        <!-- Hero Content Layer -->
        <div class="relative z-10 p-6 sm:p-10 md:p-12 flex flex-col justify-end min-h-[220px] sm:min-h-[280px] max-w-2xl">
            <!-- Dynamic Real Greeting & Real Auth User -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-950/70 border border-violet-500/30 text-violet-300 text-xs font-semibold w-fit mb-3 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></span>
                <span>{{ $greetingText }}, {{ $user->name }}</span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-2">
                Fokus dan Berkarya dengan Audio Premium
            </h1>
            <p class="text-xs sm:text-sm text-slate-200/90 leading-relaxed mb-6 max-w-lg">
                Temani alur kerja harian Anda dengan ribuan lagu, tangga lagu global, dan daftar putar personal tanpa gangguan.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                @if(!empty($discover) && isset($discover[0]))
                    <button type="button"
                            data-youtube-id="{{ $discover[0]['youtube_id'] }}"
                            data-title="{{ $discover[0]['title'] }}"
                            data-channel="{{ $discover[0]['channel_title'] }}"
                            data-thumbnail="{{ $discover[0]['thumbnail_url'] }}"
                            onclick="playFromEl(this)"
                            class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold shadow-lg shadow-violet-600/30 flex items-center gap-2 transition hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                        <span>Mulai Putar Musik</span>
                    </button>
                @endif

                <a href="{{ route('lifehub.music.trending.page') }}"
                   class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white border border-white/10 text-xs font-semibold backdrop-blur-md transition">
                    Jelajahi Trending
                </a>
            </div>
        </div>
    </div>

    @if(!$youtubeConfigured)
    <div class="p-4 rounded-2xl bg-amber-950/40 border border-amber-500/30 text-amber-200 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
        <div>
            <h4 class="text-xs font-bold text-amber-300 uppercase tracking-wide">YouTube API Key Belum Dikonfigurasi</h4>
            <p class="text-xs text-amber-200/80 mt-0.5">
                Tambahkan <code class="px-1 py-0.5 rounded bg-black/40 text-amber-300">YOUTUBE_API_KEY</code> pada file <code class="px-1 py-0.5 rounded bg-black/40 text-amber-300">.env</code> untuk mengaktifkan pencarian live dan pemutaran chart secara optimal.
            </p>
        </div>
    </div>
    @endif

    <!-- ════════════════════════════════════════════════════════════
         2. DISCOVER / REKOMENDASI UNTUK ANDA
         ════════════════════════════════════════════════════════════ -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">Rekomendasi Untuk Anda</h2>
                <p class="text-xs text-slate-400">Pilihan musik terbaik yang disesuaikan dengan aktivitas Anda</p>
            </div>
            <a href="{{ route('lifehub.music.trending.page') }}" class="text-xs font-semibold text-violet-400 hover:text-violet-300 transition">
                Lihat Semua &rarr;
            </a>
        </div>

        @if(!empty($discover))
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($discover as $song)
                    <div class="card-obsidian rounded-2xl p-3 flex flex-col justify-between group cursor-pointer"
                         data-youtube-id="{{ $song['youtube_id'] }}"
                         data-title="{{ $song['title'] }}"
                         data-channel="{{ $song['channel_title'] }}"
                         data-thumbnail="{{ $song['thumbnail_url'] }}"
                         onclick="playFromEl(this)">
                        <div>
                            <div class="w-full aspect-video sm:aspect-square rounded-xl overflow-hidden bg-black/50 relative mb-2.5">
                                <img src="{{ $song['thumbnail_url'] }}" alt="{{ $song['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-violet-600 text-white flex items-center justify-center shadow-lg shadow-violet-600/50 transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                        <svg class="w-5 h-5 ml-0.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-xs font-bold text-white truncate leading-snug group-hover:text-violet-300 transition-colors" title="{{ $song['title'] }}">
                                {{ $song['title'] }}
                            </h3>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $song['channel_title'] }}</p>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex items-center justify-end gap-1.5 pt-2 mt-1 border-t border-white/[0.04]" onclick="event.stopPropagation();">
                            <button type="button" onclick="queueFromEl(this)" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Antrian">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                            <button type="button" onclick="favFromEl(this)" class="p-1.5 rounded-lg text-slate-400 hover:text-violet-400 hover:bg-white/5 transition" title="Favoritkan">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                            <button type="button" onclick="playlistFromEl(this)" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Playlist">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card-obsidian rounded-2xl p-8 text-center text-slate-400">
                <p class="text-xs">Rekomendasi musik belum dapat dimuat saat ini.</p>
            </div>
        @endif
    </section>

    <!-- ════════════════════════════════════════════════════════════
         3. TRENDING INDONESIA PREVIEW
         ════════════════════════════════════════════════════════════ -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-base">🇮🇩</span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">Trending di Indonesia</h2>
                    <p class="text-xs text-slate-400">Lagu-lagu yang sedang populer diputar minggu ini</p>
                </div>
            </div>
            <a href="{{ route('lifehub.music.trending.page') }}" class="text-xs font-semibold text-violet-400 hover:text-violet-300 transition">
                Buka Semua Trending &rarr;
            </a>
        </div>

        @if(!empty($trendingPreview))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach(array_slice($trendingPreview, 0, 6) as $index => $song)
                    <div class="card-obsidian rounded-xl p-2.5 flex items-center gap-3 group cursor-pointer hover:border-violet-500/30"
                         data-youtube-id="{{ $song['youtube_id'] }}"
                         data-title="{{ $song['title'] }}"
                         data-channel="{{ $song['channel_title'] }}"
                         data-thumbnail="{{ $song['thumbnail_url'] }}"
                         onclick="playFromEl(this)">
                        <span class="text-xs font-mono font-bold text-slate-500 w-5 text-center shrink-0">#{{ $index + 1 }}</span>
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-black/40 shrink-0 relative">
                            <img src="{{ $song['thumbnail_url'] }}" alt="{{ $song['title'] }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-white truncate group-hover:text-violet-300 transition-colors">{{ $song['title'] }}</h4>
                            <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $song['channel_title'] }}</p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity" onclick="event.stopPropagation();">
                            <button type="button" onclick="queueFromEl(this)" class="p-1.5 text-slate-400 hover:text-white" title="Antrian">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                            <button type="button" onclick="favFromEl(this)" class="p-1.5 text-slate-400 hover:text-violet-400" title="Favorit">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ════════════════════════════════════════════════════════════
         4. KOLEKSI & RIWAYAT SAYA (IF AVAILABLE)
         ════════════════════════════════════════════════════════════ -->
    @if($history->isNotEmpty() || $playlists->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4">
            
            <!-- History Preview -->
            @if($history->isNotEmpty())
                <section class="card-obsidian rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Baru Saja Diputar</span>
                        </h3>
                        <a href="{{ route('lifehub.music.history.page') }}" class="text-xs font-semibold text-violet-400 hover:text-violet-300">
                            Semua Riwayat &rarr;
                        </a>
                    </div>
                    <div class="space-y-1.5">
                        @foreach($history->take(4) as $item)
                            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/[0.04] transition group cursor-pointer"
                                 data-youtube-id="{{ $item->youtube_id }}"
                                 data-title="{{ $item->title }}"
                                 data-channel="{{ $item->channel_title }}"
                                 data-thumbnail="{{ $item->thumbnail_url }}"
                                 onclick="playFromEl(this)">
                                <div class="w-10 h-10 rounded-lg overflow-hidden bg-black/40 shrink-0">
                                    <img src="{{ $item->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h5 class="text-xs font-semibold text-white truncate group-hover:text-violet-300 transition-colors">{{ $item->title }}</h5>
                                    <p class="text-[10px] text-slate-400 truncate">{{ $item->channel_title }}</p>
                                </div>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $item->played_at?->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Playlists Preview -->
            @if($playlists->isNotEmpty())
                <section class="card-obsidian rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Daftar Putar Anda</span>
                        </h3>
                        <a href="{{ route('lifehub.music.playlists.page') }}" class="text-xs font-semibold text-violet-400 hover:text-violet-300">
                            Kelola Playlist &rarr;
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($playlists->take(4) as $pl)
                            <a href="{{ route('lifehub.music.playlists.page') }}" class="p-3 rounded-xl bg-white/[0.03] hover:bg-white/[0.07] border border-white/[0.04] transition group block">
                                <div class="w-8 h-8 rounded-lg bg-violet-950/60 border border-violet-500/30 flex items-center justify-center text-violet-400 mb-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                                <h4 class="text-xs font-bold text-white truncate group-hover:text-violet-300 transition-colors">{{ $pl->name }}</h4>
                                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $pl->items_count }} lagu</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

        </div>
    @endif

</div>
@endsection

