@extends('layouts.kasira')

@section('title', 'Scan Barcode')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('kasira.dashboard') }}" class="p-2 rounded-xl bg-white border border-[#E5E7EB] text-[#6B7280] hover:text-[#111827] hover:bg-[#F9FAFB] transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#111827]">Scan Barcode</h1>
                <p class="text-xs text-[#6B7280]">Pindai barcode produk ritel langsung menggunakan kamera perangkat Anda.</p>
            </div>
        </div>

        <button type="button" onclick="toggleManualInputModal(true)"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-[#E5E7EB] hover:bg-[#F9FAFB] text-xs font-bold text-[#111827] transition shadow-2xs">
            <svg class="w-4 h-4 text-[#5300B7]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Input Manual</span>
        </button>
    </div>

    <!-- Scanner Viewport Card -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-5 overflow-hidden">
        <!-- Camera Viewport Box -->
        <div class="relative w-full aspect-4/3 sm:aspect-16/10 bg-[#0E131F] rounded-xl overflow-hidden flex flex-col items-center justify-center border border-[#1F2937]">
            <!-- Video Container for Html5Qrcode -->
            <div id="reader" class="w-full h-full object-cover"></div>

            <!-- Scanning Overlay Grid / Target Box -->
            <div id="scanner-reticle" class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
                <div class="w-64 sm:w-72 h-40 sm:h-44 border-2 border-[#5300B7] rounded-xl relative shadow-lg shadow-[#5300B7]/20 flex items-center justify-center">
                    <!-- Corner Markers -->
                    <div class="absolute -top-1 -left-1 w-4 h-4 border-t-4 border-l-4 border-white rounded-tl"></div>
                    <div class="absolute -top-1 -right-1 w-4 h-4 border-t-4 border-r-4 border-white rounded-tr"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-4 border-l-4 border-white rounded-bl"></div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-4 border-r-4 border-white rounded-br"></div>

                    <!-- Animated Laser Line -->
                    <div id="laser-line" class="absolute inset-x-2 h-0.5 bg-red-500 shadow-[0_0_8px_#EF4444] animate-pulse"></div>
                </div>
                <p class="text-[11px] text-white/80 font-medium mt-4 bg-black/60 px-3 py-1 rounded-full backdrop-blur-xs">
                    Arahkan barcode produk ke dalam kotak
                </p>
            </div>

            <!-- Permission / Loading / Error States -->
            <div id="camera-state-overlay" class="absolute inset-0 bg-[#0E131F] z-10 flex flex-col items-center justify-center p-6 text-center">
                <!-- Initial State: Request Access Button -->
                <div id="state-prompt" class="space-y-4 max-w-sm">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-[#5300B7]/20 border border-[#5300B7]/40 flex items-center justify-center text-[#A78BFA]">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Mulai Pindai Barcode</h3>
                        <p class="text-xs text-[#9CA3AF] mt-1">
                            Izinkan akses kamera browser Anda untuk mulai memindai barcode produk secara otomatis.
                        </p>
                    </div>
                    <button type="button" onclick="startCameraScanner()"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-md shadow-[#5300B7]/30 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Aktifkan Kamera</span>
                    </button>
                </div>

                <!-- Error State -->
                <div id="state-error" class="hidden space-y-4 max-w-sm">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-red-950/50 border border-red-800 flex items-center justify-center text-red-400">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Kamera Tidak Dapat Diakses</h3>
                        <p id="error-message-text" class="text-xs text-[#9CA3AF] mt-1">
                            Pastikan Anda telah memberikan izin akses kamera atau coba gunakan input barcode manual.
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="startCameraScanner()"
                                class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition">
                            Coba Lagi
                        </button>
                        <button type="button" onclick="toggleManualInputModal(true)"
                                class="px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold transition">
                            Input Manual
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls Bar -->
        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-[#F3F4F6]">
            <div class="text-xs text-[#6B7280]">
                Format didukung: <span class="font-semibold text-[#111827]">EAN-13, EAN-8, UPC, Code 128, QR</span>
            </div>
            <button type="button" onclick="toggleManualInputModal(true)"
                    class="text-xs font-bold text-[#5300B7] hover:underline flex items-center gap-1">
                <span>Masukkan Barcode Manual</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Scan Result Card (Appears dynamically) -->
    <div id="scan-result-card" class="hidden bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-5 transition-all">
        <!-- Case 1: Product Found -->
        <div id="result-found" class="hidden space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#F3F4F6]">
                <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Produk ditemukan</span>
                </div>
                <span id="result-barcode-badge" class="font-mono text-[10px] bg-[#F3F4F6] text-[#4B5563] px-2 py-0.5 rounded">
                    899...
                </span>
            </div>

            <div class="flex items-center gap-4">
                <div id="result-img-box" class="w-16 h-16 rounded-xl bg-[#F3F4F6] border border-[#E5E7EB] overflow-hidden shrink-0 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 id="result-name" class="text-base font-bold text-[#111827] truncate">Nama Produk</h3>
                    <p id="result-category" class="text-xs text-[#6B7280]">Kategori</p>
                    <div class="mt-1 flex items-baseline gap-3">
                        <span id="result-price" class="text-base font-bold text-[#5300B7]">Rp 0</span>
                        <span id="result-stock" class="text-xs font-semibold text-[#111827]">Stok: 0</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#F3F4F6]">
                <button type="button" onclick="resumeScanner()"
                        class="px-3.5 py-2 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#374151] hover:bg-[#F9FAFB] transition">
                    Scan Barcode Lain
                </button>
                <a id="btn-add-to-cart" href="#"
                   class="px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-md shadow-[#5300B7]/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Tambah ke Kasir</span>
                </a>
            </div>
        </div>

        <!-- Case 2: Product Not Found -->
        <div id="result-not-found" class="hidden space-y-3">
            <div class="flex items-center gap-2 text-amber-600 font-bold text-xs pb-2 border-b border-[#F3F4F6]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Barcode belum terdaftar</span>
            </div>
            <div>
                <p class="text-xs text-[#6B7280]">Barcode yang dipindai belum ada di katalog bisnis Anda:</p>
                <p id="not-found-barcode-display" class="font-mono text-sm font-bold text-[#111827] mt-1 bg-[#F9FAFB] p-2 rounded-lg border border-[#E5E7EB]">
                    899...
                </p>
            </div>
            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-[#F3F4F6]">
                <button type="button" onclick="resumeScanner()"
                        class="px-3.5 py-2 rounded-xl border border-[#E5E7EB] text-xs font-bold text-[#374151] hover:bg-[#F9FAFB] transition">
                    Scan Ulang
                </button>
                <a id="btn-register-product" href="#"
                   class="px-4 py-2 rounded-xl bg-[#5300B7] hover:bg-[#430095] text-white text-xs font-bold shadow-md shadow-[#5300B7]/25 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Daftarkan Produk</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Manual Input Barcode Modal -->
<div id="manual-barcode-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" onclick="toggleManualInputModal(false)"></div>
    <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-[#E5E7EB] z-10 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-[#F3F4F6]">
            <h3 class="text-base font-bold text-[#111827]">Masukkan Barcode Manual</h3>
            <button type="button" onclick="toggleManualInputModal(false)" class="text-[#9CA3AF] hover:text-[#111827]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form onsubmit="handleManualSearch(event)" class="space-y-4">
            <div>
                <label for="manual-barcode-input" class="block text-xs font-bold text-[#111827] uppercase tracking-wider mb-1.5">
                    Nomor Barcode / SKU
                </label>
                <input type="text" id="manual-barcode-input" required placeholder="Contoh: 8991234567890"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] font-mono text-sm text-[#111827] placeholder-[#9CA3AF] focus:border-[#5300B7] outline-none">
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-[#F3F4F6]">
                <button type="button" onclick="toggleManualInputModal(false)" class="px-4 py-2 rounded-xl border border-[#E5E7EB] text-xs font-semibold text-[#6B7280]">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#5300B7] text-white text-xs font-bold hover:bg-[#430095] shadow-xs">
                    Cari Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let html5QrCode = null;
    let isScanning = false;

    function toggleManualInputModal(show) {
        const modal = document.getElementById('manual-barcode-modal');
        if (modal) {
            if (show) {
                modal.classList.remove('hidden');
                document.getElementById('manual-barcode-input')?.focus();
            } else {
                modal.classList.add('hidden');
            }
        }
    }

    async function startCameraScanner() {
        const stateOverlay = document.getElementById('camera-state-overlay');
        const statePrompt = document.getElementById('state-prompt');
        const stateError = document.getElementById('state-error');

        statePrompt.classList.add('hidden');
        stateError.classList.add('hidden');

        try {
            if (!window.Html5Qrcode) {
                throw new Error("Library pemindai barcode belum siap.");
            }

            if (!html5QrCode) {
                html5QrCode = new window.Html5Qrcode("reader");
            }

            const config = {
                fps: 15,
                qrbox: { width: 260, height: 160 },
                aspectRatio: 1.333334
            };

            await html5QrCode.start(
                { facingMode: "environment" },
                config,
                onBarcodeSuccess,
                onBarcodeError
            );

            isScanning = true;
            stateOverlay.classList.add('hidden');
        } catch (err) {
            console.error("Camera error:", err);
            stateOverlay.classList.remove('hidden');
            stateError.classList.remove('hidden');
            document.getElementById('error-message-text').textContent = err.message || "Gagal mengaktifkan kamera.";
        }
    }

    async function onBarcodeSuccess(decodedText, decodedResult) {
        if (!isScanning) return;
        isScanning = false;

        // Pause scanner
        if (html5QrCode) {
            try {
                await html5QrCode.pause();
            } catch (e) {
                console.warn(e);
            }
        }

        // Query backend for product with this barcode
        queryBarcode(decodedText);
    }

    function onBarcodeError(errorMessage) {
        // Continuous decoding frame failures are ignored until a clear barcode is detected
    }

    async function queryBarcode(barcode) {
        const resultCard = document.getElementById('scan-result-card');
        const resultFound = document.getElementById('result-found');
        const resultNotFound = document.getElementById('result-not-found');

        resultCard.classList.remove('hidden');
        resultFound.classList.add('hidden');
        resultNotFound.classList.add('hidden');

        try {
            const res = await fetch(`/api/kasira/barcode/search?barcode=${encodeURIComponent(barcode)}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.found && data.product) {
                const p = data.product;
                document.getElementById('result-name').textContent = p.name;
                document.getElementById('result-category').textContent = p.category;
                document.getElementById('result-price').textContent = p.formatted_price;
                document.getElementById('result-stock').textContent = `Stok: ${p.stock}`;
                document.getElementById('result-barcode-badge').textContent = p.barcode || barcode;

                const imgBox = document.getElementById('result-img-box');
                if (p.image_url) {
                    imgBox.innerHTML = `<img src="${p.image_url}" class="w-full h-full object-cover" alt="${p.name}">`;
                } else {
                    imgBox.innerHTML = `<svg class="w-8 h-8 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`;
                }

                document.getElementById('btn-add-to-cart').href = `/kasir?add_product_id=${p.id}`;
                resultFound.classList.remove('hidden');
            } else {
                document.getElementById('not-found-barcode-display').textContent = data.barcode || barcode;
                document.getElementById('btn-register-product').href = data.register_url || `/produk/create?barcode=${encodeURIComponent(barcode)}`;
                resultNotFound.classList.remove('hidden');
            }

            resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } catch (err) {
            console.error("Query error:", err);
            alert("Terjadi kesalahan saat mencari barcode: " + err.message);
            resumeScanner();
        }
    }

    function resumeScanner() {
        document.getElementById('scan-result-card')?.classList.add('hidden');
        if (html5QrCode) {
            try {
                html5QrCode.resume();
                isScanning = true;
            } catch (e) {
                startCameraScanner();
            }
        } else {
            startCameraScanner();
        }
    }

    function handleManualSearch(e) {
        e.preventDefault();
        const val = document.getElementById('manual-barcode-input').value.trim();
        if (val) {
            toggleManualInputModal(false);
            queryBarcode(val);
        }
    }

    window.addEventListener('beforeunload', () => {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().catch(() => {});
        }
    });
</script>
@endpush

