<?php

namespace Tests\Feature;

use App\Models\MusicHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class LifeHubDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_authenticated_user_can_view_lifehub_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Reski Setiawan',
            'has_lifehub' => true,
        ]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertStatus(200);
        $response->assertSee('Reski Setiawan');
        $response->assertSee('Quick Actions');
        $response->assertSee('AI Assistant');
        $response->assertSee('Music Player');
        $response->assertSee('Downloader');
        $response->assertSee('hero-live-clock');
        $response->assertSee('hero-live-date');
        $response->assertDontSee('Notes');
        $response->assertDontSee('Finance');
        $response->assertDontSee('Focus');
        $response->assertDontSee('Movies');
        $response->assertDontSee('QR Tools');
        $response->assertDontSee('Mastery');
    }

    public function test_dashboard_shows_nexus_branding_and_device_module(): void
    {
        $user = User::factory()->create([
            'name' => 'Reski Setiawan',
            'has_lifehub' => true,
        ]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('NEXUS AI OFFICE', false);
        $response->assertSee('Perangkat Anda');
        $response->assertSee('id="device-type"', false);
        $response->assertSee('id="device-memory"', false);
        $response->assertSee('id="device-model"', false);
        $response->assertSee('Tidak dilaporkan');
    }

    public function test_dashboard_does_not_render_fabricated_activity_timestamps(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertDontSee('2 menit yang lalu');
        $response->assertDontSee('15 menit yang lalu');
        $response->assertDontSee('1 jam yang lalu');
    }

    public function test_dashboard_recent_activity_reflects_recorded_playback(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        MusicHistory::create([
            'user_id' => $user->id,
            'youtube_id' => 'abc123',
            'title' => 'Lagu Terakhir Didengar',
            'played_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('Lagu Terakhir Didengar');
        $response->assertSee('1 lagu diputar');
    }

    public function test_dashboard_activity_falls_back_when_no_history_exists(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('Belum ada lagu diputar');
        $response->assertSee('0 lagu diputar');
        $response->assertSee('Dashboard');
    }

    public function test_user_can_update_profile_name_and_upload_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Old Name',
            'has_lifehub' => true,
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        $response = $this->actingAs($user)->put('/lifehub/profile', [
            'name' => 'Reski Setiawan Updated',
            'avatar' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Reski Setiawan Updated', $user->name);
        $this->assertNotNull($user->avatar_path);
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_user_can_remove_custom_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Reski Setiawan',
            'has_lifehub' => true,
            'avatar_path' => 'avatars/test-avatar.jpg',
        ]);

        Storage::disk('public')->put('avatars/test-avatar.jpg', 'fake content');

        $response = $this->actingAs($user)->put('/lifehub/profile', [
            'name' => 'Reski Setiawan',
            'remove_avatar' => 1,
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertNull($user->avatar_path);
        Storage::disk('public')->assertMissing('avatars/test-avatar.jpg');
    }

    public function test_avatar_upload_fails_for_invalid_file_type(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'has_lifehub' => true,
        ]);

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->put('/lifehub/profile', [
            'name' => 'Valid Name',
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_dashboard_renders_the_premium_entrance_sequence(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        // Stage 1-3 overlay plus the Stage 4-8 cascade targets.
        $response->assertSee('id="workspace-intro-overlay"', false);
        $response->assertSee('class="anim-header', false);
        $response->assertSee('anim-sidebar', false);
        $response->assertSee('class="anim-hero ', false);
        $response->assertSee('anim-hero-avatar', false);
        $response->assertSee('anim-hero-greeting', false);
        $response->assertSee('anim-qa-card-1', false);
        $response->assertSee('anim-qa-card-2', false);
        $response->assertSee('anim-qa-card-3', false);
        $response->assertSee('anim-dock', false);
        // The intro must never masquerade as a spinner/loading screen.
        $intro = Str::between(
            $response->getContent(),
            'id="workspace-intro-overlay"',
            'class="dark-vignette'
        );
        $this->assertNotSame('', $intro, 'Intro overlay markup could not be located.');
        $this->assertStringNotContainsString('animate-spin', $intro);
        $this->assertStringNotContainsString('animate-ping', $intro);
    }

    public function test_dashboard_renders_page_transition_hooks(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('page-transition-target', false);
        $response->assertSee('page-transition-ready', false);
        $response->assertSee('js-intro-pending', false);
        $response->assertSee('page-leaving', false);
    }

    public function test_dashboard_dock_exposes_a_single_moving_active_indicator(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('id="dock-indicator"', false);
        // Count against markup only; the inline script also mentions these selectors.
        $markup = preg_replace('#<script\b[^>]*>.*?</script>#s', '', $response->getContent());

        $this->assertSame(1, substr_count($markup, 'id="dock-indicator"'));
        $this->assertSame(5, substr_count($markup, 'class="dock-item'));
        $this->assertSame(5, substr_count($markup, 'data-dock-item'));
        // Only the current dock item is marked active. Scoped to the dock, because the
        // desktop sidebar legitimately marks the same page as current too.
        preg_match('#<div class="dock-nav.*?</div>#s', $markup, $dock);
        $this->assertNotEmpty($dock, 'The dock navigation could not be located in the markup.');
        $this->assertSame(1, substr_count($dock[0], 'aria-current="page"'));
    }

    public function test_dashboard_modals_are_wired_for_transitions(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('modal-layer', false);
        $response->assertSee('modal-backdrop', false);
        $response->assertSee('modal-panel', false);
        $this->assertSame(2, substr_count($response->getContent(), 'class="modal-layer'));
        $this->assertSame(2, substr_count($response->getContent(), 'class="modal-backdrop'));
        $this->assertSame(2, substr_count($response->getContent(), 'class="modal-panel'));
    }

    public function test_dashboard_hero_shows_the_real_technology_stack_instead_of_stock_photography(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('hero-shell', false);
        $response->assertSee('hero-blueprint', false);
        // Irrelevant scenery imagery is gone from the page entirely.
        $response->assertDontSee('hero-landscape', false);
        $response->assertDontSee('hero-forest', false);

        // The showcase presents the stack this workspace is actually built on.
        $response->assertSee('stack-panel', false);
        $response->assertSee('stack-grid', false);
        foreach (['laravel', 'php', 'tailwindcss', 'vite', 'sqlite', 'javascript'] as $slug) {
            $response->assertSee('images/tech/'.$slug.'.svg', false);
        }
    }

    public function test_dashboard_preserves_the_required_hero_identity_elements(): void
    {
        $user = User::factory()->create(['has_lifehub' => true, 'name' => 'Wira Nugroho']);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $response->assertSee('Nexus AI Office', false);
        $response->assertSee('anim-hero-avatar', false);
        $response->assertSee('anim-hero-greeting', false);
        $response->assertSee('hero-live-date', false);
        $response->assertSee('hero-live-clock', false);
        $response->assertSee('Wira Nugroho');
    }

    public function test_dashboard_sidebar_is_grouped_with_a_single_subtle_active_state(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $markup = preg_replace('#<script\b[^>]*>.*?</script>#s', '', $response->getContent());

        // Hierarchy is expressed with group labels instead of one flat list.
        foreach (['Ruang Kerja', 'Modul', 'Akun'] as $group) {
            $response->assertSee($group);
        }
        $this->assertSame(3, substr_count($markup, 'sidebar-group-label'));
        $this->assertSame(1, substr_count($markup, 'sidebar-item is-active'));
        // Every row is one consistent component; the loud gradient/border treatment is gone.
        $this->assertSame(6, substr_count($markup, 'sidebar-item'));
        $response->assertDontSee('sidebar-active-glow', false);
        $response->assertDontSee('sidebar-hide-on-collapse pt-3 px-2', false);
    }

    public function test_collapsed_sidebar_rail_tooltips_every_navigation_item(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $markup = preg_replace('#<script\b[^>]*>.*?</script>#s', '', $response->getContent());

        // data-label drives the CSS tooltip, so the icon-only rail stays usable.
        $this->assertSame(6, substr_count($markup, 'data-label='));
    }

    public function test_quick_actions_distinguish_the_live_module_from_planned_modules(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $markup = preg_replace('#<script\b[^>]*>.*?</script>#s', '', $response->getContent());

        $this->assertSame(1, substr_count($markup, 'qa-card-active'));
        $this->assertSame(2, substr_count($markup, 'qa-card-planned'));
        $this->assertSame(2, substr_count($markup, '>Terencana<'));
        // The one live module surfaces recorded counts rather than decorative filler.
        $response->assertSee('0 lagu diputar', false);
    }

    public function test_device_module_stays_compact_without_dropping_any_reported_fact(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        $markup = preg_replace('#<script\b[^>]*>.*?</script>#s', '', $response->getContent());

        // Six scannable cells; the remaining technical facts collapse into one meta bar.
        $this->assertSame(6, substr_count($markup, 'class="device-cell"'));
        $this->assertSame(1, substr_count($markup, 'class="device-details"'));

        foreach ([
            'device-type', 'device-os', 'device-browser', 'device-model', 'device-screen', 'device-memory',
            'device-engine', 'device-cores', 'device-viewport', 'device-dpr', 'device-connection', 'device-language',
        ] as $id) {
            $this->assertSame(1, substr_count($markup, 'id="'.$id.'"'), "Expected exactly one #{$id} target.");
        }
    }

    public function test_dashboard_markup_tags_are_balanced(): void
    {
        $user = User::factory()->create(['has_lifehub' => true]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertOk();
        // Styles and scripts hold literal tags in strings; exclude them from the count.
        $markup = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#s', '', $response->getContent());

        foreach (['div', 'span', 'dl', 'button', 'a', 'p'] as $tag) {
            $open = preg_match_all('#<'.$tag.'\b#i', $markup);
            $close = substr_count($markup, '</'.$tag.'>');

            $this->assertSame($open, $close, "Unbalanced <{$tag}> elements in the dashboard markup.");
        }
    }
}
