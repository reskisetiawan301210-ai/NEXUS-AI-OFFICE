@extends('layouts.kasira')

@section('title', 'Kasir / Point of Sale')

@section('content')
<div class="h-[calc(100vh-6.5rem)] flex flex-col lg:flex-row gap-5">
    <!-- Left Column: Products Catalog (Scrollable) -->
    <div class="flex-1 flex flex-col min-w-0 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-4 sm:p-5 overflow-hidden">
        <!-- Top Filters & Search -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pb-4 border-b border-[#F3F4F6] shrink-0">
            <div class="relative flex-1">
                <input type="text" id="kasir-search" placeholder="Cari nama produk atau ketik barcode..."
                       class="w-full pl-9 pr-4 py-2.5 bg-[#F9FAFB] border border-[#E5E7EB] rounded-xl text-xs text-[#111827] placeholder-[#9CA3AF] focus:bg-white focus:border-[#5300B7] outline-none transition">
                <svg class="w-4 h-4 text-[#9CA3AF] absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('kasira.barcode') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#F9FAFB] hover:bg-[#F3F4F6] border border-[#E5E7EB] rounded-xl text-xs font-bold text-[#111827] transition"
                   title="Buka Scanner Kamera">
                    <svg class="w-4 h-4 text-[#5300B7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span>Scan Kamera</span>
                </a>
            </div>
        </div>

        <!-- Categories Filter Pills -->
        <div class="flex items-center gap-1.5 overflow-x-auto py-3 shrink-0 no-scrollbar">
            <button type="button" onclick="filterCategory('all', this)"
                    class="cat-pill px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap bg-[#5300B7] text-white shadow-2xs transition">
                Semua
            </button>
            @foreach($categories as $cat)
                <button type="button" onclick="filterCategory('{{ $cat->slug }}', this)"
                        class="cat-pill px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap bg-[#F3F4F6] text-[#6B7280] hover:text-[#111827] transition">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <!-- Products Grid (Scrollable) -->
        <div class="flex-1 overflow-y-auto pr-1 mt-2">
            <div id="kasir-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @forelse($products as $prod)
                    <div class="pos-product-card bg-[#F9FAFB] hover:bg-white border border-[#E5E7EB] hover:border-[#5300B7]/40 rounded-xl p-3 flex flex-col justify-between transition cursor-pointer shadow-2xs group select-none {{ $prod->stock <= 0 ? 'opacity-50 pointer-events-none' : '' }}"
                         data-id="{{ $prod->id }}"
                         data-name="{{ $prod->name }}"
                         data-price="{{ (float)$prod->selling_price }}"
                         data-stock="{{ $prod->stock }}"
                         data-category="{{ $prod->category?->slug ?? 'umum' }}"
                         data-barcode="{{ $prod->barcode ?? '' }}"
                         onclick="addToCart(this.dataset.id)">
                        <div class="w-full aspect-square rounded-lg bg-white border border-[#E5E7EB] overflow-hidden mb-2 relative flex items-center justify-center">
                            @if($prod->image_path)
                                <img src="{{ asset('storage/' . $prod->image_path) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-7 h-7 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif

                            @if($prod->stock <= 0)
                                <span class="absolute inset-0 bg-black/50 text-white font-bold text-[11px] flex items-center justify-center">Habis</span>
                            @elseif($prod->stock <= $prod->min_stock)
                                <span class="absolute top-1 right-1 px-1 py-0.5 rounded bg-amber-100 text-amber-800 text-[9px] font-bold">Sisa {{ $prod->stock }}</span>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-[#111827] truncate group-hover:text-[#5300B7]">{{ $prod->name }}</h4>
                            <span class="text-[10px] text-[#6B7280] block truncate">{{ $prod->category?->name ?? 'Umum' }}</span>
                            <span class="text-xs font-bold text-[#111827] block mt-1">
                                Rp {{ number_format($prod->selling_price, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-2 pt-2 border-t border-[#E5E7EB] flex items-center justify-between text-[10px] text-[#6B7280]">
                            <span>Stok: {{ $prod->stock }}</span>
                            <span class="text-[#5300B7] font-bold group-hover:underline">+ Tambah</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-[#9CA3AF]">
                        <p class="text-sm font-bold text-[#111827]">Belum ada produk aktif</p>
                        <p class="text-xs text-[#6B7280] mt-1">Tambahkan produk di menu Produk terlebih dahulu.</p>
                        <a href="{{ route('kasira.produk.create') }}" class="mt-3 inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-[#5300B7] text-white text-xs font-semibold">
                            Tambah Produk
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Cart & Payment Checkout Panel -->
    <div class="w-full lg:w-96 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col h-full overflow-hidden shrink-0">
        <!-- Cart Header -->
        <div class="p-4 border-b border-[#F3F4F6] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#5300B7]/10 text-[#5300B7] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#111827]">Keranjang Belanja</h3>
                <span id="cart-badge-count" class="px-2 py-0.5 rounded-full bg-[#F3F4F6] text-[11px] font-bold text-[#5300B7]">0</span>
            </div>

            <button type="button" onclick="clearCart()" class="text-[11px] text-[#9CA3AF] hover:text-red-600 font-semibold transition">
                Kosongkan
            </button>
        </div>

        <!-- Cart Items List (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-4 relative">
            <!-- Empty state placeholder -->
            <div id="cart-empty-state" class="py-16 text-center text-[#9CA3AF]">
                <svg class="w-10 h-10 mx-auto text-[#D1D5DB] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="text-xs font-bold text-[#111827]">Keranjang Masih Kosong</p>
                <p class="text-[11px] text-[#6B7280] mt-0.5">Pilih produk di katalog atau scan barcode untuk menambahkan barang.</p>
            </div>

            <!-- Active Cart Items -->
            <div id="cart-items-container" class="space-y-3 divide-y divide-[#F3F4F6] hidden">
            </div>
        </div>

        <!-- Cart Bottom Checkout Section -->
        <div class="p-4 bg-[#F9FAFB] border-t border-[#E5E7EB] space-y-3 shrink-0">
            <!-- Totals -->
            <div class="space-y-1.5 text-xs">
                <div class="flex items-center justify-between text-[#6B7280]">
                    <span>Subtotal</span>
                    <span id="cart-subtotal" class="font-semibold text-[#111827]">Rp 0</span>
                </div>
                <div class="flex items-center justify-between text-sm font-bold text-[#111827] pt-1 border-t border-[#E5E7EB]">
                    <span>Total Pembayaran</span>
                    <span id="cart-total" class="text-base text-[#5300B7]">Rp 0</span>
                </div>
            </div>

            <!-- Payment Method Tabs -->
            <div>
                <span class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider block mb-1.5">Metode Pembayaran</span>
                <div class="grid grid-cols-3 gap-1.5">
                    <button type="button" onclick="setPaymentMethod('cash')" id="btn-pay-cash"
                            class="pay-method-btn py-2 rounded-xl text-xs font-bold border border-[#5300B7] bg-[#5300B7] text-white transition text-center shadow-2xs">
                        Tunai
                    </button>
                    <button type="button" onclick="setPaymentMethod('qris')" id="btn-pay-qris"
                            class="pay-method-btn py-2 rounded-xl text-xs font-bold border border-[#E5E7EB] bg-white text-[#6B7280] hover:text-[#111827] transition text-center">
                        QRIS
                    </button>
                    <button type="button" onclick="setPaymentMethod('transfer')" id="btn-pay-transfer"
                            class="pay-method-btn py-2 rounded-xl text-xs font-bold border border-[#E5E7EB] bg-white text-[#6B7280] hover:text-[#111827] transition text-center">
                        Transfer
                    </button>
                </div>
            </div>

            <!-- Cash Payment Details (Hidden when QRIS/Transfer) -->
            <div id="cash-payment-section" class="space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-[#111827]">Uang Diterima:</span>
                    <input type="number" id="cash-paid-input" min="0" step="1000" placeholder="0" oninput="calculateChange()"
                           class="w-32 px-2.5 py-1 text-right bg-white border border-[#E5E7EB] rounded-lg text-xs font-bold text-[#111827] focus:border-[#5300B7] outline-none">
                </div>

                <!-- Quick Cash Presets -->
                <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                    <button type="button" onclick="setCashPreset('exact')" class="px-2 py-1 rounded bg-white border border-[#E5E7EB] text-[10px] font-bold text-[#374151] hover:bg-[#F3F4F6] shrink-0">Uang Pas</button>
                    <button type="button" onclick="setCashPreset(10000)" class="px-2 py-1 rounded bg-white border border-[#E5E7EB] text-[10px] font-medium text-[#374151] hover:bg-[#F3F4F6] shrink-0">10rb</button>
                    <button type="button" onclick="setCashPreset(20000)" class="px-2 py-1 rounded bg-white border border-[#E5E7EB] text-[10px] font-medium text-[#374151] hover:bg-[#F3F4F6] shrink-0">20rb</button>
                    <button type="button" onclick="setCashPreset(50000)" class="px-2 py-1 rounded bg-white border border-[#E5E7EB] text-[10px] font-medium text-[#374151] hover:bg-[#F3F4F6] shrink-0">50rb</button>
                    <button type="button" onclick="setCashPreset(100000)" class="px-2 py-1 rounded bg-white border border-[#E5E7EB] text-[10px] font-medium text-[#374151] hover:bg-[#F3F4F6] shrink-0">100rb</button>
                </div>

                <div class="flex items-center justify-between text-xs font-bold pt-1 border-t border-[#E5E7EB]">
                    <span class="text-[#6B7280]">Kembalian:</span>
                    <span id="cash-change-display" class="text-emerald-600">Rp 0</span>
                </div>
            </div>

            <!-- Checkout Button -->
            <button type="button" id="btn-submit-order" onclick="processCheckout()" disabled
                    class="w-full py-3 rounded-xl bg-[#5300B7] hover:bg-[#430095] disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs shadow-md shadow-[#5300B7]/25 transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span id="btn-submit-text">Selesaikan Transaksi</span>
            </button>
        </div>
    </div>
</div>

<!-- Receipt / Success Modal -->
<div id="receipt-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs no-print" onclick="closeReceiptModal()"></div>
    <div class="relative bg-white rounded-3xl max-w-sm w-full p-5 shadow-2xl border border-[#E5E7EB] z-10 text-center space-y-4 max-h-[92vh] overflow-y-auto">
        <!-- Green Checkmark -->
        <div class="w-12 h-12 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-md no-print">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="no-print">
            <h3 class="text-base font-bold text-[#111827]">Transaksi Berhasil!</h3>
            <p id="receipt-invoice-number" class="font-mono text-xs font-bold text-[#5300B7] mt-0.5">TRX-0001</p>
        </div>

        <!-- Printable Thermal Receipt Box -->
        <div id="printable-receipt-content" class="bg-[#F9FAFB] rounded-2xl p-4 text-xs space-y-2 border border-[#E5E7EB] text-left">
            <div class="text-center pb-2 border-b border-[#E5E7EB] space-y-0.5">
                <h4 id="receipt-store-name" class="font-bold text-sm text-[#111827]">{{ $business->name }}</h4>
                <p id="receipt-store-address" class="text-[10px] text-[#6B7280]">{{ $business->address ?? '' }}</p>
                <p id="receipt-store-phone" class="text-[10px] text-[#6B7280]">{{ $business->phone ? 'Telp: ' . $business->phone : '' }}</p>
            </div>

            <div class="text-[11px] space-y-1 text-[#6B7280] pb-2 border-b border-[#E5E7EB]">
                <div class="flex justify-between">
                    <span>No. Invoice:</span>
                    <span id="receipt-inv" class="font-mono font-bold text-[#111827]">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Waktu:</span>
                    <span id="receipt-time" class="font-medium text-[#111827]">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir:</span>
                    <span id="receipt-cashier" class="font-medium text-[#111827]">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pelanggan:</span>
                    <span id="receipt-customer" class="font-medium text-[#111827]">Umum</span>
                </div>
                <div class="flex justify-between">
                    <span>Metode:</span>
                    <span id="receipt-method" class="font-bold text-[#111827] uppercase">-</span>
                </div>
            </div>

            <!-- Itemized breakdown -->
            <div class="space-y-1.5 py-1 border-b border-[#E5E7EB]">
                <span class="text-[10px] font-bold text-[#9CA3AF] uppercase tracking-wider block">Daftar Barang</span>
                <div id="receipt-items-list" class="space-y-1.5 text-xs">
                    <!-- Dynamic items inserted via JS -->
                </div>
            </div>

            <div class="space-y-1 pt-1 text-xs">
                <div class="flex justify-between text-sm font-bold text-[#111827]">
                    <span>Total Belanja</span>
                    <span id="receipt-total" class="text-base text-[#5300B7]">-</span>
                </div>
                <div class="flex justify-between text-[#6B7280]">
                    <span>Uang Diterima</span>
                    <span id="receipt-paid" class="font-semibold text-[#111827]">-</span>
                </div>
                <div class="flex justify-between text-[#6B7280]">
                    <span>Kembalian</span>
                    <span id="receipt-change" class="font-bold text-emerald-600">-</span>
                </div>
            </div>

            <div class="text-center pt-2 border-t border-[#E5E7EB] text-[10px] text-[#9CA3AF]">
                <p id="receipt-footer-text">{{ $business->receipt_footer ?? 'Terima kasih telah berbelanja!' }}</p>
                <p class="mt-0.5 text-[9px] text-[#D1D5DB]">Kasira POS - Kelola Bisnis Lebih Mudah</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 pt-1 no-print">
            <button type="button" onclick="shareReceiptFromPos()"
                    class="py-2.5 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold text-emerald-800 transition flex items-center justify-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.77.72 2.796.72 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.586-5.766-5.768-5.766zm3.385 8.163c-.144.405-.837.774-1.17.824-.312.045-.698.075-2.221-.555-1.944-.805-3.176-2.775-3.272-2.905-.097-.129-.785-1.042-.785-1.987 0-.945.497-1.411.674-1.603.177-.193.386-.241.514-.241.129 0 .257.002.37.008.118.006.277-.045.433.33.161.386.547 1.334.595 1.431.048.097.08.209.016.338-.064.129-.097.209-.193.322-.097.113-.204.252-.291.338-.097.097-.198.203-.085.397.113.193.501.826 1.077 1.339.742.66 1.368.864 1.562.961.193.097.306.08.419-.048.113-.129.483-.563.612-.756.129-.193.258-.161.434-.097.177.064 1.127.531 1.32.628.193.097.322.145.37.225.048.08.048.467-.096.872z"/>
                </svg>
                <span>Bagikan WA</span>
            </button>

            <button type="button" onclick="printThermalReceipt()"
                    class="py-2.5 px-3 rounded-xl border border-[#E5E7EB] hover:bg-[#F9FAFB] text-xs font-bold text-[#374151] transition flex items-center justify-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Struk (58/80)</span>
            </button>
        </div>

        <button type="button" onclick="closeReceiptModal()"
                class="w-full py-2.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-xs transition no-print">
            Selesai (Transaksi Baru)
        </button>
    </div>
</div>

<!-- QRIS Payment Modal -->
<div id="qris-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="closeQrisModal()"></div>
    <div class="relative bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-[#E5E7EB] z-10 text-center space-y-4 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-md bg-[#5300B7] text-white text-[10px] font-bold">QRIS</span>
                <span class="text-xs font-bold text-gray-900">Pembayaran Digital</span>
            </div>
            <button type="button" onclick="closeQrisModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div>
            <h3 class="text-lg font-extrabold text-[#111827]" id="qris-modal-amount">Rp 0</h3>
            <p class="text-xs text-gray-500 font-mono mt-0.5" id="qris-modal-invoice">TRX-XXXX</p>
        </div>

        <!-- Missing Config Notice (Shown if Midtrans is not configured in .env) -->
        <div id="qris-unconfigured-warning" class="hidden p-3 rounded-xl bg-amber-50 border border-amber-200 text-left space-y-1">
            <div class="flex items-center gap-1.5 text-amber-800 font-bold text-xs">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Gateway QRIS Mode Offline</span>
            </div>
            <p id="qris-warning-message" class="text-[11px] text-amber-700 leading-relaxed"></p>
            <p class="text-[10px] text-amber-600">Pelanggan dapat scan QR toko / transfer bank. Setelah dana masuk, kasir klik tombol <b>Konfirmasi Pembayaran Manual</b> di bawah.</p>
        </div>

        <!-- Dynamic QR Code Container -->
        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-200 inline-block mx-auto">
            <img id="qris-image" src="" alt="QRIS Code" class="w-56 h-56 mx-auto rounded-xl object-contain bg-white p-2">
        </div>

        <!-- Status Polling Badge (Only shown when auto-gateway is active) -->
        <div id="qris-auto-badge" class="flex items-center justify-center gap-2 text-xs font-medium text-purple-700 bg-purple-50 py-2 px-3 rounded-xl border border-purple-100">
            <span class="w-2.5 h-2.5 rounded-full bg-[#5300B7] animate-ping"></span>
            <span>Menunggu konfirmasi pembayaran...</span>
        </div>

        <!-- Manual Confirmation Button -->
        <div id="qris-manual-section" class="pt-2 space-y-2">
            <button type="button" onclick="manualConfirmQris()"
                    class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs">
                Konfirmasi Pembayaran Berhasil (Manual)
            </button>
        </div>
    </div>
</div>

<!-- Modal Upgrade Pro Needed -->
<div id="upgrade-pro-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="document.getElementById('upgrade-pro-modal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-[#E5E7EB] z-10 text-center space-y-4">
        <div class="w-14 h-14 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shadow-md">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
        </div>

        <div>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-900 border border-amber-200">
                Fitur Eksklusif Pro
            </span>
            <h3 class="text-base font-bold text-gray-900 mt-2">Aktifkan Pembayaran QRIS</h3>
            <p class="text-xs text-gray-500 mt-1">
                Pembayaran QRIS otomatis tersedia di Paket Pro seharga Rp 18.000/bln.
            </p>
        </div>

        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-200 text-left text-xs space-y-1.5">
            <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>QRIS Dinamis Otomatis</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Produk Tanpa Batas</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Kontrol Shift & Kasir Lengkap</span>
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <a href="{{ route('kasira.pricing') }}"
               class="block w-full py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-black text-xs font-extrabold transition shadow-md shadow-amber-500/20">
                Upgrade ke Pro (Rp 18.000/bln) &rsaquo;
            </a>
            <button type="button" onclick="document.getElementById('upgrade-pro-modal').classList.add('hidden')"
                    class="block w-full py-2 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-800">
                Nanti Saja
            </button>
        </div>
    </div>
<style>
@media print {
    body * {
        visibility: hidden !important;
    }
    #receipt-modal, #receipt-modal * {
        visibility: visible !important;
    }
    #receipt-modal {
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        display: flex !important;
        justify-content: flex-start !important;
        align-items: flex-start !important;
        z-index: 99999 !important;
    }
    #receipt-modal > div:last-child {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        max-width: 80mm !important;
        width: 100% !important;
    }
    #printable-receipt-content {
        position: relative !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-width: 80mm !important;
        margin: 0 !important;
        padding: 4mm !important;
        background: #ffffff !important;
        color: #000000 !important;
        border: none !important;
        box-shadow: none !important;
        font-family: 'Courier New', Courier, monospace !important;
        font-size: 11px !important;
        line-height: 1.25 !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endsection

@push('scripts')
<div id="kasir-data"
     data-products='@json($products)'
     data-initial-product-id='@json($initialProductId)'
     data-is-business-pro='@json($business->isPro())'
     data-checkout-url="{{ route('kasira.kasir.checkout') }}"
     data-business-name="{{ $business->name }}"
     data-cashier-name="{{ auth()->user()->name }}"
     hidden></div>

<script>
    // In-memory Cart state
    let cart = [];
    let paymentMethod = 'cash';
    const kasirData = document.getElementById('kasir-data');
    const productsData = JSON.parse(kasirData.dataset.products);
    const initialProductId = JSON.parse(kasirData.dataset.initialProductId);
    const isBusinessPro = JSON.parse(kasirData.dataset.isBusinessPro);
    const businessName = kasirData.dataset.businessName;
    const cashierName = kasirData.dataset.cashierName;
    let currentQrisTransaction = null;
    let qrisPollTimer = null;
    let lastCompletedTransaction = null;

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function addToCart(productId) {
        productId = Number(productId);
        const product = productsData.find(p => p.id === productId);
        if (!product) return;

        if (product.stock <= 0) {
            alert('Stok produk habis!');
            return;
        }

        const existing = cart.find(item => item.product_id === productId);
        if (existing) {
            if (existing.quantity >= product.stock) {
                alert(`Tidak dapat menambah melebihi stok yang tersedia (${product.stock}).`);
                return;
            }
            existing.quantity += 1;
        } else {
            cart.push({
                product_id: product.id,
                name: product.name,
                price: parseFloat(product.selling_price),
                stock: product.stock,
                quantity: 1,
            });
        }

        renderCart();
    }

    function updateQuantity(productId, delta) {
        const item = cart.find(i => i.product_id === productId);
        if (!item) return;

        const newQty = item.quantity + delta;
        if (newQty <= 0) {
            removeFromCart(productId);
            return;
        }

        if (newQty > item.stock) {
            alert(`Stok hanya tersedia ${item.stock}.`);
            return;
        }

        item.quantity = newQty;
        renderCart();
    }

    function removeFromCart(productId) {
        cart = cart.filter(i => i.product_id !== productId);
        renderCart();
    }

    function clearCart() {
        if (cart.length > 0 && confirm('Kosongkan semua barang di keranjang?')) {
            cart = [];
            renderCart();
        }
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const emptyState = document.getElementById('cart-empty-state');
        const countBadge = document.getElementById('cart-badge-count');
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        const submitBtn = document.getElementById('btn-submit-order');

        const totalItemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
        countBadge.textContent = totalItemsCount;

        if (cart.length === 0) {
            container.innerHTML = '';
            container.classList.add('hidden');
            if (emptyState) emptyState.classList.remove('hidden');
            subtotalEl.textContent = 'Rp 0';
            totalEl.textContent = 'Rp 0';
            submitBtn.disabled = true;
            calculateChange();
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        container.classList.remove('hidden');
        let total = 0;
        let html = '';

        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            html += `
                <div class="pt-2 flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <h4 class="text-xs font-bold text-[#111827] truncate">${item.name}</h4>
                        <span class="text-[10px] text-[#6B7280]">${formatRupiah(item.price)}</span>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" onclick="updateQuantity(${item.product_id}, -1)"
                                class="w-6 h-6 rounded-lg bg-[#F3F4F6] text-[#111827] font-bold text-xs flex items-center justify-center hover:bg-[#E5E7EB]">-</button>
                        <span class="w-6 text-center text-xs font-bold text-[#111827]">${item.quantity}</span>
                        <button type="button" onclick="updateQuantity(${item.product_id}, 1)"
                                class="w-6 h-6 rounded-lg bg-[#F3F4F6] text-[#111827] font-bold text-xs flex items-center justify-center hover:bg-[#E5E7EB]">+</button>
                    </div>

                    <div class="text-right shrink-0 min-w-[70px]">
                        <span class="text-xs font-bold text-[#111827] block">${formatRupiah(subtotal)}</span>
                        <button type="button" onclick="removeFromCart(${item.product_id})" class="text-[10px] text-red-500 hover:underline">Hapus</button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        subtotalEl.textContent = formatRupiah(total);
        totalEl.textContent = formatRupiah(total);
        submitBtn.disabled = false;

        calculateChange();
    }

    function setPaymentMethod(method) {
        if (method === 'qris' && !isBusinessPro) {
            document.getElementById('upgrade-pro-modal').classList.remove('hidden');
            return;
        }

        paymentMethod = method;
        const cashSection = document.getElementById('cash-payment-section');

        document.querySelectorAll('.pay-method-btn').forEach(btn => {
            btn.classList.remove('bg-[#5300B7]', 'text-white', 'border-[#5300B7]');
            btn.classList.add('bg-white', 'text-[#6B7280]', 'border-[#E5E7EB]');
        });

        const activeBtn = document.getElementById(`btn-pay-${method}`);
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-[#6B7280]', 'border-[#E5E7EB]');
            activeBtn.classList.add('bg-[#5300B7]', 'text-white', 'border-[#5300B7]');
        }

        if (method === 'cash') {
            cashSection.classList.remove('hidden');
        } else {
            cashSection.classList.add('hidden');
        }
    }

    function calculateTotalAmount() {
        return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    }

    function calculateChange() {
        const total = calculateTotalAmount();
        const paidInput = document.getElementById('cash-paid-input');
        const changeEl = document.getElementById('cash-change-display');

        if (paymentMethod !== 'cash') {
            changeEl.textContent = 'Rp 0';
            return;
        }

        const paid = parseFloat(paidInput.value) || 0;
        const change = paid - total;

        if (change >= 0) {
            changeEl.textContent = formatRupiah(change);
            changeEl.className = 'text-emerald-600 font-bold';
        } else {
            changeEl.textContent = `Kurang ${formatRupiah(Math.abs(change))}`;
            changeEl.className = 'text-red-500 font-bold';
        }
    }

    function setCashPreset(preset) {
        const total = calculateTotalAmount();
        const paidInput = document.getElementById('cash-paid-input');

        if (preset === 'exact') {
            paidInput.value = total;
        } else {
            paidInput.value = preset;
        }
        calculateChange();
    }

    async function processCheckout() {
        if (cart.length === 0) return;

        const total = calculateTotalAmount();
        let paid = total;

        if (paymentMethod === 'cash') {
            paid = parseFloat(document.getElementById('cash-paid-input').value) || 0;
            if (paid < total) {
                alert(`Nominal pembayaran tunai kurang! Total harus dibayar: ${formatRupiah(total)}.`);
                return;
            }
        }

        const submitBtn = document.getElementById('btn-submit-order');
        const submitText = document.getElementById('btn-submit-text');
        submitBtn.disabled = true;
        submitText.textContent = 'Memproses...';

        try {
            const res = await fetch(kasirData.dataset.checkoutUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    items: cart.map(i => ({ product_id: i.product_id, quantity: i.quantity })),
                    payment_method: paymentMethod,
                    paid_amount: paid,
                }),
            });

            const data = await res.json();

            if (data.upgrade_required) {
                document.getElementById('upgrade-pro-modal').classList.remove('hidden');
                return;
            }

            if (data.success && data.transaction) {
                lastCompletedTransaction = data.transaction;

                // Update local products stock
                cart.forEach(cartItem => {
                    const localProd = productsData.find(p => p.id === cartItem.product_id);
                    if (localProd) localProd.stock -= cartItem.quantity;
                });
                cart = [];
                renderCart();

                // If QRIS method, open QRIS modal and start polling
                if (paymentMethod === 'qris') {
                    currentQrisTransaction = data.transaction;
                    openQrisModal(data);
                } else {
                    showReceiptModal(data.transaction);
                }
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses transaksi.');
            }
        } catch (err) {
            console.error(err);
            alert('Gagal menghubungi server: ' + err.message);
        } finally {
            submitBtn.disabled = false;
            submitText.textContent = 'Selesaikan Transaksi';
        }
    }

    function openQrisModal(checkoutData) {
        const trx = checkoutData.transaction;
        document.getElementById('qris-modal-amount').textContent = trx.formatted_total;
        document.getElementById('qris-modal-invoice').textContent = trx.invoice_number;

        const qrisImg = document.getElementById('qris-image');
        const qrisWarning = document.getElementById('qris-unconfigured-warning');
        const qrisAutoBadge = document.getElementById('qris-auto-badge');

        if (checkoutData.qris && checkoutData.qris.success) {
            if (qrisWarning) qrisWarning.classList.add('hidden');
            if (qrisAutoBadge) qrisAutoBadge.classList.remove('hidden');

            if (checkoutData.qris.qr_url) {
                qrisImg.src = checkoutData.qris.qr_url;
            } else if (checkoutData.qris.qr_string) {
                qrisImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(checkoutData.qris.qr_string)}`;
            }

            // Start polling payment status every 2.5s
            if (qrisPollTimer) clearInterval(qrisPollTimer);
            qrisPollTimer = setInterval(pollQrisStatus, 2500);
        } else {
            // Unconfigured or failed Midtrans QRIS
            if (qrisWarning) {
                qrisWarning.classList.remove('hidden');
                const warningMsgEl = document.getElementById('qris-warning-message');
                if (warningMsgEl) {
                    warningMsgEl.textContent = checkoutData.qris?.message || 'Midtrans QRIS belum dikonfigurasi di file .env (MIDTRANS_SERVER_KEY). Mode manual aktif.';
                }
            }
            if (qrisAutoBadge) qrisAutoBadge.classList.add('hidden');
            qrisImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(trx.invoice_number)}`;
            if (qrisPollTimer) clearInterval(qrisPollTimer);
        }

        document.getElementById('qris-modal').classList.remove('hidden');
    }

    async function pollQrisStatus() {
        if (!currentQrisTransaction) return;

        try {
            const res = await fetch(`/api/kasira/qris/${currentQrisTransaction.id}/status`);
            const data = await res.json();

            if (data.is_paid) {
                clearInterval(qrisPollTimer);
                closeQrisModal();
                showReceiptModal(currentQrisTransaction);
            }
        } catch (e) {
            console.warn('QRIS status check error:', e);
        }
    }

    async function manualConfirmQris() {
        if (!currentQrisTransaction) return;

        try {
            const res = await fetch(`/api/kasira/qris/${currentQrisTransaction.id}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            });
            const data = await res.json();
            if (data.success) {
                if (qrisPollTimer) clearInterval(qrisPollTimer);
                closeQrisModal();
                showReceiptModal(currentQrisTransaction);
            }
        } catch (e) {
            alert('Gagal konfirmasi QRIS: ' + e.message);
        }
    }

    function closeQrisModal() {
        if (qrisPollTimer) clearInterval(qrisPollTimer);
        document.getElementById('qris-modal').classList.add('hidden');
    }

    function showReceiptModal(t) {
        document.getElementById('receipt-invoice-number').textContent = t.invoice_number;
        const invEl = document.getElementById('receipt-inv');
        if (invEl) invEl.textContent = t.invoice_number;
        document.getElementById('receipt-time').textContent = t.created_at;
        document.getElementById('receipt-method').textContent = t.payment_method;
        document.getElementById('receipt-total').textContent = t.formatted_total;
        document.getElementById('receipt-paid').textContent = formatRupiah(t.paid_amount);
        document.getElementById('receipt-change').textContent = t.formatted_change;

        if (t.cashier_name) {
            const cashierEl = document.getElementById('receipt-cashier');
            if (cashierEl) cashierEl.textContent = t.cashier_name;
        }
        if (t.customer_name) {
            const customerEl = document.getElementById('receipt-customer');
            if (customerEl) customerEl.textContent = t.customer_name;
        }
        if (t.business_name) {
            const bNameEl = document.getElementById('receipt-store-name');
            if (bNameEl) bNameEl.textContent = t.business_name;
        }
        if (t.business_address) {
            const bAddrEl = document.getElementById('receipt-store-address');
            if (bAddrEl) bAddrEl.textContent = t.business_address;
        }
        if (t.business_phone && t.business_phone !== '-') {
            const bPhoneEl = document.getElementById('receipt-store-phone');
            if (bPhoneEl) bPhoneEl.textContent = 'Telp: ' + t.business_phone;
        }
        if (t.receipt_footer) {
            const footerEl = document.getElementById('receipt-footer-text');
            if (footerEl) footerEl.textContent = t.receipt_footer;
        }

        const itemsContainer = document.getElementById('receipt-items-list');
        if (itemsContainer && t.items) {
            itemsContainer.innerHTML = t.items.map(item => `
                <div class="flex justify-between items-center text-xs">
                    <div class="min-w-0 flex-1">
                        <span class="font-bold text-[#111827] block truncate">${item.name}</span>
                        <span class="text-[10px] text-[#6B7280]">${item.quantity}x @ ${item.formatted_price}</span>
                    </div>
                    <span class="font-bold text-[#111827] shrink-0">${item.formatted_subtotal}</span>
                </div>
            `).join('');
        }

        document.getElementById('receipt-modal').classList.remove('hidden');
    }

    function printThermalReceipt() {
        window.print();
    }

    function shareReceiptFromPos() {
        if (!lastCompletedTransaction) return;
        const t = lastCompletedTransaction;
        let itemsListText = '';
        if (t.items && t.items.length > 0) {
            itemsListText = t.items.map(i => `${i.name}\n  ${i.quantity}x @ ${i.formatted_price} = ${i.formatted_subtotal}`).join('\n') + '\n--------------------------------\n';
        }

        const text = `*STRUK PEMBELIAN*\n*${t.business_name || businessName}*\n` +
            (t.business_phone && t.business_phone !== '-' ? `Telp: ${t.business_phone}\n` : '') +
            (t.business_address && t.business_address !== '-' ? `Alamat: ${t.business_address}\n` : '') +
            `--------------------------------\n` +
            `No. Invoice: ${t.invoice_number}\n` +
            `Waktu: ${t.created_at}\n` +
            `Kasir: ${t.cashier_name || cashierName}\n` +
            `Pelanggan: ${t.customer_name || 'Umum'}\n` +
            `--------------------------------\n` +
            itemsListText +
            `*TOTAL: ${t.formatted_total}*\n` +
            `Bayar (${t.payment_method.toUpperCase()}): Rp ${new Intl.NumberFormat('id-ID').format(t.paid_amount)}\n` +
            `Kembali: ${t.formatted_change}\n` +
            `--------------------------------\n` +
            `${t.receipt_footer || 'Terima kasih telah berbelanja di ' + (t.business_name || 'toko kami')}!`;

        if (navigator.share) {
            navigator.share({
                title: 'Struk ' + t.invoice_number,
                text: text
            }).catch(console.error);
        } else {
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }
    }

    function closeReceiptModal() {
        document.getElementById('receipt-modal').classList.add('hidden');
    }

    // Category Filter in Products Catalog
    function filterCategory(categorySlug, buttonEl) {
        document.querySelectorAll('.cat-pill').forEach(btn => {
            btn.classList.remove('bg-[#5300B7]', 'text-white', 'shadow-2xs');
            btn.classList.add('bg-[#F3F4F6]', 'text-[#6B7280]');
        });
        buttonEl.classList.remove('bg-[#F3F4F6]', 'text-[#6B7280]');
        buttonEl.classList.add('bg-[#5300B7]', 'text-white', 'shadow-2xs');

        const cards = document.querySelectorAll('.pos-product-card');
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (categorySlug === 'all' || cardCat === categorySlug) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Real-time search in Catalog
    document.getElementById('kasir-search')?.addEventListener('input', function(e) {
        const val = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.pos-product-card');
        cards.forEach(card => {
            const name = card.getAttribute('data-name')?.toLowerCase() || '';
            const barcode = card.getAttribute('data-barcode')?.toLowerCase() || '';
            if (name.includes(val) || barcode.includes(val)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Check if redirected with an initial product
    if (initialProductId) {
        addToCart(parseInt(initialProductId));
    }
</script>
@endpush
