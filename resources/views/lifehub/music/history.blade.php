@extends('lifehub.music.layout')

@section('title', 'Riwayat Pemutaran')

@section('content')
<div class="px-4 sm:px-8 py-6 max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight flex items-center gap-2.5">
                <span>Riwayat Pemutaran</span>
                <span class="text-xs font-mono text-slate-500 font-normal">({{ $history->total() }} lagu)</span>
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">Daftar semua musik yang pernah Anda dengarkan di LifeHub AI</p>
        </div>

        @if($history->isNotEmpty())
            <button type="button" onclick="clearAllHistoryConfirm()"
                    class="px-3.5 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Hapus Semua</span>
            </button>
        @endif
    </div>

    <!-- History List -->
    @if($history->isEmpty())
        <div class="card-obsidian rounded-3xl p-12 text-center max-w-md mx-auto my-10 space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-violet-950/60 border border-violet-500/30 flex items-center justify-center text-violet-400 mx-auto">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-white">Belum Ada Riwayat</h3>
            <p class="text-xs text-slate-400 max-w-xs mx-auto">
                Lagu yang Anda putar akan otomatis tercatat di sini sehingga mudah untuk didengarkan kembali.
            </p>
            <a href="{{ route('lifehub.music') }}" class="inline-block px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold transition mt-2">
                Eksplorasi Beranda
            </a>
        </div>
    @else
        <div class="card-obsidian rounded-2xl p-3 sm:p-5">
            <div class="divide-y divide-white/[0.04]" id="history-items-wrapper">
                @foreach($history as $item)
                    @php
                        $isFav = in_array($item->youtube_id, $userFavoriteIds);
                    @endphp
                    <div class="py-3 px-2 sm:px-3 flex items-center justify-between gap-3 hover:bg-white/[0.03] rounded-xl transition group cursor-pointer"
                         id="history-row-{{ $item->id }}"
                         data-youtube-id="{{ $item->youtube_id }}"
                         data-title="{{ $item->title }}"
                         data-channel="{{ $item->channel_title }}"
                         data-thumbnail="{{ $item->thumbnail_url }}"
                         onclick="playFromEl(this)">
                        
                        <!-- Left: Thumbnail + Title + Time -->
                        <div class="flex items-center gap-3.5 min-w-0 flex-1">
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl overflow-hidden bg-black/50 shrink-0 relative">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="text-xs sm:text-sm font-bold text-white truncate leading-tight group-hover:text-violet-300 transition-colors">
                                    {{ $item->title }}
                                </h3>
                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400">
                                    <span class="truncate">{{ $item->channel_title }}</span>
                                    <span>&bull;</span>
                                    <span class="font-mono text-slate-500 shrink-0">{{ $item->played_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center gap-2 shrink-0" onclick="event.stopPropagation();">
                            <button type="button" onclick="queueFromEl(this)" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Antrian">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                            <button type="button" onclick="favFromEl(this)" class="p-2 rounded-lg {{ $isFav ? 'text-violet-400' : 'text-slate-400 hover:text-violet-400' }} hover:bg-white/5 transition" title="Favorit">
                                <svg class="w-4 h-4" fill="{{ $isFav ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                            <button type="button" onclick="playlistFromEl(this)" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition" title="Tambah ke Playlist">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                            </button>
                            <button type="button" data-history-id="{{ $item->id }}" onclick="deleteHistoryItem(this)" class="p-2 rounded-lg text-slate-500 hover:text-red-400 hover:bg-white/5 transition" title="Hapus dari Riwayat">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            @if($history->hasPages())
                <div class="pt-4 mt-2 border-t border-white/[0.06]">
                    {{ $history->links() }}
                </div>
            @endif
        </div>
    @endif

</div>

<script>
async function deleteHistoryItem(btn) {
    const id = btn.getAttribute('data-history-id') || btn.dataset.historyId;
    if (!id) return;
    try {
        const res = await fetch(`/api/lifehub/music/history/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            const el = document.getElementById(`history-row-${id}`);
            if (el) el.remove();
        }
    } catch (e) {
        alert('Gagal menghapus riwayat pemutaran');
    }
}

async function clearAllHistoryConfirm() {
    if (!confirm('Hapus seluruh riwayat pemutaran Anda? Tindakan ini tidak dapat dibatalkan.')) return;
    try {
        const res = await fetch('/api/lifehub/music/history', {
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
        alert('Gagal mengosongkan riwayat pemutaran');
    }
}
</script>
@endsection

