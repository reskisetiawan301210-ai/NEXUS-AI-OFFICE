<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_login_screen_can_be_rendered_for_new_user(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome');
        $response->assertSee('LifeHub AI');
        $response->assertSee('Lanjutkan dengan Google');
        $response->assertSee('Masuk untuk melanjutkan');
    }

    public function test_login_screen_displays_welcome_back_for_returning_user(): void
    {
        $response = $this->withCookie('lifehub_has_account', '1')->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back!');
    }

    public function test_users_can_authenticate_using_email_and_redirects_to_workspace_select_if_both_features(): void
    {
        $user = User::factory()->create([
            'email' => 'alex@lifehub.ai',
            'username' => 'alex',
            'password' => Hash::make('password123'),
            'has_lifehub' => true,
            'has_kasira' => true,
        ]);

        $response = $this->post('/login', [
            'login' => 'alex@lifehub.ai',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/workspace-select');
        $response->assertCookie('lifehub_has_account');
    }

    public function test_users_can_authenticate_using_username_and_redirects_to_kasira_dashboard_if_kasira_only(): void
    {
        $user = User::factory()->kasiraOnly()->create([
            'email' => 'alex@lifehub.ai',
            'username' => 'alexhub',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'alexhub',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_authenticate_and_redirects_to_lifehub_if_lifehub_only(): void
    {
        $user = User::factory()->lifehubOnly()->create([
            'email' => 'siti@lifehub.ai',
            'username' => 'sitiai',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'sitiai',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/lifehub');
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'alex@lifehub.ai',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'alex@lifehub.ai',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('login');
    }

    public function test_registration_screen_can_be_rendered_with_feature_options(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('LifeHub AI');
        $response->assertSee('Buat akun untuk memulai');
        $response->assertSee('Pilih produk yang ingin digunakan');
        $response->assertSee('Digital workspace untuk AI dan produktivitas.');
        $response->assertSee('Kelola toko, produk, stok, transaksi, dan kasir.');
        $response->assertSee('Kasira');
        $response->assertSee('Daftar');
    }

    public function test_new_user_can_register_with_both_features_and_redirects_to_workspace_selector(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@lifehub.ai',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'product' => 'both',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@lifehub.ai',
            'username' => 'johndoe',
            'has_lifehub' => 1,
            'has_kasira' => 1,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/workspace-select');
        $response->assertCookie('lifehub_has_account');
    }

    public function test_new_user_can_register_with_lifehub_only_and_redirects_to_lifehub(): void
    {
        $response = $this->post('/register', [
            'name' => 'Rina Developer',
            'username' => 'rinadev',
            'email' => 'rina@lifehub.ai',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'product' => 'lifehub',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'rina@lifehub.ai',
            'has_lifehub' => 1,
            'has_kasira' => 0,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/lifehub');
    }

    public function test_new_user_can_register_with_kasira_only_and_redirects_to_kasira_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Pak Budi Warung',
            'username' => 'budiwarung',
            'email' => 'budi@warung.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'product' => 'kasira',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'budi@warung.com',
            'has_lifehub' => 0,
            'has_kasira' => 1,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_registration_requires_feature_selection(): void
    {
        $response = $this->post('/register', [
            'name' => 'Invalid User',
            'username' => 'invaliduser',
            'email' => 'invalid@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('product');
        $this->assertGuest();
    }

    public function test_user_without_kasira_feature_cannot_access_kasira_routes(): void
    {
        $user = User::factory()->lifehubOnly()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        // Should be redirected to lifehub dashboard with error flash
        $response->assertRedirect(route('lifehub.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_user_without_lifehub_feature_cannot_access_lifehub_routes(): void
    {
        $user = User::factory()->kasiraOnly()->create();

        $response = $this->actingAs($user)->get('/lifehub');

        // Should be redirected to kasira dashboard with error flash
        $response->assertRedirect(route('kasira.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_user_with_both_features_can_access_workspace_selector(): void
    {
        $user = User::factory()->create([
            'has_lifehub' => true,
            'has_kasira' => true,
        ]);

        $response = $this->actingAs($user)->get('/workspace-select');

        $response->assertStatus(200);
        $response->assertSee('Welcome to LifeHub');
        $response->assertSee('Pilih workspace kamu');
        $response->assertSee('LifeHub AI');
        $response->assertSee('Kasira');
    }

    public function test_user_with_both_features_can_access_lifehub_and_switch_to_kasira(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Multi',
            'has_lifehub' => true,
            'has_kasira' => true,
        ]);

        $response = $this->actingAs($user)->get('/lifehub');

        $response->assertStatus(200);
        $response->assertSee('Budi Multi');
        $response->assertSee('Kasira POS');
    }

    public function test_authenticated_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertCookie('lifehub_has_account');
    }
}
