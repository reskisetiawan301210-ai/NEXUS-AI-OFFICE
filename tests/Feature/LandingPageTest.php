<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guests_see_the_landing_page_with_a_login_cta(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('landing');
        $response->assertSee('NEXUS AI OFFICE');
        $response->assertSee(route('login'), false);
        $response->assertDontSee('Buka Workspace');
    }

    public function test_landing_page_renders_every_marketing_section(): void
    {
        $response = $this->get('/');

        // Section order and presence are part of the page contract.
        foreach ([
            'nx-nav',
            'nx-hero',
            'nx-scrub',
            'nx-marquee',
            'nx-cream',
            'nx-statement',
            'nx-projects',
            'nx-principles',
            'nx-footer',
        ] as $section) {
            $response->assertSee($section, false);
        }
    }

    public function test_landing_page_renders_without_the_authenticated_app_bundle(): void
    {
        $response = $this->get('/');

        // The landing page ships its own bundle and must not pull in the app's.
        $response->assertDontSee('resources/css/app.css', false);
        $response->assertDontSee('resources/js/app.js', false);
    }

    public function test_signed_in_users_get_a_workspace_cta_instead_of_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Buka Workspace');
        $response->assertSee(route('workspace.select'), false);
    }

    public function test_landing_page_labels_modules_by_real_availability(): void
    {
        $response = $this->get('/');

        // Music and Kasira ship routes and views, so they read "Tersedia".
        $response->assertSee('Tersedia');
        // Downloader has neither a route nor a view yet.
        $response->assertSee('Segera');
    }
}
