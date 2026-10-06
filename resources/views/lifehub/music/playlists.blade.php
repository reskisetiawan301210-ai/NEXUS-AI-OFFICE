@extends('lifehub.music.layout')

@section('title', 'Daftar Putar (Playlist)')

@section('content')
<div class="px-4 sm:px-8 py-6 max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Daftar Putar Saya</h1>
            <p class="text-xs text-slate-400 mt-0.5">Kelola koleksi album dan daftar putar khusus Anda</p>
        </div>

        <button type="button" onclick="openCreatePlaylistModal()"
                class="px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold shadow-lg shadow-violet-600/30 flex items-center gap-2 transition hover:scale-105 active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Playlist</span>
        </button>
    </div>

    <!-- Playlists Grid -->
    @if($playlists->isEmpty())
        <div class="card-obsidian rounded-3xl p-12 text-center max-w-md mx-auto my-10 space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-violet-950/60 border border-violet-500/30 flex items-center justify-center text-violet-400 mx-auto">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Belum Ada Playlist</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                    Kumpulkan lagu-lagu kesukaan Anda dalam satu daftar putar untuk didengarkan kapan saja.
                </p>
            </div>
            <button type="button" onclick="openCreatePlaylistModal()"
                    class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold transition">
                + Buat Playlist Sekarang
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($playlists as $pl)
                <div class="card-obsidian rounded-2xl p-4 flex flex-col justify-between group cursor-pointer"
                     data-playlist-id="{{ $pl->id }}"
                     data-playlist-name="{{ $pl->name }}"
                     onclick="openPlaylistDetailView(this.dataset.playlistId)">
                    
                    <div>
                        <!-- Playlist Visual Grid / Artwork Cover -->
                        <div class="w-full aspect-square rounded-xl bg-gradient-to-br from-violet-950/80 via-[#141522] to-black border border-white/10 p-2 grid grid-cols-2 gap-1.5 mb-3 overflow-hidden">
                            @php
                                $previewItems = $pl->items;
                            @endphp
                            @if($previewItems->count() >= 4)
                                @foreach($previewItems->take(4) as $it)
                                    <div class="rounded-lg overflow-hidden bg-black/40">
                                        <img src="{{ $it->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            @elseif($previewItems->count() > 0)
                                <div class="col-span-2 row-span-2 rounded-lg overflow-hidden bg-black/40">
                                    <img src="{{ $previewItems->first()->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="col-span-2 row-span-2 flex items-center justify-center text-violet-500/40">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <h3 class="text-sm font-bold text-white truncate leading-snug group-hover:text-violet-300 transition-colors">
                            {{ $pl->name }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5 line-clamp-2">
                            {{ $pl->description ?: 'Daftar putar personal' }}
                        </p>
                    </div>

                    <!-- Footer: Song count & Play/Detail Action -->
                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-white/[0.04]">
                        <span class="text-[11px] font-mono text-slate-400">{{ $pl->items_count }} lagu</span>
                        <div class="flex items-center gap-1.5" onclick="event.stopPropagation();">
                            <button type="button" data-playlist-id="{{ $pl->id }}" onclick="quickPlayPlaylist(this.dataset.playlistId)" class="p-2 rounded-lg bg-violet-600/30 hover:bg-violet-600 text-violet-300 hover:text-white transition" title="Putar Seluruh Playlist">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                            </button>
                            <button type="button" data-playlist-id="{{ $pl->id }}" onclick="confirmDeletePlaylist(this.dataset.playlistId)" class="p-2 rounded-lg text-slate-500 hover:text-red-400 hover:bg-white/5 transition" title="Hapus Playlist">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Playlist Detail Modal View -->
    <div id="pl-detail-modal" class="fixed inset-0 z-[85] hidden flex items-center justify-center p-4 select-none" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-md" onclick="closePlaylistDetailView()"></div>
        <div class="relative w-full max-w-2xl bg-[#10111A] border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl z-10 max-h-[85dvh] flex flex-col space-y-6">
            
            <!-- Detail Header -->
            <div class="flex items-start justify-between gap-4 pb-4 border-b border-white/[0.08]">
                <div class="space-y-1 min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-violet-400 block">Playlist Detail</span>
                    <h2 id="detail-pl-title" class="text-xl sm:text-2xl font-extrabold text-white truncate"></h2>
                    <p id="detail-pl-desc" class="text-xs text-slate-400 line-clamp-2"></p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" id="detail-pl-play-all-btn" class="px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold flex items-center gap-1.5 shadow-md transition">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                        <span>Putar Semua</span>
                    </button>
                    <button type="button" onclick="closePlaylistDetailView()" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <!-- Items List -->
            <div id="detail-pl-items" class="flex-1 overflow-y-auto space-y-1.5 pr-2">
                <!-- Dynamically loaded songs -->
            </div>
        </div>
    </div>

</div>

<script>
let currentActivePlaylistId = null;

async function openPlaylistDetailView(playlistId) {
    currentActivePlaylistId = playlistId;
    const modal = document.getElementById('pl-detail-modal');
    const title = document.getElementById('detail-pl-title');
    const desc = document.getElementById('detail-pl-desc');
    const container = document.getElementById('detail-pl-items');
    const playAllBtn = document.getElementById('detail-pl-play-all-btn');

    modal.classList.remove('hidden');
    container.innerHTML = '<p class="text-xs text-slate-500 py-10 text-center">Memuat lagu...</p>';

    try {
        const res = await fetch(`/api/lifehub/music/playlists/${playlistId}`);
        const data = await res.json();
        const pl = data.playlist;

        title.textContent = pl.name;
        desc.textContent = pl.description || 'Tidak ada deskripsi.';

        playAllBtn.onclick = () => {
            if (pl.items && pl.items.length > 0) {
                playSong(pl.items[0]);
                if (pl.items.length > 1) {
                    MusicApp.queue = pl.items.slice(1).map(i => ({
                        youtube_id: i.youtube_id,
                        title: i.title,
                        channel_title: i.channel_title,
                        thumbnail_url: i.thumbnail_url
                    }));
                }
                closePlaylistDetailView();
            }
        };

        if (!pl.items || pl.items.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 text-slate-500">
                    <p class="text-xs">Playlist ini belum memiliki lagu.</p>
                    <p class="text-[10px] text-slate-600 mt-1">Cari lagu dan klik tombol (+) untuk menambahkan.</p>
                </div>`;
            return;
        }

        const escapeAttr = (s) => String(s || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        container.innerHTML = pl.items.map((it, idx) => `
            <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/[0.04] transition group"
                 data-youtube-id="${escapeAttr(it.youtube_id)}"
                 data-title="${escapeAttr(it.title)}"
                 data-channel="${escapeAttr(it.channel_title)}"
                 data-thumbnail="${escapeAttr(it.thumbnail_url)}">
                <div class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer" onclick="playFromEl(this)">
                    <span class="text-xs font-mono text-slate-500 w-5 text-center shrink-0">${idx + 1}</span>
                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-black/40 shrink-0">
                        <img src="${escapeAttr(it.thumbnail_url)}" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-xs font-bold text-white truncate group-hover:text-violet-300 transition-colors">${escapeAttr(it.title)}</h4>
                        <p class="text-[10px] text-slate-400 truncate mt-0.5">${escapeAttr(it.channel_title || 'Artis')}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" onclick="queueFromEl(this)" class="p-1.5 text-slate-400 hover:text-white transition" title="Antrian">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </button>
                    <button type="button" onclick="deleteSongFromPlaylist(${pl.id}, ${it.id})" class="p-1.5 text-slate-500 hover:text-red-400 transition" title="Hapus dari playlist">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        `).join('');

    } catch (e) {
        container.innerHTML = '<p class="text-xs text-red-400 py-6 text-center">Gagal memuat isi playlist.</p>';
    }
}

function closePlaylistDetailView() {
    document.getElementById('pl-detail-modal').classList.add('hidden');
    currentActivePlaylistId = null;
}

async function quickPlayPlaylist(playlistId) {
    try {
        const res = await fetch(`/api/lifehub/music/playlists/${playlistId}`);
        const data = await res.json();
        const items = data.playlist.items || [];
        if (items.length > 0) {
            playSong(items[0]);
            if (items.length > 1) {
                MusicApp.queue = items.slice(1).map(i => ({
                    youtube_id: i.youtube_id,
                    title: i.title,
                    channel_title: i.channel_title,
                    thumbnail_url: i.thumbnail_url
                }));
            }
        } else {
            alert('Playlist ini masih kosong.');
        }
    } catch (e) {
        alert('Gagal memutar playlist.');
    }
}

async function confirmDeletePlaylist(playlistId) {
    if (!confirm('Apakah Anda yakin ingin menghapus playlist ini?')) return;
    try {
        const res = await fetch(`/api/lifehub/music/playlists/${playlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            window.location.reload();
        }
    } catch (e) {
        alert('Gagal menghapus playlist');
    }
}

async function deleteSongFromPlaylist(playlistId, itemId) {
    try {
        const res = await fetch(`/api/lifehub/music/playlists/${playlistId}/items/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            openPlaylistDetailView(playlistId);
        }
    } catch (e) {
        alert('Gagal menghapus lagu dari playlist.');
    }
}
</script>
@endsection

