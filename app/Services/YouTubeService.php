<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    private string $baseUrl = 'https://www.googleapis.com/youtube/v3';

    private ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
    }

    /**
     * Check if the API key is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Search YouTube videos.
     *
     * @return array{items: array<int, array<string, mixed>>, error?: string}
     */
    public function search(string $query, int $maxResults = 24): array
    {
        if (! $this->isConfigured()) {
            return ['items' => [], 'error' => 'YouTube API key belum dikonfigurasi di .env.'];
        }

        $cacheKey = 'yt_search_'.md5(trim(strtolower($query)).'_'.$maxResults);

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($query, $maxResults) {
            try {
                $response = Http::timeout(10)->get($this->baseUrl.'/search', [
                    'part' => 'snippet',
                    'q' => $query,
                    'type' => 'video',
                    'videoEmbeddable' => 'true',
                    'videoSyndicated' => 'true',
                    'videoCategoryId' => '10',
                    'maxResults' => min($maxResults, 50),
                    'regionCode' => 'ID',
                    'relevanceLanguage' => 'id',
                    'key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    return ['items' => $this->formatSearchResults($response->json('items', []))];
                }

                $status = $response->status();
                $err = $response->json('error.message', 'Gagal mengambil hasil pencarian.');
                Log::warning('YouTube Search API error', ['status' => $status, 'message' => $err]);

                if ($status === 403) {
                    return ['items' => [], 'error' => 'Batas kuota YouTube API harian telah tercapai.'];
                }

                return ['items' => [], 'error' => 'Gagal memuat pencarian musik dari YouTube.'];
            } catch (\Exception $e) {
                Log::error('YouTube Search exception', ['message' => $e->getMessage()]);

                return ['items' => [], 'error' => 'Koneksi ke layanan musik sedang bermasalah.'];
            }
        });
    }

    /**
     * Get trending music videos for a specific region.
     *
     * @return array{items: array<int, array<string, mixed>>, error?: string}
     */
    public function trending(string $regionCode = 'ID', int $maxResults = 24): array
    {
        if (! $this->isConfigured()) {
            return ['items' => [], 'error' => 'YouTube API key belum dikonfigurasi di .env.'];
        }

        $regionCode = strtoupper(trim($regionCode));
        $cacheKey = 'yt_trending_'.$regionCode.'_'.$maxResults;

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($regionCode, $maxResults) {
            try {
                $response = Http::timeout(10)->get($this->baseUrl.'/videos', [
                    'part' => 'snippet,contentDetails,statistics,status',
                    'chart' => 'mostPopular',
                    'regionCode' => $regionCode,
                    'videoCategoryId' => '10',
                    'maxResults' => min($maxResults, 50),
                    'key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    $items = array_values(array_filter($response->json('items', []), function ($item) {
                        return ($item['status']['embeddable'] ?? true) === true;
                    }));

                    return ['items' => $this->formatVideoResults($items)];
                }

                $status = $response->status();
                if ($status === 403) {
                    return ['items' => [], 'error' => 'Batas kuota YouTube API harian telah tercapai.'];
                }

                return ['items' => [], 'error' => 'Gagal mengambil data trending musik.'];
            } catch (\Exception $e) {
                Log::error('YouTube Trending exception', ['message' => $e->getMessage()]);

                return ['items' => [], 'error' => 'Koneksi ke layanan musik sedang bermasalah.'];
            }
        });
    }

    /**
     * Backward-compatible helper for trending Indonesia.
     *
     * @return array{items: array<int, array<string, mixed>>, error?: string}
     */
    public function trendingIndonesia(int $maxResults = 24): array
    {
        return $this->trending('ID', $maxResults);
    }

    /**
     * Global discovery by combining cached trending from top music markets.
     *
     * @param  array<int, string>  $regions
     * @return array{items: array<int, array<string, mixed>>, error?: string}
     */
    public function trendingGlobal(array $regions = ['US', 'GB', 'KR', 'JP', 'ID'], int $limit = 30): array
    {
        if (! $this->isConfigured()) {
            return ['items' => [], 'error' => 'YouTube API key belum dikonfigurasi di .env.'];
        }

        $cacheKey = 'yt_global_trending_'.md5(implode(',', $regions).'_'.$limit);

        return Cache::remember($cacheKey, now()->addHours(3), function () use ($regions, $limit) {
            $allItems = [];
            $seenIds = [];

            foreach ($regions as $region) {
                $res = $this->trending($region, 12);
                if (! empty($res['items'])) {
                    foreach ($res['items'] as $item) {
                        if (! empty($item['youtube_id']) && ! isset($seenIds[$item['youtube_id']])) {
                            $seenIds[$item['youtube_id']] = true;
                            $item['region'] = $region;
                            $allItems[] = $item;
                        }
                    }
                }
            }

            if (empty($allItems)) {
                return ['items' => [], 'error' => 'Tidak dapat memuat chart global saat ini.'];
            }

            return ['items' => array_slice($allItems, 0, $limit)];
        });
    }

    /**
     * Charts by genre or popularity category.
     *
     * @return array{items: array<int, array<string, mixed>>, error?: string}
     */
    public function charts(string $category = 'global', int $maxResults = 25): array
    {
        $category = strtolower(trim($category));

        return match ($category) {
            'indonesia' => $this->trending('ID', $maxResults),
            'pop' => $this->discover('pop hits music 2026', $maxResults),
            'new' => $this->discover('new releases music official', $maxResults),
            default => $this->trendingGlobal(['US', 'GB', 'KR', 'JP', 'ID'], $maxResults),
        };
    }

    /**
     * Get recommended / discovery music based on a seed query.
     *
     * @return array{items: array<int, array<string, mixed>>, error?: string}
     */
    public function discover(string $seed = 'musik Indonesia populer', int $maxResults = 18): array
    {
        if (! $this->isConfigured()) {
            return ['items' => [], 'error' => 'YouTube API key belum dikonfigurasi di .env.'];
        }

        $cacheKey = 'yt_discover_'.md5(trim(strtolower($seed)).'_'.$maxResults);

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($seed, $maxResults) {
            try {
                $response = Http::timeout(10)->get($this->baseUrl.'/search', [
                    'part' => 'snippet',
                    'q' => $seed,
                    'type' => 'video',
                    'videoEmbeddable' => 'true',
                    'videoSyndicated' => 'true',
                    'videoCategoryId' => '10',
                    'maxResults' => min($maxResults, 50),
                    'regionCode' => 'ID',
                    'order' => 'relevance',
                    'key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    return ['items' => $this->formatSearchResults($response->json('items', []))];
                }

                return ['items' => [], 'error' => 'Gagal mengambil rekomendasi musik.'];
            } catch (\Exception $e) {
                Log::error('YouTube Discover exception', ['message' => $e->getMessage()]);

                return ['items' => [], 'error' => 'Koneksi ke layanan musik sedang bermasalah.'];
            }
        });
    }

    /**
     * Format search API results into a consistent structure.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function quotaOrHttpError(int $status, string $fallback): string
    {
        if ($status === 403 || $status === 429) {
            return 'Batas kuota YouTube API harian telah tercapai.';
        }

        return $fallback;
    }

    /**
     * @param  callable(): array{items: array<int, array<string, mixed>>, error?: string}  $fetcher
     * @return array{items: array<int, array<string, mixed>>, error?: string}
     */
    private function rememberCatalog(string $cacheKey, int $freshMinutes, callable $fetcher): array
    {
        $staleKey = $cacheKey.'_stale';
        $live = Cache::get($cacheKey);

        if (is_array($live) && empty($live['error'])) {
            return $live;
        }

        $result = $fetcher();

        if (empty($result['error'])) {
            Cache::put($cacheKey, $result, now()->addMinutes($freshMinutes));
            Cache::put($staleKey, $result, now()->addDay());

            return $result;
        }

        $stale = Cache::get($staleKey);
        if (is_array($stale) && empty($stale['error'])) {
            return $stale;
        }

        return $result;
    }

    /**
     * Format search API results into a consistent structure.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function formatSearchResults(array $items): array
    {
        return array_map(function (array $item): array {
            $snippet = $item['snippet'] ?? [];

            return [
                'youtube_id' => $item['id']['videoId'] ?? '',
                'title' => html_entity_decode($snippet['title'] ?? 'Tanpa Judul', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'channel_title' => html_entity_decode($snippet['channelTitle'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'thumbnail_url' => $snippet['thumbnails']['high']['url']
                    ?? $snippet['thumbnails']['medium']['url']
                    ?? $snippet['thumbnails']['default']['url']
                    ?? '',
                'published_at' => $snippet['publishedAt'] ?? null,
            ];
        }, $items);
    }

    /**
     * Format video list API results (trending) into a consistent structure.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function formatVideoResults(array $items): array
    {
        return array_map(function (array $item): array {
            $snippet = $item['snippet'] ?? [];

            return [
                'youtube_id' => $item['id'] ?? '',
                'title' => html_entity_decode($snippet['title'] ?? 'Tanpa Judul', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'channel_title' => html_entity_decode($snippet['channelTitle'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'thumbnail_url' => $snippet['thumbnails']['high']['url']
                    ?? $snippet['thumbnails']['medium']['url']
                    ?? $snippet['thumbnails']['default']['url']
                    ?? '',
                'duration' => $item['contentDetails']['duration'] ?? null,
                'view_count' => $item['statistics']['viewCount'] ?? null,
                'published_at' => $snippet['publishedAt'] ?? null,
            ];
        }, $items);
    }
}
