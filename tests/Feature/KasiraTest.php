<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasiraTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_unauthenticated_user_cannot_access_dashboard_and_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_business_is_redirected_to_setup(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect(route('kasira.setup'));
    }

    public function test_user_can_setup_first_business_and_gets_default_categories(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/bisnis/setup', [
            'name' => 'Warung Berkah',
            'business_type' => 'Warung',
            'phone' => '081234567890',
            'address' => 'Jl. Mawar No. 10',
        ]);

        $response->assertRedirect(route('kasira.dashboard'));

        $this->assertDatabaseHas('businesses', [
            'user_id' => $user->id,
            'name' => 'Warung Berkah',
            'business_type' => 'Warung',
        ]);

        $business = $user->fresh()->currentBusiness();
        $this->assertNotNull($business);
        $this->assertDatabaseHas('categories', [
            'business_id' => $business->id,
            'name' => 'Makanan',
        ]);
    }

    public function test_dashboard_renders_with_real_business_metrics(): void
    {
        $user = User::factory()->create(['name' => 'Reski Setiawan']);
        $business = Business::create([
            'user_id' => $user->id,
            'name' => 'Warung Suka Maju',
            'business_type' => 'Warung',
        ]);

        $product = $business->products()->create([
            'name' => 'Indomie Goreng',
            'cost_price' => 2800,
            'selling_price' => 3500,
            'stock' => 20,
            'min_stock' => 5,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Warung Suka Maju');
        $response->assertSee('Reski Setiawan');
        $response->assertSee('Indomie Goreng');
    }

    public function test_user_can_create_product_with_barcode(): void
    {
        $user = User::factory()->create();
        $business = Business::create([
            'user_id' => $user->id,
            'name' => 'Toko Alex',
            'business_type' => 'Toko Kelontong',
        ]);
        $category = $business->categories()->create([
            'name' => 'Snack',
            'slug' => 'snack',
        ]);

        $response = $this->actingAs($user)->post('/produk', [
            'name' => 'Chitato Sapi Panggang',
            'barcode' => '8993175537554',
            'cost_price' => 9500,
            'selling_price' => 12000,
            'stock' => 15,
            'min_stock' => 3,
            'category_id' => $category->id,
            'description' => 'Keripik kentang renyah',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('kasira.produk.index'));

        $this->assertDatabaseHas('products', [
            'business_id' => $business->id,
            'name' => 'Chitato Sapi Panggang',
            'barcode' => '8993175537554',
            'stock' => 15,
        ]);
    }

    public function test_user_cannot_access_or_modify_another_business_product(): void
    {
        $userA = User::factory()->create();
        $businessA = Business::create(['user_id' => $userA->id, 'name' => 'Bisnis A']);
        $productA = $businessA->products()->create([
            'name' => 'Produk A',
            'selling_price' => 5000,
            'cost_price' => 4000,
            'stock' => 10,
            'min_stock' => 2,
        ]);

        $userB = User::factory()->create();
        $businessB = Business::create(['user_id' => $userB->id, 'name' => 'Bisnis B']);

        // User B tries to edit User A's product
        $response = $this->actingAs($userB)->get("/produk/{$productA->id}/edit");
        $response->assertStatus(403);

        // User B tries to update User A's product
        $updateResponse = $this->actingAs($userB)->put("/produk/{$productA->id}", [
            'name' => 'Hacked Product',
            'selling_price' => 1000,
            'cost_price' => 500,
            'stock' => 99,
            'min_stock' => 1,
        ]);
        $updateResponse->assertStatus(403);

        // User B tries to delete User A's product
        $deleteResponse = $this->actingAs($userB)->delete("/produk/{$productA->id}");
        $deleteResponse->assertStatus(403);

        $this->assertDatabaseHas('products', [
            'id' => $productA->id,
            'name' => 'Produk A',
        ]);
    }

    public function test_barcode_search_api_finds_own_product_and_ignores_other_business_product(): void
    {
        $userA = User::factory()->create();
        $businessA = Business::create(['user_id' => $userA->id, 'name' => 'Bisnis A']);
        $productA = $businessA->products()->create([
            'name' => 'Kecap Manis',
            'barcode' => '8991234567890',
            'selling_price' => 8000,
            'stock' => 10,
            'min_stock' => 2,
        ]);

        $userB = User::factory()->create();
        $businessB = Business::create(['user_id' => $userB->id, 'name' => 'Bisnis B']);

        // User A searches: found!
        $responseA = $this->actingAs($userA)->getJson('/api/kasira/barcode/search?barcode=8991234567890');
        $responseA->assertStatus(200)
            ->assertJson([
                'found' => true,
                'product' => [
                    'id' => $productA->id,
                    'name' => 'Kecap Manis',
                ],
            ]);

        // User B searches same barcode: NOT found in User B's business!
        $responseB = $this->actingAs($userB)->getJson('/api/kasira/barcode/search?barcode=8991234567890');
        $responseB->assertStatus(200)
            ->assertJson([
                'found' => false,
                'barcode' => '8991234567890',
            ]);
    }

    public function test_barcode_search_does_not_return_an_inactive_product(): void
    {
        $user = User::factory()->create();
        $business = Business::create(['user_id' => $user->id, 'name' => 'Bisnis Aktif']);
        $business->products()->create([
            'name' => 'Produk Nonaktif',
            'barcode' => '8990000000001',
            'selling_price' => 8000,
            'cost_price' => 5000,
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->getJson('/api/kasira/barcode/search?barcode=8990000000001')
            ->assertOk()
            ->assertJson([
                'found' => false,
                'barcode' => '8990000000001',
            ]);
    }

    public function test_cashier_checkout_creates_transaction_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $business = Business::create(['user_id' => $user->id, 'name' => 'Warung Berkah']);
        $product = $business->products()->create([
            'name' => 'Teh Botol',
            'selling_price' => 4000,
            'cost_price' => 3000,
            'stock' => 20,
            'min_stock' => 5,
        ]);

        $response = $this->actingAs($user)->postJson('/kasir/checkout', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
            'payment_method' => 'cash',
            'paid_amount' => 15000,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'transaction' => [
                    'total_amount' => 12000,
                    'paid_amount' => 15000,
                    'change_amount' => 3000,
                ],
            ]);

        // Verify stock decremented: 20 - 3 = 17
        $this->assertEquals(17, $product->fresh()->stock);

        // Verify database records
        $this->assertDatabaseHas('transactions', [
            'business_id' => $business->id,
            'total_amount' => 12000,
            'change_amount' => 3000,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('transaction_items', [
            'product_id' => $product->id,
            'product_name' => 'Teh Botol',
            'quantity' => 3,
            'subtotal' => 12000,
        ]);
    }

    public function test_cashier_checkout_fails_when_quantity_exceeds_stock(): void
    {
        $user = User::factory()->create();
        $business = Business::create(['user_id' => $user->id, 'name' => 'Warung Berkah']);
        $product = $business->products()->create([
            'name' => 'Roti Manis',
            'selling_price' => 5000,
            'cost_price' => 4000,
            'stock' => 2,
            'min_stock' => 1,
        ]);

        $response = $this->actingAs($user)->postJson('/kasir/checkout', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5], // Exceeds stock 2
            ],
            'payment_method' => 'cash',
            'paid_amount' => 25000,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        // Stock unchanged
        $this->assertEquals(2, $product->fresh()->stock);
    }

    public function test_stock_management_can_update_stock_values(): void
    {
        $user = User::factory()->create();
        $business = Business::create(['user_id' => $user->id, 'name' => 'Warung Berkah']);
        $product = $business->products()->create([
            'name' => 'Beras 5kg',
            'selling_price' => 65000,
            'cost_price' => 58000,
            'stock' => 10,
            'min_stock' => 3,
        ]);

        $response = $this->actingAs($user)->patch("/stok/{$product->id}", [
            'stock' => 25,
            'min_stock' => 5,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(25, $product->fresh()->stock);
        $this->assertEquals(5, $product->fresh()->min_stock);
    }

    public function test_product_filters_and_sorting_are_scoped_to_the_current_business(): void
    {
        $user = User::factory()->create();
        $business = Business::create(['user_id' => $user->id, 'name' => 'Toko Aman']);
        $business->products()->create([
            'name' => 'Produk Aktif',
            'selling_price' => 5000,
            'cost_price' => 3000,
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => true,
        ]);
        $business->products()->create([
            'name' => 'Produk Nonaktif',
            'selling_price' => 3000,
            'cost_price' => 2000,
            'stock' => 0,
            'min_stock' => 2,
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->get('/produk?filter=inactive&sort=price_asc');

        $response->assertSee('Produk Nonaktif')
            ->assertDontSee('Produk Aktif');
    }

    public function test_owner_can_toggle_product_status_but_another_business_cannot(): void
    {
        $userA = User::factory()->create();
        $businessA = Business::create(['user_id' => $userA->id, 'name' => 'Bisnis A']);
        $product = $businessA->products()->create([
            'name' => 'Produk A',
            'selling_price' => 5000,
            'cost_price' => 3000,
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => true,
        ]);
        $userB = User::factory()->create();
        Business::create(['user_id' => $userB->id, 'name' => 'Bisnis B']);

        $this->actingAs($userB)
            ->patch("/produk/{$product->id}/status", ['is_active' => false])
            ->assertForbidden();

        $this->actingAs($userA)
            ->patch("/produk/{$product->id}/status", ['is_active' => false])
            ->assertRedirect(route('kasira.produk.index'));

        $this->assertFalse($product->fresh()->is_active);
    }

    public function test_destroying_a_product_with_transaction_history_deactivates_it(): void
    {
        $user = User::factory()->create();
        $business = Business::create(['user_id' => $user->id, 'name' => 'Toko Aman']);
        $product = $business->products()->create([
            'name' => 'Produk Bertransaksi',
            'selling_price' => 5000,
            'cost_price' => 3000,
            'stock' => 10,
            'min_stock' => 2,
        ]);
        $transaction = Transaction::create([
            'business_id' => $business->id,
            'user_id' => $user->id,
            'invoice_number' => 'TRX-TEST-0001',
            'total_amount' => 5000,
            'paid_amount' => 5000,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
        $transaction->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 5000,
            'cost_price' => 3000,
            'quantity' => 1,
            'subtotal' => 5000,
        ]);

        $this->actingAs($user)
            ->delete("/produk/{$product->id}")
            ->assertRedirect(route('kasira.produk.index'));

        $this->assertModelExists($product->fresh());
        $this->assertFalse($product->fresh()->is_active);
    }
}
