@extends('lifehub.music.layout')

@section('title', 'Lagu Favorit')

@section('content')
<div class="px-4 sm:px-8 py-6 max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight flex items-center gap-2.5">
                <span>Lagu Favorit</span>
                <span class="text-xs font-mono text-slate-500 font-normal">({{ $favorites->total() }} lagu)</span>
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">Koleksi lagu yang paling Anda sukai di LifeHub AI</p>
        </div>
    </div>

    <!-- Favorites List -->
    @if($favorites->isEmpty())
        <div class="card-obsidian rounded-3xl p-12 text-center max-w-md mx-auto my-10 space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-violet-950/60 border border-violet-500/30 flex items-center justify-center text-violet-400 mx-auto">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-white">Belum Ada Lagu Favorit</h3>
            <p class="text-xs text-slate-400 max-w-xs mx-auto">
                Klik ikon hati pada lagu apa pun untuk menyimpannya ke dalam koleksi favorit Anda.
            </p>
            <a href="{{ route('lifehub.music') }}" class="inline-block px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold transition mt-2">
                Temukan Lagu Sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5" id="favorites-grid">
            @foreach($favorites as $fav)
                <div class="card-obsidian rounded-2xl p-3 flex flex-col justify-between group cursor-pointer"
                     id="fav-card-{{ $fav->id }}"
                     data-youtube-id="{{ $fav->youtube_id }}"
                     data-title="{{ $fav->title }}"
                     data-channel="{{ $fav->channel_title }}"
                     data-thumbnail="{{ $fav->thumbnail_url }}"
                     onclick="playFromEl(this)">
                    <div>
                        <div class="w-full aspect-video rounded-xl overflow-hidden bg-black/50 relative mb-2.5">
                            <img src="{{ $fav->thumbnail_url }}" alt="{{ $fav->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full bg-violet-600 text-white flex items-center justify-center shadow-lg shadow-violet-600/50 transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                    <svg class="w-5 h-5 ml-0.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xs font-bold text-white truncate leading-snug group-hover:text-violet-300 transition-colors" title="{{ $fav->title }}">
                            {{ $fav->title }}
                        </h3>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $fav->channel_title }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-1.5 pt-2 mt-2 border-t border-white/[0.04]" onclick="event.stopPropagation();">
                        <button type="button" onclick="queueFromEl(this)" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Antrian">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </button>
                        <button type="button" data-fav-id="{{ $fav->id }}" onclick="removeFavoriteAndCard(this)" class="p-1.5 rounded-lg text-violet-400 hover:text-red-400 hover:bg-white/5 transition" title="Hapus dari Favorit">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </button>
                        <button type="button" onclick="playlistFromEl(this)" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Playlist">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($favorites->hasPages())
            <div class="pt-4 border-t border-white/[0.06]">
                {{ $favorites->links() }}
            </div>
        @endif
    @endif

</div>

<script>
async function removeFavoriteAndCard(btn) {
    const id = btn.getAttribute('data-fav-id') || btn.dataset.favId;
    const song = window.extractSongData ? window.extractSongData(btn) : null;
    if (!song) return;
    try {
        const res = await fetch('/api/lifehub/music/favorites/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                youtube_id: song.youtube_id,
                title: song.title
            })
        });
        if (res.ok) {
            const card = document.getElementById(`fav-card-${id}`);
            if (card) card.remove();
        }
    } catch (e) {
        alert('Gagal menghapus favorit');
    }
}
</script>
@endsection

