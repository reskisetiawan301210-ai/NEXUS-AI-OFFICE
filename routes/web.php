<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Kasira\BarcodeController;
use App\Http\Controllers\Kasira\BusinessSetupController;
use App\Http\Controllers\Kasira\CashierController;
use App\Http\Controllers\Kasira\CustomerController;
use App\Http\Controllers\Kasira\DashboardController;
use App\Http\Controllers\Kasira\DebtController;
use App\Http\Controllers\Kasira\PaymentWebhookController;
use App\Http\Controllers\Kasira\PricingController;
use App\Http\Controllers\Kasira\ProductController;
use App\Http\Controllers\Kasira\ReportController;
use App\Http\Controllers\Kasira\SettingsController;
use App\Http\Controllers\Kasira\ShiftController;
use App\Http\Controllers\Kasira\StockController;
use App\Http\Controllers\Kasira\SupplierController;
use App\Http\Controllers\Kasira\TransactionController;
use App\Http\Controllers\LifeHub\LifeHubController;
use App\Http\Controllers\LifeHub\MusicController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Middleware\EnsureHasBusiness;
use App\Http\Middleware\EnsureHasKasira;
use App\Http\Middleware\EnsureHasLifeHub;
use Illuminate\Support\Facades\Route;

// Public landing page. Guests see the marketing page with a "Masuk" CTA;
// signed-in users see the same page with a "Buka Workspace" CTA, so the
// marketing surface stays reachable for everyone.
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Authentication routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth product selection
Route::middleware('auth')->prefix('auth/google')->name('auth.google.')->group(function () {
    Route::get('/features', [GoogleController::class, 'showFeatureSelection'])->name('features');
    Route::post('/features', [GoogleController::class, 'saveFeatures']);
});

// Workspace Selector redirect fallback
Route::get('/workspace-select', [WorkspaceController::class, 'select'])->name('workspace.select')->middleware('auth');

// Midtrans QRIS Webhook endpoint
Route::post('/api/payment/qris/webhook', [PaymentWebhookController::class, 'handleMidtrans'])->name('payment.qris.webhook');

// LifeHub AI Digital Workspace
Route::middleware(['auth', EnsureHasLifeHub::class])->group(function () {
    Route::get('/lifehub', [LifeHubController::class, 'index'])->name('lifehub.dashboard');
    Route::put('/lifehub/profile', [LifeHubController::class, 'updateProfile'])->name('lifehub.profile.update');

    // Music Workspace Pages
    Route::get('/lifehub/music', [MusicController::class, 'index'])->name('lifehub.music');
    Route::get('/lifehub/music/search', [MusicController::class, 'searchPage'])->name('lifehub.music.search.page');
    Route::get('/lifehub/music/trending', [MusicController::class, 'trendingPage'])->name('lifehub.music.trending.page');
    Route::get('/lifehub/music/charts', [MusicController::class, 'chartsPage'])->name('lifehub.music.charts.page');
    Route::get('/lifehub/music/playlists', [MusicController::class, 'playlistsPage'])->name('lifehub.music.playlists.page');
    Route::get('/lifehub/music/history', [MusicController::class, 'historyPage'])->name('lifehub.music.history.page');
    Route::get('/lifehub/music/favorites', [MusicController::class, 'favoritesPage'])->name('lifehub.music.favorites.page');

    // Convenient aliases /music -> /lifehub/music
    Route::get('/music', fn () => redirect()->route('lifehub.music'));
    Route::get('/music/search', fn () => redirect()->route('lifehub.music.search.page', request()->query()));
    Route::get('/music/trending', fn () => redirect()->route('lifehub.music.trending.page', request()->query()));
    Route::get('/music/charts', fn () => redirect()->route('lifehub.music.charts.page', request()->query()));
    Route::get('/music/playlists', fn () => redirect()->route('lifehub.music.playlists.page'));
    Route::get('/music/history', fn () => redirect()->route('lifehub.music.history.page'));
    Route::get('/music/favorites', fn () => redirect()->route('lifehub.music.favorites.page'));

    // Music API routes
    Route::prefix('api/lifehub/music')->name('lifehub.music.')->group(function () {
        Route::get('/search', [MusicController::class, 'search'])->name('search');
        Route::get('/suggestions', [MusicController::class, 'suggestions'])->name('suggestions');
        Route::get('/trending', [MusicController::class, 'trending'])->name('trending');
        Route::get('/charts', [MusicController::class, 'charts'])->name('charts');
        Route::get('/discover', [MusicController::class, 'discover'])->name('discover');

        // History
        Route::get('/history', [MusicController::class, 'historyList'])->name('history');
        Route::post('/history', [MusicController::class, 'storeHistory'])->name('history.store');
        Route::delete('/history/{history}', [MusicController::class, 'destroyHistory'])->name('history.destroy');
        Route::delete('/history', [MusicController::class, 'clearHistory'])->name('history.clear');

        // Favorites
        Route::get('/favorites', [MusicController::class, 'favoritesList'])->name('favorites');
        Route::post('/favorites/toggle', [MusicController::class, 'toggleFavorite'])->name('favorites.toggle');

        // Playlists
        Route::post('/playlists', [MusicController::class, 'storePlaylist'])->name('playlists.store');
        Route::get('/playlists/{playlist}', [MusicController::class, 'showPlaylist'])->name('playlists.show');
        Route::put('/playlists/{playlist}', [MusicController::class, 'updatePlaylist'])->name('playlists.update');
        Route::delete('/playlists/{playlist}', [MusicController::class, 'destroyPlaylist'])->name('playlists.destroy');
        Route::post('/playlists/{playlist}/items', [MusicController::class, 'addToPlaylist'])->name('playlists.items.store');
        Route::delete('/playlists/{playlist}/items/{item}', [MusicController::class, 'removeFromPlaylist'])->name('playlists.items.destroy');
    });
});

