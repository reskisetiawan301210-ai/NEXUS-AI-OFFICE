@extends('lifehub.music.layout')

@section('title', 'Pencarian Musik')

@section('content')
<div class="px-4 sm:px-8 py-6 max-w-7xl mx-auto space-y-6">

    <!-- Search Bar Hero -->
    <div class="space-y-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Pencarian Musik</h1>
            <p class="text-xs text-slate-400">Temukan lagu, artis, atau genre favorit langsung dari YouTube Music</p>
        </div>

        <form action="{{ route('lifehub.music.search.page') }}" method="GET" class="relative max-w-2xl" id="page-search-form" autocomplete="off">
            <div class="relative">
                <button type="submit" class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 hover:text-violet-400 transition" title="Mulai Cari">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <input type="text"
                       name="q"
                       id="page-search-input"
                       value="{{ $query }}"
                       autofocus
                       placeholder="Ketik judul lagu, penyanyi, atau band..."
                       autocomplete="off"
                       class="w-full pl-12 pr-12 py-3 rounded-2xl bg-white/[0.06] border border-white/10 text-sm text-white placeholder-slate-400 focus:bg-[#12131C] focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none transition cursor-text shadow-lg">
                @if($query)
                    <a href="{{ route('lifehub.music.search.page') }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-white" title="Hapus">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>

            <!-- Page Autocomplete Dropdown -->
            <div id="page-search-suggestions-box"
                 class="absolute left-0 right-0 top-full mt-1.5 bg-[#12131C]/95 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50 hidden divide-y divide-white/[0.04] max-h-80 overflow-y-auto">
            </div>
        </form>

        <!-- Quick Tags -->
        <div class="flex flex-wrap items-center gap-2 pt-1">
            <span class="text-[11px] text-slate-500 font-medium">Saran pencarian:</span>
            @foreach(['Hindia', 'Nadin Amizah', 'Sal Priadi', 'Tulus', 'Coldplay', 'Lo-Fi Chill Beats', 'Pop Indonesia Viral'] as $tag)
                <a href="{{ route('lifehub.music.search.page', ['q' => $tag]) }}"
                   class="px-2.5 py-1 rounded-lg bg-white/[0.04] hover:bg-violet-600/20 border border-white/[0.06] hover:border-violet-500/30 text-[11px] text-slate-300 hover:text-violet-200 transition">
                    {{ $tag }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Results Section -->
    @if($error)
        <div class="p-6 rounded-2xl bg-red-950/30 border border-red-500/30 text-center max-w-lg mx-auto my-8">
            <svg class="w-10 h-10 text-red-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <h3 class="text-sm font-bold text-red-300 mb-1">Gagal Menemukan Musik</h3>
            <p class="text-xs text-red-200/80 mb-4">{{ $error }}</p>
            <a href="{{ route('lifehub.music.search.page', ['q' => $query]) }}" class="px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold transition">
                Coba Lagi
            </a>
        </div>
    @elseif(!empty($query))
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-white">
                    Hasil untuk <span class="text-violet-400">&ldquo;{{ $query }}&rdquo;</span>
                </h2>
                <span class="text-xs text-slate-500">{{ count($results) }} lagu ditemukan</span>
            </div>

            @if(empty($results))
                <div class="card-obsidian rounded-2xl p-12 text-center max-w-md mx-auto my-8">
                    <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="text-sm font-bold text-white mb-1">Tidak ada hasil ditemukan</h3>
                    <p class="text-xs text-slate-400">Coba gunakan kata kunci lain atau periksa ejaan judul lagu/artis.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5">
                    @foreach($results as $song)
                        @php
                            $isFav = in_array($song['youtube_id'], $userFavoriteIds);
                        @endphp
                        <div class="card-obsidian rounded-2xl p-3 flex flex-col justify-between group cursor-pointer"
                             data-youtube-id="{{ $song['youtube_id'] }}"
                             data-title="{{ $song['title'] }}"
                             data-channel="{{ $song['channel_title'] }}"
                             data-thumbnail="{{ $song['thumbnail_url'] }}"
                             onclick="playFromEl(this)">
                            <div>
                                <div class="w-full aspect-video rounded-xl overflow-hidden bg-black/50 relative mb-2.5">
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

                            <!-- Actions -->
                            <div class="flex items-center justify-end gap-1.5 pt-2 mt-2 border-t border-white/[0.04]" onclick="event.stopPropagation();">
                                <button type="button" onclick="queueFromEl(this)" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Antrian">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                                <button type="button" onclick="favFromEl(this)" class="p-1.5 rounded-lg {{ $isFav ? 'text-violet-400' : 'text-slate-400 hover:text-violet-400' }} hover:bg-white/5 transition" title="Favorit">
                                    <svg class="w-4 h-4" fill="{{ $isFav ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                </button>
                                <button type="button" onclick="playlistFromEl(this)" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Playlist">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <!-- Search landing state -->
        <div class="card-obsidian rounded-3xl p-10 text-center max-w-xl mx-auto my-8 space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-violet-950/60 border border-violet-500/30 flex items-center justify-center text-violet-400 mx-auto">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-white">Mulai Eksplorasi Musik</h3>
            <p class="text-xs text-slate-400 leading-relaxed max-w-sm mx-auto">
                Ketik nama artis atau judul lagu pada kolom di atas untuk mencari jutaan track musik resmi.
            </p>
        </div>
    @endif

</div>
@endsection

