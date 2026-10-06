@extends('lifehub.music.layout')

@section('title', 'Trending Musik')

@section('content')
<div class="px-4 sm:px-8 py-6 max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight flex items-center gap-2.5">
                <span>Trending Musik</span>
                <span class="px-2.5 py-0.5 rounded-full bg-violet-950/70 border border-violet-500/30 text-violet-300 text-xs font-semibold">
                    {{ $regions[$currentRegion] ?? 'Indonesia' }}
                </span>
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">Daftar video musik paling banyak diputar saat ini</p>
        </div>

        <!-- Regional selector tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            @foreach($regions as $code => $label)
                <a href="{{ route('lifehub.music.trending.page', ['region' => $code]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-semibold border transition shrink-0 {{ $currentRegion === $code ? 'bg-violet-600 text-white border-violet-500 shadow-sm' : 'bg-white/[0.04] text-slate-400 hover:text-white border-white/[0.06] hover:bg-white/[0.08]' }}">
                    @if($code === 'ID') 🇮🇩 @elseif($code === 'GLOBAL') 🌍 @elseif($code === 'US') 🇺🇸 @elseif($code === 'GB') 🇬🇧 @elseif($code === 'KR') 🇰🇷 @elseif($code === 'JP') 🇯🇵 @elseif($code === 'BR') 🇧🇷 @endif
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Error or Results -->
    @if($error)
        <div class="p-8 rounded-2xl bg-red-950/30 border border-red-500/30 text-center max-w-lg mx-auto my-8">
            <svg class="w-10 h-10 text-red-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <h3 class="text-sm font-bold text-red-300 mb-1">Tidak Dapat Memuat Trending</h3>
            <p class="text-xs text-red-200/80 mb-4">{{ $error }}</p>
            <a href="{{ route('lifehub.music.trending.page', ['region' => $currentRegion]) }}" class="px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold transition">
                Muat Ulang
            </a>
        </div>
    @elseif(empty($items))
        <div class="card-obsidian rounded-2xl p-12 text-center max-w-md mx-auto my-8">
            <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            <h3 class="text-sm font-bold text-white mb-1">Data trending sedang tidak tersedia</h3>
            <p class="text-xs text-slate-400">Silakan pilih wilayah lain di atas.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5">
            @foreach($items as $index => $song)
                @php
                    $isFav = in_array($song['youtube_id'], $userFavoriteIds);
                @endphp
                <div class="card-obsidian rounded-2xl p-3 flex flex-col justify-between group cursor-pointer relative"
                     data-youtube-id="{{ $song['youtube_id'] }}"
                     data-title="{{ $song['title'] }}"
                     data-channel="{{ $song['channel_title'] }}"
                     data-thumbnail="{{ $song['thumbnail_url'] }}"
                     onclick="playFromEl(this)">
                    
                    <!-- Ranking Badge -->
                    <div class="absolute top-4 left-4 z-10 px-2 py-0.5 rounded-lg {{ $index < 3 ? 'bg-amber-500/90 text-amber-950 font-extrabold' : 'bg-black/60 text-white/90 font-bold' }} text-[11px] backdrop-blur-md shadow-md">
                        #{{ $index + 1 }}
                    </div>

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
@endsection

