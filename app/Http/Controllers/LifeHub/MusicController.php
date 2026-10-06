<?php

namespace App\Http\Controllers\LifeHub;

use App\Http\Controllers\Controller;
use App\Models\MusicFavorite;
use App\Models\MusicHistory;
use App\Models\MusicPlaylist;
use App\Models\MusicPlaylistItem;
use App\Services\YouTubeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class MusicController extends Controller
{
    public function __construct(
        private YouTubeService $youtube,
    ) {}

    // ── Dedicated Page Routes ────────────────────────────────────

    /**
     * Beranda (Home) Music Workspace.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $playlists = $user->musicPlaylists()->withCount('items')->latest()->take(6)->get();
        $favorites = $user->musicFavorites()->latest()->take(10)->get();
        $history = $user->musicHistory()->latest('played_at')->take(10)->get();

        // Discover tracks for hero & carousel
        $discover = $this->youtube->discover('musik Indonesia hits santai populer', 12);
        $trendingPreview = $this->youtube->trendingIndonesia(10);

        return view('lifehub.music.index', [
            'user' => $user,
            'activeNav' => 'beranda',
            'playlists' => $playlists,
            'favorites' => $favorites,
            'history' => $history,
            'discover' => $discover['items'] ?? [],
            'trendingPreview' => $trendingPreview['items'] ?? [],
            'youtubeConfigured' => $this->youtube->isConfigured(),
        ]);
    }

    /**
     * Dedicated Search Page.
     */
    public function searchPage(Request $request): View
    {
        $user = $request->user();
        $query = trim((string) $request->input('q', ''));
        $results = [];
        $error = null;

        if ($query !== '' && mb_strlen($query) >= 2) {
            $searchData = $this->youtube->search($query, 24);
            $results = $searchData['items'] ?? [];
            $error = $searchData['error'] ?? null;
        }

        $userFavoriteIds = $user->musicFavorites()->pluck('youtube_id')->toArray();

        return view('lifehub.music.search', [
            'user' => $user,
            'activeNav' => 'search',
            'query' => $query,
            'results' => $results,
            'userFavoriteIds' => $userFavoriteIds,
            'error' => $error,
            'youtubeConfigured' => $this->youtube->isConfigured(),
        ]);
    }

    /**
     * Dedicated Trending Page (Indonesia & Global).
     */
    public function trendingPage(Request $request): View
    {
        $user = $request->user();
        $region = strtoupper(trim((string) $request->input('region', 'ID')));

        $availableRegions = [
            'ID' => 'Indonesia',
            'GLOBAL' => 'Global Top Hits',
            'US' => 'Amerika Serikat',
            'GB' => 'Inggris Raya',
            'KR' => 'Korea Selatan',
            'JP' => 'Jepang',
            'BR' => 'Brasil',
        ];

        if (! array_key_exists($region, $availableRegions)) {
            $region = 'ID';
        }

        if ($region === 'GLOBAL') {
            $trendingData = $this->youtube->trendingGlobal(['US', 'GB', 'KR', 'JP', 'ID'], 24);
        } else {
            $trendingData = $this->youtube->trending($region, 24);
        }

        $userFavoriteIds = $user->musicFavorites()->pluck('youtube_id')->toArray();

        return view('lifehub.music.trending', [
            'user' => $user,
            'activeNav' => 'trending',
            'currentRegion' => $region,
            'regions' => $availableRegions,
            'items' => $trendingData['items'] ?? [],
            'error' => $trendingData['error'] ?? null,
            'userFavoriteIds' => $userFavoriteIds,
            'youtubeConfigured' => $this->youtube->isConfigured(),
        ]);
    }

    /**
     * Dedicated Top Charts Page.
     */
    public function chartsPage(Request $request): View
    {
        $user = $request->user();
        $category = strtolower(trim((string) $request->input('category', 'global')));

        $categories = [
            'global' => 'Global Top 50',
            'indonesia' => 'Indonesia Viral & Popular',
            'pop' => 'Pop Hits Worldwide',
            'new' => 'Rilisan Baru & Trending',
        ];

        if (! array_key_exists($category, $categories)) {
            $category = 'global';
        }

        $chartData = $this->youtube->charts($category, 25);
        $userFavoriteIds = $user->musicFavorites()->pluck('youtube_id')->toArray();

        return view('lifehub.music.charts', [
            'user' => $user,
            'activeNav' => 'charts',
            'currentCategory' => $category,
            'categories' => $categories,
            'items' => $chartData['items'] ?? [],
            'error' => $chartData['error'] ?? null,
            'userFavoriteIds' => $userFavoriteIds,
            'youtubeConfigured' => $this->youtube->isConfigured(),
        ]);
    }

    /**
     * Dedicated Playlists Page.
     */
    public function playlistsPage(Request $request): View
    {
        $user = $request->user();
        $playlists = $user->musicPlaylists()->withCount('items')->with(['items' => fn ($q) => $q->take(4)])->latest()->get();

        return view('lifehub.music.playlists', [
            'user' => $user,
            'activeNav' => 'playlists',
            'playlists' => $playlists,
            'youtubeConfigured' => $this->youtube->isConfigured(),
        ]);
    }

    /**
     * Dedicated History Page.
     */
    public function historyPage(Request $request): View
    {
        $user = $request->user();
        $history = $user->musicHistory()->latest('played_at')->paginate(30);
        $userFavoriteIds = $user->musicFavorites()->pluck('youtube_id')->toArray();

        return view('lifehub.music.history', [
            'user' => $user,
            'activeNav' => 'history',
            'history' => $history,
            'userFavoriteIds' => $userFavoriteIds,
            'youtubeConfigured' => $this->youtube->isConfigured(),
        ]);
    }

    /**
     * Dedicated Favorites Page.
     */
    public function favoritesPage(Request $request): View
    {
        $user = $request->user();
        $favorites = $user->musicFavorites()->latest()->paginate(30);

        return view('lifehub.music.favorites', [
            'user' => $user,
            'activeNav' => 'favorites',
            'favorites' => $favorites,
            'youtubeConfigured' => $this->youtube->isConfigured(),
        ]);
    }

    // ── JSON API Endpoints ───────────────────────────────────────

    /**
     * Live search API for debounce or dynamic autocomplete.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => ['required', 'string', 'min:2', 'max:200']]);

        $results = $this->youtube->search($request->input('q'));

        if (isset($results['error'])) {
            return response()->json($results, 503);
        }

        $favoriteIds = $request->user()->musicFavorites()->pluck('youtube_id')->toArray();

        $items = array_map(function (array $item) use ($favoriteIds): array {
            $item['is_favorite'] = in_array($item['youtube_id'], $favoriteIds);

            return $item;
        }, $results['items']);

        return response()->json(['items' => $items]);
    }

    /**
     * Trending API.
     */
    public function trending(Request $request): JsonResponse
    {
        $region = strtoupper(trim((string) $request->input('region', 'ID')));

        if ($region === 'GLOBAL') {
            $results = $this->youtube->trendingGlobal(['US', 'GB', 'KR', 'JP', 'ID'], 24);
        } else {
            $results = $this->youtube->trending($region, 24);
        }

        if (isset($results['error'])) {
            return response()->json($results, 503);
        }

        return response()->json($results);
    }

    /**
     * Charts API.
     */
    public function charts(Request $request): JsonResponse
    {
        $category = strtolower(trim((string) $request->input('category', 'global')));
        $results = $this->youtube->charts($category, 25);

        if (isset($results['error'])) {
            return response()->json($results, 503);
        }

        return response()->json($results);
    }

    /**
     * Discovery recommendation API.
     */
    public function discover(Request $request): JsonResponse
    {
        $seeds = [
            'lagu Indonesia terbaru populer',
            'lo-fi music study beats',
            'musik chill santai akustik',
            'pop hits viral 2026',
        ];
        $seed = $seeds[array_rand($seeds)];

        $results = $this->youtube->discover($seed, 18);

        if (isset($results['error'])) {
            return response()->json($results, 503);
        }

        return response()->json($results);
    }

    // ── History API ──────────────────────────────────────────────

    /**
     * Record a played song in history.
     */
    public function storeHistory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'youtube_id' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:500'],
            'channel_title' => ['nullable', 'string', 'max:255'],
            'thumbnail_url' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->musicHistory()->create([
            ...$validated,
            'played_at' => now(),
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Get user's music history.
     */
    public function historyList(Request $request): JsonResponse
    {
        $history = $request->user()
            ->musicHistory()
            ->latest('played_at')
            ->take(50)
            ->get();

        return response()->json(['items' => $history]);
    }

    /**
     * Delete a single history item.
     */
    public function destroyHistory(Request $request, MusicHistory $history): JsonResponse
    {
        if ($history->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        $history->delete();

        return response()->json(['status' => 'ok']);
    }

    /**
     * Clear all history for authenticated user.
     */
    public function clearHistory(Request $request): JsonResponse
    {
        $request->user()->musicHistory()->delete();

        return response()->json(['status' => 'ok']);
    }

    // ── Favorites API ────────────────────────────────────────────

    /**
     * Toggle favorite status for a song.
     */
    public function toggleFavorite(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'youtube_id' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:500'],
            'channel_title' => ['nullable', 'string', 'max:255'],
            'thumbnail_url' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $existing = $user->musicFavorites()->where('youtube_id', $validated['youtube_id'])->first();

        if ($existing) {
            $existing->delete();

            return response()->json(['status' => 'removed', 'is_favorite' => false]);
        }

        $user->musicFavorites()->create($validated);

        return response()->json(['status' => 'added', 'is_favorite' => true]);
    }

    /**
     * Get user's favorites list.
     */
    public function favoritesList(Request $request): JsonResponse
    {
        $favorites = $request->user()
            ->musicFavorites()
            ->latest()
            ->get();

        return response()->json(['items' => $favorites]);
    }

    // ── Playlists API ────────────────────────────────────────────

    /**
     * Create a new playlist.
     */
    public function storePlaylist(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $playlist = $request->user()->musicPlaylists()->create($validated);

        return response()->json(['playlist' => $playlist->loadCount('items')], 201);
    }

    /**
     * Update playlist name/description.
     */
    public function updatePlaylist(Request $request, MusicPlaylist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $playlist->update($validated);

        return response()->json(['playlist' => $playlist->loadCount('items')]);
    }

    /**
     * Delete a playlist.
     */
    public function destroyPlaylist(Request $request, MusicPlaylist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        $playlist->delete();

        return response()->json(['status' => 'ok']);
    }

    /**
     * Get a playlist with its items.
     */
    public function showPlaylist(Request $request, MusicPlaylist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        return response()->json([
            'playlist' => $playlist->load('items'),
        ]);
    }

    /**
     * Add a song to a playlist.
     */
    public function addToPlaylist(Request $request, MusicPlaylist $playlist): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        $validated = $request->validate([
            'youtube_id' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:500'],
            'channel_title' => ['nullable', 'string', 'max:255'],
            'thumbnail_url' => ['nullable', 'string', 'max:1000'],
            'duration' => ['nullable', 'string', 'max:30'],
        ]);

        $exists = $playlist->items()->where('youtube_id', $validated['youtube_id'])->exists();
        if ($exists) {
            return response()->json(['error' => 'Lagu sudah ada di playlist ini.'], 422);
        }

        $maxOrder = $playlist->items()->max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;

        $item = $playlist->items()->create($validated);

        return response()->json(['item' => $item, 'playlist' => $playlist->loadCount('items')], 201);
    }

    /**
     * Remove a song from a playlist.
     */
    public function removeFromPlaylist(Request $request, MusicPlaylist $playlist, MusicPlaylistItem $item): JsonResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Tidak diizinkan.'], 403);
        }

        if ($item->music_playlist_id !== $playlist->id) {
            return response()->json(['error' => 'Item tidak ditemukan di playlist ini.'], 404);
        }

        $item->delete();

        return response()->json(['status' => 'ok', 'playlist' => $playlist->loadCount('items')]);
    }

    /**
     * Autocomplete suggestions for music search.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        if (mb_strlen($query) < 2) {
            return response()->json([
                'query' => $query,
                'suggestions' => [],
            ]);
        }

        $user = $request->user();
        $cacheKey = 'music_sug_'.md5(mb_strtolower($query)).'_'.($user ? $user->id : 'guest');

        $suggestions = Cache::remember($cacheKey, 3600, function () use ($query, $user) {
            $results = collect();

            // 1. User history matches (prioritas tinggi)
            if ($user) {
                $historyMatches = MusicHistory::where('user_id', $user->id)
                    ->where('title', 'like', "%{$query}%")
                    ->latest('played_at')
                    ->take(3)
                    ->get();

                foreach ($historyMatches as $h) {
                    $results->push([
                        'text' => $h->title,
                        'sub' => $h->channel_title ?: 'Riwayat Putar',
                        'type' => 'history',
                        'youtube_id' => $h->youtube_id,
                    ]);
                }

                // 2. User favorites matches
                $favoriteMatches = MusicFavorite::where('user_id', $user->id)
                    ->where('title', 'like', "%{$query}%")
                    ->latest()
                    ->take(3)
                    ->get();

                foreach ($favoriteMatches as $f) {
                    if (! $results->contains('text', $f->title)) {
                        $results->push([
                            'text' => $f->title,
                            'sub' => $f->channel_title ?: 'Lagu Favorit',
                            'type' => 'favorite',
                            'youtube_id' => $f->youtube_id,
                        ]);
                    }
                }
            }

            // 3. Fast suggestion from Google / YouTube Suggest API (zero YouTube Data API quota)
            try {
                $response = Http::timeout(2)
                    ->get('https://suggestqueries.google.com/complete/search', [
                        'client' => 'firefox',
                        'ds' => 'yt',
                        'q' => $query,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (is_array($data) && isset($data[1]) && is_array($data[1])) {
                        foreach ($data[1] as $sug) {
                            $text = (string) $sug;
                            if (! $results->contains('text', $text)) {
                                $results->push([
                                    'text' => $text,
                                    'sub' => 'Saran Musik',
                                    'type' => 'suggestion',
                                ]);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Silently continue if external autocomplete fails
            }

            return $results->take(8)->values();
        });

        return response()->json([
            'query' => $query,
            'suggestions' => $suggestions,
        ]);
    }
}