// Kasira First-time Business Setup
Route::middleware(['auth', EnsureHasKasira::class])->prefix('bisnis')->name('kasira.setup')->group(function () {
    Route::get('/setup', [BusinessSetupController::class, 'create']);
    Route::post('/setup', [BusinessSetupController::class, 'store'])->name('.store');
});

// Kasira Authenticated Business App
Route::middleware(['auth', EnsureHasKasira::class, EnsureHasBusiness::class])->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('kasira.dashboard');

    // 2. Kasir / POS
    Route::get('/kasir', [CashierController::class, 'index'])->name('kasira.kasir');
    Route::post('/kasir/checkout', [CashierController::class, 'checkout'])->name('kasira.kasir.checkout');
    Route::get('/api/kasira/qris/{transaction}/status', [CashierController::class, 'checkQrisStatus'])->name('kasira.qris.status');
    Route::post('/api/kasira/qris/{transaction}/confirm', [CashierController::class, 'confirmQrisPayment'])->name('kasira.qris.confirm');

    // 3. Shift Kasir
    Route::get('/shift', [ShiftController::class, 'index'])->name('kasira.shift.index');
    Route::post('/shift/buka', [ShiftController::class, 'open'])->name('kasira.shift.open');
    Route::post('/shift/{shift}/tutup', [ShiftController::class, 'close'])->name('kasira.shift.close');

    // 4. Barcode Scanner
    Route::get('/barcode', [BarcodeController::class, 'index'])->name('kasira.barcode');
    Route::get('/api/kasira/barcode/search', [BarcodeController::class, 'search'])->name('kasira.barcode.search');

    // 5. Produk
    Route::resource('produk', ProductController::class)->names([
        'index' => 'kasira.produk.index',
        'create' => 'kasira.produk.create',
        'store' => 'kasira.produk.store',
        'edit' => 'kasira.produk.edit',
        'update' => 'kasira.produk.update',
        'destroy' => 'kasira.produk.destroy',
    ])->parameters(['produk' => 'product']);
    Route::patch('/produk/{product}/status', [ProductController::class, 'updateStatus'])->name('kasira.produk.status');

    // 6. Stok
    Route::get('/stok', [StockController::class, 'index'])->name('kasira.stok');
    Route::patch('/stok/{product}', [StockController::class, 'update'])->name('kasira.stok.update');

    // 7. Penjualan
    Route::get('/penjualan', [TransactionController::class, 'index'])->name('kasira.penjualan');
    Route::get('/penjualan/{transaction}', [TransactionController::class, 'show'])->name('kasira.penjualan.show');

    // 8. Piutang Pelanggan
    Route::get('/piutang', [DebtController::class, 'index'])->name('kasira.piutang.index');
    Route::post('/piutang', [DebtController::class, 'store'])->name('kasira.piutang.store');
    Route::post('/piutang/{debt}/bayar', [DebtController::class, 'pay'])->name('kasira.piutang.pay');

    // 9. Supplier
    Route::get('/supplier', [SupplierController::class, 'index'])->name('kasira.supplier.index');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('kasira.supplier.store');
    Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('kasira.supplier.update');
    Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('kasira.supplier.destroy');

    // 10. Pelanggan
    Route::get('/pelanggan', [CustomerController::class, 'index'])->name('kasira.pelanggan');
    Route::post('/pelanggan', [CustomerController::class, 'store'])->name('kasira.pelanggan.store');
    Route::get('/pelanggan/{customer}', [CustomerController::class, 'show'])->name('kasira.pelanggan.show');
    Route::put('/pelanggan/{customer}', [CustomerController::class, 'update'])->name('kasira.pelanggan.update');
    Route::delete('/pelanggan/{customer}', [CustomerController::class, 'destroy'])->name('kasira.pelanggan.destroy');

    // 11. Laporan & Statistik
    Route::get('/laporan', [ReportController::class, 'index'])->name('kasira.laporan');
    Route::get('/laporan/export', [ReportController::class, 'exportCsv'])->name('kasira.laporan.export');
    Route::get('/statistik', [ReportController::class, 'statistics'])->name('kasira.statistik');

    // 12. Paket Langganan (Pricing)
    Route::get('/paket', [PricingController::class, 'index'])->name('kasira.pricing');
    Route::post('/paket/pilih', [PricingController::class, 'selectPlan'])->name('kasira.pricing.select');

    // 13. Pengaturan
    Route::get('/pengaturan', [SettingsController::class, 'index'])->name('kasira.pengaturan');
    Route::put('/pengaturan', [SettingsController::class, 'update'])->name('kasira.pengaturan.update');
});
