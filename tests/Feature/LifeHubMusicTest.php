<?php

namespace Tests\Feature;

use App\Models\MusicHistory;
use App\Models\MusicPlaylist;
use App\Models\User;
use App\Services\YouTubeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class LifeHubMusicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guest_cannot_access_music_workspace(): void
    {
        $response = $this->get('/lifehub/music');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_lifehub_cannot_access_music(): void
    {
        $kasiraUser = User::factory()->kasiraOnly()->create();

        $response = $this->actingAs($kasiraUser)->get('/lifehub/music');
        $response->assertRedirect(route('kasira.dashboard'));
    }

    public function test_authenticated_lifehub_user_can_access_music_workspace_with_dynamic_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Reski Setiawan',
            'has_lifehub' => true,
        ]);

        $response = $this->actingAs($user)->get('/lifehub/music');

        $response->assertStatus(200);
        $response->assertSee('Reski Setiawan');
        $response->assertSee('Trending di Indonesia');
        $response->assertSee('Rekomendasi Untuk Anda');
        $response->assertSee('LifeHub');
    }

    public function test_user_can_access_all_dedicated_music_pages(): void
    {
        $user = User::factory()->create([
            'has_lifehub' => true,
        ]);

        // 1. Search page
        $this->actingAs($user)
            ->get(route('lifehub.music.search.page'))
            ->assertStatus(200)
            ->assertSee('Pencarian Musik');

        // 2. Trending page
        $this->actingAs($user)
            ->get(route('lifehub.music.trending.page'))
            ->assertStatus(200)
            ->assertSee('Trending Musik');

        // 3. Top Charts page
        $this->actingAs($user)
            ->get(route('lifehub.music.charts.page'))
            ->assertStatus(200)
            ->assertSee('Top Charts');

        // 4. Playlists page
        $this->actingAs($user)
            ->get(route('lifehub.music.playlists.page'))
            ->assertStatus(200)
            ->assertSee('Daftar Putar Saya');

        // 5. History page
        $this->actingAs($user)
            ->get(route('lifehub.music.history.page'))
            ->assertStatus(200)
            ->assertSee('Riwayat Pemutaran');

        // 6. Favorites page
        $this->actingAs($user)
            ->get(route('lifehub.music.favorites.page'))
            ->assertStatus(200)
            ->assertSee('Lagu Favorit');
    }

    public function test_convenient_music_aliases_redirect_properly(): void
    {
        $user = User::factory()->create([
            'has_lifehub' => true,
        ]);

        $this->actingAs($user)->get('/music')->assertRedirect(route('lifehub.music'));
        $this->actingAs($user)->get('/music/search?q=test')->assertRedirect(route('lifehub.music.search.page', ['q' => 'test']));
        $this->actingAs($user)->get('/music/trending')->assertRedirect(route('lifehub.music.trending.page'));
        $this->actingAs($user)->get('/music/charts')->assertRedirect(route('lifehub.music.charts.page'));
        $this->actingAs($user)->get('/music/playlists')->assertRedirect(route('lifehub.music.playlists.page'));
        $this->actingAs($user)->get('/music/history')->assertRedirect(route('lifehub.music.history.page'));
        $this->actingAs($user)->get('/music/favorites')->assertRedirect(route('lifehub.music.favorites.page'));
    }

    public function test_user_can_record_list_and_delete_history(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        // Record history
        $response = $this->actingAs($user)->postJson(route('lifehub.music.history.store'), [
            'youtube_id' => 'abc123xyz',
            'title' => 'Satu Tuju - Hindia',
            'channel_title' => 'Hindia Official',
            'thumbnail_url' => 'https://img.youtube.com/vi/abc123xyz/hqdefault.jpg',
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('music_histories', [
            'user_id' => $user->id,
            'youtube_id' => 'abc123xyz',
            'title' => 'Satu Tuju - Hindia',
        ]);

        // List history
        $listResponse = $this->actingAs($user)->getJson(route('lifehub.music.history'));
        $listResponse->assertStatus(200)
            ->assertJsonCount(1, 'items')
            ->assertJsonPath('items.0.youtube_id', 'abc123xyz');

        $history = MusicHistory::where('user_id', $user->id)->first();

        // Delete single history
        $delResponse = $this->actingAs($user)->deleteJson(route('lifehub.music.history.destroy', $history));
        $delResponse->assertStatus(200);
        $this->assertDatabaseMissing('music_histories', ['id' => $history->id]);

        // Clear history
        $user->musicHistory()->create([
            'youtube_id' => 'song2',
            'title' => 'Song 2',
        ]);
        $clearResponse = $this->actingAs($user)->deleteJson(route('lifehub.music.history.clear'));
        $clearResponse->assertStatus(200);
        $this->assertDatabaseCount('music_histories', 0);
    }

    public function test_history_is_isolated_between_users(): void
    {
        $userA = User::factory()->create(['has_lifehub' => true]);
        $userB = User::factory()->create(['has_lifehub' => true]);

        $historyA = $userA->musicHistory()->create([
            'youtube_id' => 'userA_song',
            'title' => 'User A Song',
        ]);

        // User B tries to delete User A's history
        $response = $this->actingAs($userB)->deleteJson(route('lifehub.music.history.destroy', $historyA));
        $response->assertForbidden();

        // User B's history list should be empty
        $listResponse = $this->actingAs($userB)->getJson(route('lifehub.music.history'));
        $listResponse->assertJsonCount(0, 'items');
    }

    public function test_user_can_toggle_and_list_favorites(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        // Toggle ON
        $response = $this->actingAs($user)->postJson(route('lifehub.music.favorites.toggle'), [
            'youtube_id' => 'fav123',
            'title' => 'Runtuh - Feby Putri',
            'channel_title' => 'Feby Putri',
            'thumbnail_url' => 'https://img.youtube.com/vi/fav123/hqdefault.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'added', 'is_favorite' => true]);

        $this->assertDatabaseHas('music_favorites', [
            'user_id' => $user->id,
            'youtube_id' => 'fav123',
        ]);

        // List favorites
        $listResponse = $this->actingAs($user)->getJson(route('lifehub.music.favorites'));
        $listResponse->assertStatus(200)->assertJsonCount(1, 'items');

        // Toggle OFF
        $responseOff = $this->actingAs($user)->postJson(route('lifehub.music.favorites.toggle'), [
            'youtube_id' => 'fav123',
            'title' => 'Runtuh - Feby Putri',
        ]);

        $responseOff->assertStatus(200)
            ->assertJson(['status' => 'removed', 'is_favorite' => false]);

        $this->assertDatabaseMissing('music_favorites', [
            'user_id' => $user->id,
            'youtube_id' => 'fav123',
        ]);
    }

    public function test_user_can_manage_playlist_and_items(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        // Create playlist
        $res = $this->actingAs($user)->postJson(route('lifehub.music.playlists.store'), [
            'name' => 'Chill Vibes Sore',
            'description' => 'Kompilasi santai',
        ]);

        $res->assertStatus(201)
            ->assertJsonPath('playlist.name', 'Chill Vibes Sore');

        $playlistId = $res->json('playlist.id');
        $playlist = MusicPlaylist::find($playlistId);

        // Update playlist
        $updateRes = $this->actingAs($user)->putJson(route('lifehub.music.playlists.update', $playlist), [
            'name' => 'Chill Vibes Sore Edit',
            'description' => 'Kompilasi baru',
        ]);
        $updateRes->assertStatus(200)->assertJsonPath('playlist.name', 'Chill Vibes Sore Edit');

        // Add item to playlist
        $addItemRes = $this->actingAs($user)->postJson(route('lifehub.music.playlists.items.store', $playlist), [
            'youtube_id' => 'pl_song_1',
            'title' => 'Bertaut - Nadin Amizah',
            'channel_title' => 'Nadin Amizah',
            'thumbnail_url' => 'https://img.youtube.com/vi/pl_song_1/hqdefault.jpg',
        ]);
        $addItemRes->assertStatus(201);
        $itemId = $addItemRes->json('item.id');

        $this->assertDatabaseHas('music_playlist_items', [
            'music_playlist_id' => $playlist->id,
            'youtube_id' => 'pl_song_1',
        ]);

        // Duplicate item should be rejected
        $dupRes = $this->actingAs($user)->postJson(route('lifehub.music.playlists.items.store', $playlist), [
            'youtube_id' => 'pl_song_1',
            'title' => 'Bertaut - Nadin Amizah',
        ]);
        $dupRes->assertStatus(422);

        // Show playlist
        $showRes = $this->actingAs($user)->getJson(route('lifehub.music.playlists.show', $playlist));
        $showRes->assertStatus(200)->assertJsonCount(1, 'playlist.items');

        // Remove item from playlist
        $item = $playlist->items()->find($itemId);
        $delItemRes = $this->actingAs($user)->deleteJson(route('lifehub.music.playlists.items.destroy', ['playlist' => $playlist, 'item' => $item]));
        $delItemRes->assertStatus(200);
        $this->assertDatabaseMissing('music_playlist_items', ['id' => $itemId]);

        // Delete playlist
        $delPlRes = $this->actingAs($user)->deleteJson(route('lifehub.music.playlists.destroy', $playlist));
        $delPlRes->assertStatus(200);
        $this->assertDatabaseMissing('music_playlists', ['id' => $playlist->id]);
    }

    public function test_playlist_is_isolated_between_users(): void
    {
        $userA = User::factory()->create(['has_lifehub' => true]);
        $userB = User::factory()->create(['has_lifehub' => true]);

        $playlistA = $userA->musicPlaylists()->create([
            'name' => 'Playlist A',
        ]);

        // User B tries to view User A's playlist
        $this->actingAs($userB)
            ->getJson(route('lifehub.music.playlists.show', $playlistA))
            ->assertForbidden();

        // User B tries to update User A's playlist
        $this->actingAs($userB)
            ->putJson(route('lifehub.music.playlists.update', $playlistA), ['name' => 'Hacked'])
            ->assertForbidden();

        // User B tries to delete User A's playlist
        $this->actingAs($userB)
            ->deleteJson(route('lifehub.music.playlists.destroy', $playlistA))
            ->assertForbidden();
    }

    public function test_search_and_trending_with_mocked_service(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $this->mock(YouTubeService::class, function (MockInterface $mock) {
            $mock->shouldReceive('search')
                ->once()
                ->with('Hindia')
                ->andReturn([
                    'items' => [
                        [
                            'youtube_id' => 'yt_123',
                            'title' => 'Evaluasi - Hindia',
                            'channel_title' => 'Hindia',
                            'thumbnail_url' => 'https://img.youtube.com/thumb.jpg',
                        ],
                    ],
                ]);

            $mock->shouldReceive('trending')
                ->once()
                ->with('ID', 24)
                ->andReturn([
                    'items' => [
                        [
                            'youtube_id' => 'yt_trending',
                            'title' => 'Trending Song',
                            'channel_title' => 'Artist',
                            'thumbnail_url' => 'https://img.youtube.com/thumb.jpg',
                        ],
                    ],
                ]);
        });

        $searchRes = $this->actingAs($user)->getJson(route('lifehub.music.search', ['q' => 'Hindia']));
        $searchRes->assertStatus(200)
            ->assertJsonPath('items.0.youtube_id', 'yt_123');

        $trendRes = $this->actingAs($user)->getJson(route('lifehub.music.trending'));
        $trendRes->assertStatus(200)
            ->assertJsonPath('items.0.youtube_id', 'yt_trending');
    }

    public function test_suggestions_endpoint_returns_autocomplete_data(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $user->musicHistory()->create([
            'youtube_id' => 'h_1',
            'title' => 'Sesi Berdua',
            'channel_title' => 'Artis',
        ]);

        $res = $this->actingAs($user)->getJson(route('lifehub.music.suggestions', ['q' => 'se']));
        $res->assertStatus(200)
            ->assertJsonPath('query', 'se')
            ->assertJsonStructure(['query', 'suggestions']);

        $this->assertTrue(collect($res->json('suggestions'))->contains('text', 'Sesi Berdua'));
    }
}
