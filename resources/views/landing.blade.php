@php
    /**
     * Public landing page &mdash; NEXUS AI OFFICE.
     *
     * Self-contained: this view ships its own stylesheet and script bundle
     * (resources/css/landing.css + resources/js/landing.js) and deliberately
     * does not extend layouts/app so nothing here can leak into the
     * authenticated app.
     *
     * Module availability below is derived from routes/web.php: only modules
     * with a real route AND a real view are labelled "Tersedia".
     */

    $isMember = auth()->check();
    $ctaLabel = $isMember ? 'Buka Workspace' : 'Masuk';
    // `workspace.select` already resolves to the right single-module dashboard,
    // so it is the correct "open my workspace" target for any signed-in user.
    $ctaUrl = $isMember ? route('workspace.select') : route('login');
    $loginUrl = route('login');

    // Four words, single scramble slot. Width is locked by the widest word
    // (min-width ~9ch) so the headline never reflows while the word scrambles.
    $rotateWords = ['PRODUKTIF', 'TERPADU', 'CEPAT', 'RINGAN'];

    // Scrub-card activity pills, stacked bottom-up in a loop.
    $scrubPills = ['Lagu masuk antrean', 'Catatan baru', 'Unduhan selesai', 'Transaksi tercatat'];

    $marqueeItems = [
        ['name' => 'NEXUS AI', 'icon' => 'ai'],
        ['name' => 'Music', 'icon' => 'music'],
        ['name' => 'Downloader', 'icon' => 'downloader'],
        ['name' => 'Kasira', 'icon' => 'kasira'],
        ['name' => 'QR Tools', 'icon' => 'qr'],
        ['name' => 'AI Assistant', 'icon' => 'assistant'],
    ];

    /**
     * Lima modul showcase (daftar editorial kiri).
     * Urutan: NEXUS AI, Music, Downloader, Kasira, Alat Produktivitas.
     * `live` true hanya di mana route + view benar-benar ada (Music, Kasira); sisanya "Segera".
     */
    $modules = [
        [
            'index' => '01',
            'name' => 'NEXUS AI',
            'mock' => 'nexus-ai',
            'title' => 'NEXUS AI',
            'desc' => 'Asisten AI cerdas untuk membantu brainstorming ide, menyusun ringkasan dokumen, dan menjawab pertanyaan kerja langsung di workspace.',
            'features' => [
                ['Percakapan Cerdas', false],
                ['Ringkasan Teks', false],
                ['Generator Ide', false],
                ['Terintegrasi Data', false],
            ],
        ],
        [
            'index' => '02',
            'name' => 'Music',
            'mock' => 'music',
            'title' => 'Music',
            'desc' => 'Pemutar musik dengan pencarian, chart, playlist, riwayat, dan favorit yang tersimpan di akun.',
            'features' => [
                ['Pemutar audio', true],
                ['Playlist', true],
                ['Chart', true],
                ['Pencarian', true],
                ['Favorit', true],
            ],
        ],
        [
            'index' => '03',
            'name' => 'Downloader',
            'mock' => 'downloader',
            'title' => 'Downloader',
            'desc' => 'Unduh media dari beberapa platform ke satu antrean yang rapi, lengkap dengan riwayat unduhan.',
            'features' => [
                ['YouTube & TikTok', false],
                ['Instagram Video', false],
                ['Format MP3 / MP4', false],
                ['Riwayat Unduhan', false],
            ],
        ],
        [
            'index' => '04',
            'name' => 'Kasira',
            'mock' => 'kasira',
            'title' => 'Kasira',
            'desc' => 'Point of sale untuk warung dan UMKM: kasir, produk, stok, pelanggan, piutang, hingga laporan.',
            'features' => [
                ['Kasir / POS', true],
                ['Produk & Stok', true],
                ['Transaksi', true],
                ['Laporan & Statistik', true],
                ['Mudah dipakai', true],
            ],
        ],
        [
            'index' => '05',
            'name' => 'Alat Produktivitas',
            'mock' => 'tools',
            'title' => 'Alat Produktivitas',
            'desc' => 'Rangkaian utilitas produktivitas harian terpadu: generator & pemindai kode QR, catatan cepat, dan kalkulator.',
            'features' => [
                ['QR Generator', false],
                ['Pindai QR', false],
                ['Catatan Cepat', false],
                ['Kalkulator Harian', false],
            ],
        ],
    ];

    // URL AURA GLOBAL terverifikasi dari config lifehub.developer.other_projects.
    $auraUrl = config('lifehub.developer.other_projects.0.url');

    // Tiga produk showcase. `url` null = tanpa link (route memang belum ada).
    $projects = [
        [
            'num' => '01',
            'cat' => 'Digital workspace',
            'name' => 'NEXUS AI OFFICE',
            'mock' => 'workspace',
            'desc' => 'Ekosistem workspace digital yang menyatukan berbagai kebutuhan produktivitas dan tools dalam satu tempat.',
            'url' => $ctaUrl,
            'cta' => 'Buka Workspace',
        ],
        [
            'num' => '02',
            'cat' => 'POS UMKM',
            'name' => 'KASIRA',
            'mock' => 'pos',
            'desc' => 'Workspace kasir untuk membantu pengelolaan produk, transaksi, dan kebutuhan operasional UMKM.',
            'url' => route('kasira.dashboard'),
            'cta' => 'Buka Kasira',
        ],
        [
            'num' => '03',
            'cat' => 'Website development',
            'name' => 'AURA GLOBAL',
            'mock' => 'browser',
            'desc' => 'Jasa pembuatan website profesional untuk bisnis dan kebutuhan digital. Bangun website yang profesional, responsif, dan disesuaikan dengan kebutuhan bisnis.',
            'url' => $auraUrl,
            'cta' => 'Lihat AURA GLOBAL',
            'ext' => true,
        ],
    ];

    // Lima prinsip editorial — tanpa klaim angka, tanpa testimoni.
    $principles = [
        ['01', 'Satu Workspace', 'Berbagai kebutuhan digital ditempatkan dalam satu ruang kerja yang lebih mudah diakses.'],
        ['02', 'Terintegrasi', 'Modul dirancang sebagai bagian dari satu pengalaman workspace, bukan kumpulan tools yang terpisah.'],
        ['03', 'Praktis', 'Fokus pada akses yang sederhana agar aktivitas digital sehari-hari tidak terasa berantakan.'],
        ['04', 'Kasira untuk UMKM', 'Kasira menghadirkan kebutuhan POS untuk membantu aktivitas operasional UMKM.'],
        ['05', 'Terus Berkembang', 'NEXUS dirancang sebagai ekosistem yang dapat terus berkembang bersama kebutuhan penggunanya.'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NEXUS AI OFFICE &mdash; Ekosistem Workspace Digital</title>
    <meta name="description" content="Satu workspace untuk AI, musik, unduhan, dan kasir UMKM.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="NEXUS AI OFFICE">
    <meta property="og:description" content="Satu workspace untuk AI, musik, unduhan, dan kasir UMKM.">
    <meta property="og:site_name" content="NEXUS AI OFFICE">
    <meta property="og:locale" content="id_ID">
    <meta property="og:image" content="{{ asset('images/landing/og-image.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Tepat satu link Google Fonts (Bagian 5): Orbitron hero, Outfit pinned, Plus Jakarta Sans heading terang, Inter body. --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@800;900&family=Outfit:wght@900&family=Plus+Jakarta+Sans:wght@500;600;700&family=Inter:wght@400;500;600&display=swap">

        @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>

<body class="nx-body">
    <div class="nx-shell">

        {{-- ============================================================
             SECTION 0 &mdash; NAVBAR
             Colour adapts to whatever sits behind it via [data-nav].
             ============================================================ --}}
        <header class="nx-nav" id="nxNav" data-theme="dark">
            <a href="#beranda" class="nx-nav__brand">NEXUS AI OFFICE</a>

            <div class="nx-nav__actions">
                <a href="{{ $ctaUrl }}" class="nx-nav__cta">
                    {{ $ctaLabel }}
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M3.4 20.4 21.8 12 3.4 3.6l2.6 7.1 2.1 1.3-2.1 1.3-2.6 7.1Z" />
                    </svg>
                </a>
                <button type="button" class="nx-nav__burger" id="nxBurger" aria-label="Buka menu"
                    aria-expanded="false" aria-controls="nxOverlay">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </header>

        <div class="nx-overlay" id="nxOverlay" role="dialog" aria-modal="true" aria-label="Menu navigasi">
            <button type="button" class="nx-overlay__close" id="nxOverlayClose" aria-label="Tutup menu">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" aria-hidden="true">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
            <a href="#beranda" class="nx-overlay__link">Beranda</a>
            <a href="#fitur" class="nx-overlay__link">Fitur</a>
            <a href="#proyek" class="nx-overlay__link">Modul</a>
            <a href="{{ route('about') }}" class="nx-overlay__link">Tentang</a>
            <a href="{{ $ctaUrl }}" class="nx-overlay__link">{{ $ctaLabel }}</a>
        </div>

        {{-- ============================================================
             SECTION 1 — HERO
             ============================================================ --}}
        <section class="nx-hero" id="nxHero" data-theme="dark" data-nav="dark">
            <div class="nx-hero__shade" aria-hidden="true"></div>
            <div class="nx-hero__grain" aria-hidden="true"></div>

            {{-- Sosok Robot Hero: State 1 (wajah terbuka) & State 2 (masker SVG) --}}
            <div class="nx-hero__visual" data-hero-visual id="nxHeroVisual">
                <div class="nx-hero__figure" id="nxRobotFigure">
                    <picture>
                        <source srcset="{{ asset('images/landing/hero-robot.webp') }}" type="image/webp">
                        <img class="nx-hero__media nx-hero__media--base" id="nxRobotImg" src="{{ asset('images/landing/hero-robot.png') }}"
                            alt="NEXUS AI Cyber Avatar" loading="eager" decoding="async">
                    </picture>
                    <img class="nx-hero__mask" id="nxRobotMask" src="{{ asset('images/landing/hero-robot-mask-layer.svg') }}"
                        alt="Cyber Mask Layer" aria-hidden="true" loading="eager" decoding="async">
                </div>
            </div>

            <div class="nx-hero__content">
                <div class="nx-hero__copy">
                    <span class="nx-badge" data-hero-fade>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                        SATU RUANG, BANYAK ALAT
                    </span>

                    <h1 class="nx-hero__headline" id="nxHeroHeadline">
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>MEMBANGUN RUANG</span></span>
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>KERJA <span class="nx-swap" data-word-scramble
                                    data-words='@json($rotateWords)'>PRODUKTIF</span></span></span>
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line><span class="nx-line-part">DALAM SATU </span><span class="nx-line-part">TEMPAT</span></span></span>
                    </h1>
                </div>
            </div>

            <div class="nx-hero__foot-wrap" id="nxHeroFootWrap">
                <div class="nx-glass" data-hero-fade id="nxGlassCard">
                    <div class="nx-glass__head">
                        <span class="nx-glass__brand">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
                            <span>Tentang NEXUS</span>
                        </span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </div>
                    <div class="nx-glass__content">
                        <h3 class="nx-glass__title">Kami membangun perangkat kerja yang menyatu, bukan kumpulan aplikasi yang terpisah.</h3>
                        <p class="nx-glass__desc">AI, musik, unduhan, dan kasir UMKM berada di satu workspace yang sama. Dirancang agar tetap ringan dipakai setiap hari.</p>
                    </div>
                </div>

                <p class="nx-hero__foot" data-hero-fade id="nxHeroFoot">
                    KAMI MENYATUKAN AI, MUSIK, UNDUHAN, DAN KASIR UMKM DALAM SATU WORKSPACE YANG RAPI. SEMUA ALAT YANG KAMU BUTUHKAN ADA DI SATU TEMPAT, TANPA PERLU BERALIH APLIKASI, SUPAYA PEKERJAANMU LEBIH TERARAH DAN TETAP RINGAN SETIAP HARI.
                </p>
            </div>
        </section>

        {{-- ============================================================
             SECTION 2 — PINNED SCRUB (RUANG KERJA ... TERPADU)
             Pin ±700vh, scrub 0.6; dua kata sebaris terpusat di y:50vh.
             Kartu biru scrub berisi tumpukan pill notifikasi + tombol bawah.
             ============================================================ --}}
        <section class="nx-scrub" id="nxScrub" data-scrub data-nav="dark">
            <div class="nx-scrub__stage" id="nxScrubStage">
                <div class="nx-scrub__row" id="nxScrubRow">
                    <span class="nx-scrub__word nx-scrub__word--left" id="nxScrubLeft">RUANG KERJA</span>

                    <div class="nx-scrub__card" id="nxScrubCard" data-scrub-card>
                        <div class="nx-scrub__card-grid" aria-hidden="true"></div>

                        {{-- Film Mini 7 Scene --}}
                        <div class="nx-film" id="nxFilm">
                            {{-- SVG Filter Gooey Definition --}}
                            <svg class="nx-film__goo-svg" width="0" height="0" style="position: absolute; pointer-events: none;" aria-hidden="true">
                                <defs>
                                    <filter id="nxGooFilter">
                                        <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur" />
                                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                                        <feComposite in="SourceGraphic" in2="goo" operator="atop" />
                                    </filter>
                                </defs>
                            </svg>

                            {{-- SCENE 0: Riak 3 Lingkaran + "Setiap kerja" + "dimulai" --}}
                            <div class="nx-film__scene nx-film__s0" id="nxS0">
                                <div class="nx-film__ripple-wrap" aria-hidden="true">
                                    <span class="nx-film__ripple nx-film__ripple--1"></span>
                                    <span class="nx-film__ripple nx-film__ripple--2"></span>
                                    <span class="nx-film__ripple nx-film__ripple--3"></span>
                                </div>
                                <div class="nx-film__s0-copy">
                                    <span class="nx-film__s0-text" id="nxS0Text">Setiap kerja</span>
                                    <span class="nx-film__s0-badge" id="nxS0Badge">
                                        <span class="nx-film__s0-d" id="nxS0D">d</span><span class="nx-film__s0-rest">imulai</span>
                                    </span>
                                </div>
                            </div>

                            {{-- SCENE 1, 2, 3: Tombol Sampah Gooey + Pills + Kerucut Cahaya --}}
                            <div class="nx-film__scene nx-film__s123" id="nxS123">
                                {{-- Kerucut Cahaya (Scene 3) --}}
                                <div class="nx-film__light-cone" id="nxLightCone" aria-hidden="true"></div>

                                {{-- Stack of Pills --}}
                                <div class="nx-film__pills-area" id="nxPillsArea">
                                    <div class="nx-film__pill nx-film__pill--1" id="nxFilmPill1">
                                        <span>Banyak tab terbuka.</span>
                                    </div>
                                    <div class="nx-film__pill nx-film__pill--2" id="nxFilmPill2">
                                        <span>Data tercecer.</span>
                                    </div>
                                    <div class="nx-film__pill nx-film__pill--3" id="nxFilmPill3">
                                        <span>Harus ganti aplikasi.</span>
                                    </div>
                                </div>

                                {{-- Wadah Tombol Sampah + 6 Droplets dengan Gooey Filter --}}
                                <div class="nx-film__goo-stage" id="nxGooStage">
                                    <div class="nx-film__trash-btn" id="nxTrashBtn" aria-label="Wadah Terpadu">
                                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#4B0FB8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </div>
                                    <span class="nx-film__drop nx-film__drop--1" aria-hidden="true"></span>
                                    <span class="nx-film__drop nx-film__drop--2" aria-hidden="true"></span>
                                    <span class="nx-film__drop nx-film__drop--3" aria-hidden="true"></span>
                                    <span class="nx-film__drop nx-film__drop--4" aria-hidden="true"></span>
                                    <span class="nx-film__drop nx-film__drop--5" aria-hidden="true"></span>
                                    <span class="nx-film__drop nx-film__drop--6" aria-hidden="true"></span>
                                </div>
                            </div>

                            {{-- SCENE 4: Kartu Putih + "Lalu kejelasan masuk." + Kursor + "Mulai" --}}
                            <div class="nx-film__scene nx-film__s4" id="nxS4">
                                <div class="nx-film__s4-content">
                                    <h3 class="nx-film__s4-title">
                                        Lalu <span class="nx-film__highlight"><span class="nx-film__highlight-bg" id="nxS4HlBg"></span><span class="nx-film__highlight-txt">kejelasan</span></span> masuk.
                                    </h3>
                                </div>
                                <div class="nx-film__start-pill" id="nxStartPill">
                                    <span>Mulai</span>
                                </div>
                                <div class="nx-film__cursor" id="nxS4Cursor" aria-hidden="true">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#4B0FB8" stroke="#FFFFFF" stroke-width="1.5">
                                        <path d="m3 3 7.07 16.97 2.51-7.39 7.39-2.51L3 3z"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- SCENE 5: Orbit Bola Kaca + 3 Cincin + 5 Titik + 2 Kilau + Kata Berganti --}}
                            <div class="nx-film__scene nx-film__s5" id="nxS5">
                                <svg class="nx-film__orbit-svg" id="nxOrbitSvg" viewBox="0 0 600 400" fill="none" aria-hidden="true">
                                    <circle class="nx-film__ring nx-film__ring--1" cx="200" cy="200" r="85" stroke="rgba(255,255,255,0.35)" stroke-width="1.5" />
                                    <circle class="nx-film__ring nx-film__ring--2" cx="200" cy="200" r="145" stroke="rgba(255,255,255,0.35)" stroke-width="1.5" />
                                    <circle class="nx-film__ring nx-film__ring--3" cx="200" cy="200" r="215" stroke="rgba(255,255,255,0.35)" stroke-width="1.5" />
                                </svg>

                                <span class="nx-film__orbit-dot nx-film__dot--1" id="nxDot1" aria-hidden="true"></span>
                                <span class="nx-film__orbit-dot nx-film__dot--2" id="nxDot2" aria-hidden="true"></span>
                                <span class="nx-film__orbit-dot nx-film__dot--3" id="nxDot3" aria-hidden="true"></span>
                                <span class="nx-film__orbit-dot nx-film__dot--4" id="nxDot4" aria-hidden="true"></span>
                                <span class="nx-film__orbit-dot nx-film__dot--5" id="nxDot5" aria-hidden="true"></span>

                                <span class="nx-film__sparkle nx-film__sparkle--1" id="nxSparkle1" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="#FFFFFF"><path d="m12 0 3 9 9 3-9 3-3 9-3-9-9-3 9-3 3-9z"/></svg>
                                </span>
                                <span class="nx-film__sparkle nx-film__sparkle--2" id="nxSparkle2" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="10" height="10" fill="#FFFFFF"><path d="m12 0 3 9 9 3-9 3-3 9-3-9-9-3 9-3 3-9z"/></svg>
                                </span>

                                <div class="nx-film__sphere-wrap" id="nxSphereWrap">
                                    <div class="nx-film__sphere" id="nxSphere">
                                        <div class="nx-film__sphere-icon" id="nxSphereIcon"></div>
                                    </div>
                                </div>

                                <div class="nx-film__orbit-word-wrap" id="nxOrbitWordWrap">
                                    <span class="nx-film__orbit-word" id="nxOrbitWord">AI</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span class="nx-scrub__word nx-scrub__word--right" id="nxScrubRight">TERPADU</span>
                </div>
            </div>
        </section>

        {{-- ============================================================
             SECTION 3 &mdash; MARQUEE
             ============================================================ --}}
        <div class="nx-marquee" data-marquee data-nav="light">
            <div class="nx-marquee__track">
                {{-- Two identical groups; the loop translates exactly -50%. --}}
                @for ($g = 0; $g < 2; $g++)
                    <div class="nx-marquee__group" @if ($g === 1) aria-hidden="true" @endif>
                        @foreach ($marqueeItems as $item)
                            <span class="nx-marquee__item">
                                @if ($item['icon'] === 'ai')
                                    <svg class="nx-marquee__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                                @elseif ($item['icon'] === 'music')
                                    <svg class="nx-marquee__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                                @elseif ($item['icon'] === 'downloader')
                                    <svg class="nx-marquee__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                @elseif ($item['icon'] === 'kasira')
                                    <svg class="nx-marquee__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 16h2M11 16h2M16 16h2M6 12h12M6 8h12"/></svg>
                                @elseif ($item['icon'] === 'qr')
                                    <svg class="nx-marquee__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                @else
                                    <svg class="nx-marquee__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                @endif
                                <span>{{ $item['name'] }}</span>
                                <span class="nx-marquee__sep"></span>
                            </span>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>

        {{-- ============================================================
             SECTION 4 &mdash; KREM PINNED  (400vh)
             ============================================================ --}}
        <section class="nx-cream" id="fitur" data-modules data-nav="light">
            <div class="nx-cream__stage">
                <h2 class="nx-cream__head">Satu workspace, setiap alat di tempatnya.</h2>

                <div class="nx-modules">
                    <div class="nx-modules__list" data-modules-list role="tablist" aria-label="Daftar modul">
                        @foreach ($modules as $i => $module)
                            <button type="button" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                                class="nx-modules__item @if ($i === 0) is-active @endif"
                                data-module-tab="{{ $i }}">{{ $module['name'] }}</button>
                        @endforeach
                    </div>

                    <div class="nx-modules__frame" aria-hidden="true">
                        @foreach ($modules as $i => $module)
                            <figure class="nx-modules__slide @if ($i === 0) is-active @endif" data-module-slide="{{ $i }}">
                                @if ($module['mock'] === 'nexus-ai')
                                    <div class="nx-mock nx-mock--ai">
                                        <div class="nx-mock__top">
                                            <span class="nx-mock__dot is-green"></span>
                                            <span>NEXUS AI Assistant</span>
                                            <span class="nx-mock__pill">Online</span>
                                        </div>
                                        <div class="nx-mock__chatbox">
                                            <div class="nx-mock__msg nx-mock__msg--user">
                                                <span class="nx-mock__bubble">Analisis tren penjualan Kasira dan rekomendasikan stok minggu ini.</span>
                                            </div>
                                            <div class="nx-mock__msg nx-mock__msg--bot">
                                                <div class="nx-mock__bot-avatar">AI</div>
                                                <div class="nx-mock__bubble">
                                                    <p>Total transaksi naik 24%. Stok Kopi & Roti menipis, disarankan restock sebelum akhir pekan.</p>
                                                    <div class="nx-mock__tags">
                                                        <span>+24% Transaksi</span>
                                                        <span>Restock Kopi</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nx-mock__inputbar">
                                            <span>Ketik pertanyaan atau ide kerja...</span>
                                            <span class="nx-mock__sendbtn">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                            </span>
                                        </div>
                                    </div>
                                @elseif ($module['mock'] === 'music')
                                    <div class="nx-mock nx-mock--music">
                                        <div class="nx-mock__top"><span class="nx-mock__dot"></span><span>Pemutar Musik</span><span class="nx-mock__pill">Aktif</span></div>
                                        <div class="nx-mock__player">
                                            <div class="nx-mock__art">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13" /><circle cx="6" cy="18" r="3" /><circle cx="18" cy="16" r="3" /></svg>
                                            </div>
                                            <div class="nx-mock__song"><b>Senja di Workspace</b><span>NEXUS Radio · 3:24</span></div>
                                            <span class="nx-mock__play" aria-hidden="true"></span>
                                        </div>
                                        <div class="nx-mock__track nx-mock__track--song"><span style="width:58%"></span></div>
                                        <div class="nx-mock__controls"><span></span><span class="big"></span><span></span></div>
                                        <div class="nx-mock__cline"><span>Pagi Produktif</span><span>2:51</span></div>
                                        <div class="nx-mock__cline"><span>Lembur Santai</span><span>4:02</span></div>
                                        <div class="nx-mock__cline"><span>Fokus 25 Menit</span><span>25:00</span></div>
                                    </div>
                                @elseif ($module['mock'] === 'downloader')
                                    <div class="nx-mock nx-mock--dl">
                                        <div class="nx-mock__top"><span class="nx-mock__dot"></span><span>Antrean Unduhan</span><span class="nx-mock__pill">3</span></div>
                                        <div class="nx-mock__row">
                                            <span class="nx-mock__plat">YT</span>
                                            <span class="nx-mock__meta"><span class="nx-mock__bar" style="width:72%"></span><span class="nx-mock__track"><span style="width:100%"></span></span></span>
                                            <span class="nx-mock__st is-done">Selesai</span>
                                        </div>
                                        <div class="nx-mock__row">
                                            <span class="nx-mock__plat">TT</span>
                                            <span class="nx-mock__meta"><span class="nx-mock__bar" style="width:58%"></span><span class="nx-mock__track"><span style="width:62%"></span></span></span>
                                            <span class="nx-mock__st">62%</span>
                                        </div>
                                        <div class="nx-mock__row">
                                            <span class="nx-mock__plat">IG</span>
                                            <span class="nx-mock__meta"><span class="nx-mock__bar" style="width:64%"></span><span class="nx-mock__track"><span style="width:28%"></span></span></span>
                                            <span class="nx-mock__st">28%</span>
                                        </div>
                                        <div class="nx-mock__foot"><span>2 selesai · 1 berjalan</span><span class="nx-mock__btn">+ Tambah</span></div>
                                    </div>
                                @elseif ($module['mock'] === 'kasira')
                                    <div class="nx-mock nx-mock--pos">
                                        <div class="nx-mock__top" style="width:100%"><span class="nx-mock__dot"></span><span>Point of Sale UMKM</span><span class="nx-mock__pill">Live</span></div>
                                        <div class="nx-mock__posgrid">
                                            <span class="nx-mock__tile"><b>Kopi</b><i>8rb</i></span>
                                            <span class="nx-mock__tile"><b>Teh</b><i>5rb</i></span>
                                            <span class="nx-mock__tile"><b>Roti</b><i>12rb</i></span>
                                            <span class="nx-mock__tile"><b>Mie</b><i>10rb</i></span>
                                            <span class="nx-mock__tile"><b>Susu</b><i>7rb</i></span>
                                            <span class="nx-mock__tile"><b>Air</b><i>4rb</i></span>
                                        </div>
                                        <div class="nx-mock__cart">
                                            <div class="nx-mock__cline"><span>Kopi ×2</span><span>16rb</span></div>
                                            <div class="nx-mock__cline"><span>Roti ×1</span><span>12rb</span></div>
                                            <div class="nx-mock__cline dim"><span>Subtotal</span><span>28rb</span></div>
                                            <div class="nx-mock__cline total"><span>Total</span><span>28rb</span></div>
                                            <span class="nx-mock__pay">Bayar</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="nx-mock nx-mock--tools">
                                        <div class="nx-mock__top"><span class="nx-mock__dot"></span><span>Produktivitas & Tools</span><span class="nx-mock__pill">Terpadu</span></div>
                                        <div class="nx-mock__tools-grid">
                                            <div class="nx-mock__qr-card">
                                                <svg class="nx-mock__qrimg-sm" viewBox="0 0 100 100" aria-hidden="true">
                                                    <rect width="100" height="100" rx="8" fill="#FFFFFF" />
                                                    <g fill="#14142B">
                                                        <rect x="10" y="10" width="26" height="26" rx="4" /><rect x="16" y="16" width="14" height="14" fill="#FFFFFF" /><rect x="20" y="20" width="6" height="6" />
                                                        <rect x="64" y="10" width="26" height="26" rx="4" /><rect x="70" y="16" width="14" height="14" fill="#FFFFFF" /><rect x="74" y="20" width="6" height="6" />
                                                        <rect x="10" y="64" width="26" height="26" rx="4" /><rect x="16" y="70" width="14" height="14" fill="#FFFFFF" /><rect x="20" y="74" width="6" height="6" />
                                                        <rect x="44" y="12" width="8" height="8" /><rect x="54" y="22" width="6" height="6" /><rect x="44" y="32" width="6" height="6" />
                                                        <rect x="44" y="44" width="8" height="8" /><rect x="56" y="44" width="6" height="14" />
                                                        <rect x="68" y="46" width="8" height="8" /><rect x="44" y="60" width="6" height="6" /><rect x="56" y="62" width="10" height="10" />
                                                    </g>
                                                </svg>
                                                <div class="nx-mock__qr-meta">
                                                    <b>nexus.app/m/ksr-01</b>
                                                    <span>QR Generator Cepat</span>
                                                </div>
                                            </div>
                                            <div class="nx-mock__note-card">
                                                <div class="nx-mock__note-title">Catatan Cepat</div>
                                                <div class="nx-mock__note-line">1. Finalisasi katalog Kasira</div>
                                                <div class="nx-mock__note-line">2. Sinkron playlist fokus</div>
                                            </div>
                                        </div>
                                        <div class="nx-mock__foot"><span>3 tools siap pakai</span><span class="nx-mock__btn">+ Tambah</span></div>
                                    </div>
                                @endif
                            </figure>
                        @endforeach
                    </div>

                    <div class="nx-modules__detail">
                        @foreach ($modules as $i => $module)
                            <div class="nx-modules__pane @if ($i === 0) is-active @endif" data-module-detail="{{ $i }}" @if ($i) hidden @endif>
                                <div class="nx-modules__index">/{{ $module['index'] }}</div>
                                <h3 class="nx-modules__title">{{ $module['title'] }}</h3>
                                <p class="nx-modules__desc">{{ $module['desc'] }}</p>
                                <ul class="nx-modules__features">
                                    @foreach ($module['features'] as [$label, $live])
                                        <li class="nx-modules__feature">
                                            <span>{{ $label }}</span>
                                            <span class="nx-badge-state {{ $live ? 'nx-badge-state--live' : 'nx-badge-state--soon' }}">
                                                {{ $live ? 'Tersedia' : 'Segera' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================
             SECTION 5 &mdash; PUTIH
             Per-word colour scrub, then a pinned horizontal project carousel.
             ============================================================ --}}
        <section class="nx-white" id="proyek" data-nav="light">
            <div class="nx-statement nx-gutter">
                <p class="nx-statement__text" data-statement>
                    NEXUS AI OFFICE adalah ekosistem workspace multifungsi yang terus berkembang.
                    Satu tempat untuk berbagai kebutuhan digital, produktivitas, tools, dan operasional.
                </p>
            </div>

            <div class="nx-projects" data-projects>
                <div class="nx-projects__stage">
                    <div class="nx-projects__head nx-gutter">
                        <h2 class="nx-display nx-projects__title">Tiga produk, satu ekosistem.</h2>
                    </div>

                    <div class="nx-projects__viewport">
                        <div class="nx-projects__track" data-projects-track>
                            @foreach ($projects as $project)
                                 <article class="nx-project" data-project>
                                     <div class="nx-project__media" aria-hidden="true">
                                         @if ($project['mock'] === 'workspace')
                                             <div class="nx-pmock nx-pmock--ws">
                                                 <div class="nx-pmock__side">
                                                     <span class="nx-pmock__logo"></span>
                                                     <span class="nx-pmock__nav on"></span>
                                                     <span class="nx-pmock__nav"></span>
                                                     <span class="nx-pmock__nav"></span>
                                                     <span class="nx-pmock__nav"></span>
                                                     <span class="nx-pmock__nav"></span>
                                                 </div>
                                                 <div class="nx-pmock__main">
                                                     <div class="nx-pmock__topbar"><span class="nx-pmock__bar" style="width:34%"></span><span class="nx-pmock__avatar"></span></div>
                                                     <div class="nx-pmock__stats">
                                                         <span class="nx-pmock__stat"><i></i><b></b></span>
                                                         <span class="nx-pmock__stat"><i></i><b></b></span>
                                                         <span class="nx-pmock__stat"><i></i><b></b></span>
                                                     </div>
                                                     <div class="nx-pmock__chart">
                                                         <span style="height:38%"></span><span style="height:62%"></span><span style="height:48%"></span><span style="height:80%"></span><span style="height:56%"></span><span style="height:92%"></span><span style="height:70%"></span>
                                                     </div>
                                                 </div>
                                             </div>
                                         @elseif ($project['mock'] === 'pos')
                                             <div class="nx-pmock nx-pmock--pos">
                                                 <div class="nx-pmock__items">
                                                     <span class="nx-pmock__tile"><b>Kopi</b><i>8rb</i><em class="ok"></em></span>
                                                     <span class="nx-pmock__tile"><b>Teh</b><i>5rb</i><em class="ok"></em></span>
                                                     <span class="nx-pmock__tile"><b>Roti</b><i>12rb</i><em class="ok"></em></span>
                                                     <span class="nx-pmock__tile"><b>Mie</b><i>10rb</i><em class="low"></em></span>
                                                 </div>
                                                 <div class="nx-pmock__bill">
                                                     <div class="nx-pmock__brow"><span>Kopi ×2</span><span>16rb</span></div>
                                                     <div class="nx-pmock__brow"><span>Roti ×1</span><span>12rb</span></div>
                                                     <div class="nx-pmock__brow total"><span>Total</span><span>28rb</span></div>
                                                     <span class="nx-pmock__paybtn">Bayar</span>
                                                 </div>
                                             </div>
                                         @else
                                             <div class="nx-pmock nx-pmock--web">
                                                 <div class="nx-pmock__browser"><span></span><span></span><span></span><i>auraglobal.id</i></div>
                                                 <div class="nx-pmock__webnav"><span class="nx-pmock__weblogo"></span><span class="nx-pmock__weblinks"><i></i><i></i><i></i></span><span class="nx-pmock__webcta"></span></div>
                                                 <div class="nx-pmock__hero">
                                                     <span class="nx-pmock__hline accent"></span>
                                                     <span class="nx-pmock__hline"></span>
                                                     <span class="nx-pmock__hline short"></span>
                                                     <span class="nx-pmock__webbtn"></span>
                                                 </div>
                                                 <div class="nx-pmock__serv">
                                                     <span></span><span></span><span></span>
                                                 </div>
                                             </div>
                                         @endif
                                     </div>
                                     <span class="nx-project__num">{{ $project['num'] }}</span>
                                     <div class="nx-project__cat">{{ $project['cat'] }}</div>
                                     <h3 class="nx-project__name">{{ $project['name'] }}</h3>
                                     <p class="nx-project__desc">{{ $project['desc'] }}</p>
                                     @if (!empty($project['url']))
                                         <a href="{{ $project['url'] }}" class="nx-project__link"
                                             @if (!empty($project['ext'])) target="_blank" rel="noopener" @endif>
                                             {{ $project['cta'] }}
                                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                 <path d="M5 12h14M13 6l6 6-6 6" />
                                             </svg>
                                         </a>
                                     @else
                                         <span class="nx-project__soon">Segera hadir</span>
                                     @endif
                                 </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================
             SECTION 6 &mdash; PRINSIP (7.8: kartu oranye sticky + carousel horizontal)
             ============================================================ --}}
        <section class="nx-principles" id="prinsip" data-nav="light">
            <div class="nx-gutter">
                <div class="nx-principles__head">
                    <h2 class="nx-principles__title">Ruang kerja digital yang dirancang untuk bergerak bersama kebutuhanmu.</h2>
                    <p class="nx-principles__desc">NEXUS AI OFFICE menyatukan berbagai kebutuhan digital dalam satu ekosistem workspace &mdash; dari AI, produktivitas, musik, downloader, hingga tools operasional seperti Kasira.</p>
                </div>
            </div>

            <div class="nx-principles__carousel-container" id="nxPrinciplesContainer">
                {{-- Kartu 1: Sticky oranye di kiri kontainer (desktop) --}}
                <div class="nx-principles__sticky-card" id="nxPrinciplesSticky">
                    <div class="nx-psticky__brand">NEXUS</div>
                    <div class="nx-psticky__content">
                        <h3 class="nx-psticky__title">Ekosistem Workspace Digital</h3>
                        <p class="nx-psticky__sub">Dibuat oleh Team NEXUS</p>
                    </div>
                    <div class="nx-psticky__badge">PRINSIP KERJA</div>
                </div>

                {{-- Gradien fade di tepi kiri area geser --}}
                <div class="nx-principles__fade-left" aria-hidden="true"></div>

                {{-- Area geser horizontal berisi kartu krem --}}
                <div class="nx-principles__scroll-wrap" id="nxPrinciplesScrollWrap">
                    <div class="nx-principles__track" id="nxPrinciplesTrack">
                        @foreach ($principles as [$num, $title, $desc])
                            <div class="nx-pcard">
                                <span class="nx-pcard__quote" aria-hidden="true">&ldquo;</span>
                                <div class="nx-pcard__num">{{ $num }}</div>
                                <h4 class="nx-pcard__title">{{ $title }}</h4>
                                <p class="nx-pcard__desc">{{ $desc }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tombol panah bulat oranye (≈40px) di kanan-bawah --}}
                <div class="nx-principles__nav">
                    <button type="button" class="nx-pnav__btn" id="nxPrinciplesPrev" aria-label="Prinsip sebelumnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button type="button" class="nx-pnav__btn" id="nxPrinciplesNext" aria-label="Prinsip berikutnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </section>

        {{-- ============================================================
             CTA SEBELUM FOOTER
             ============================================================ --}}
        <section class="nx-cta" data-nav="dark">
            <div class="nx-gutter nx-cta__inner">
                <h2 class="nx-cta__title" data-cta-reveal>Siap merapikan pekerjaan digitalmu?</h2>
                <p class="nx-cta__text" data-cta-reveal>Satu workspace untuk mengelola berbagai kebutuhan digital dalam satu tempat.</p>
                <a href="{{ $ctaUrl }}" class="nx-cta__btn" data-cta-reveal>
                    Buka Workspace
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
        </section>

        {{-- ============================================================
             SECTION 7 &mdash; FOOTER
             ============================================================ --}}
        <footer class="nx-footer" data-nav="dark">
            {{-- Lengkung glossy abstrak SVG di kiri-bawah --}}
            <div class="nx-footer__abstract-curve" aria-hidden="true">
                <svg viewBox="0 0 420 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M-40 210 C 80 200, 140 60, 320 30 C 370 20, 410 45, 430 80" stroke="url(#nxFtrG1)" stroke-width="3" stroke-linecap="round" />
                    <path d="M-20 220 C 100 210, 170 90, 340 60" stroke="url(#nxFtrG2)" stroke-width="1.5" opacity="0.6" />
                    <defs>
                        <linearGradient id="nxFtrG1" x1="0" y1="210" x2="350" y2="30" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#F0490A" stop-opacity="0.85" />
                            <stop offset="60%" stop-color="#F5A623" stop-opacity="0.6" />
                            <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0.1" />
                        </linearGradient>
                        <linearGradient id="nxFtrG2" x1="0" y1="220" x2="340" y2="60" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#E8430F" stop-opacity="0.6" />
                            <stop offset="100%" stop-color="#A855F7" stop-opacity="0.2" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="nx-footer__grid nx-gutter">
                 <div class="nx-footer__col">
                     <div class="nx-footer__brand">
                         <span class="nx-footer__brandname">NEXUS AI OFFICE</span>
                     </div>
                     <p class="nx-footer__blurb">
                         Digital workspace ecosystem untuk kebutuhan kerja, produktivitas, dan tools digital.
                     </p>
                 </div>

                 <nav class="nx-footer__col" aria-label="Navigasi footer">
                     <div class="nx-footer__title">Navigasi</div>
                     <ul class="nx-footer__list">
                         <li><a href="#beranda">Beranda</a></li>
                         <li><a href="#proyek">Proyek</a></li>
                         <li><a href="#prinsip">Prinsip</a></li>
                         <li><a href="{{ $ctaUrl }}">Workspace</a></li>
                     </ul>
                 </nav>

                 <div class="nx-footer__col">
                     <div class="nx-footer__title">Produk</div>
                      <ul class="nx-footer__list">
                          <li><a href="#fitur">Downloader</a></li>
                          <li><a href="{{ route('kasira.dashboard') }}">Kasira</a></li>
                          <li><a href="#fitur">QR Tools</a></li>
                          <li><a href="{{ route('lifehub.music') }}">Music</a></li>
                      </ul>
                 </div>

                 <div class="nx-footer__col">
                     <div class="nx-footer__title">Ekosistem</div>
                     <ul class="nx-footer__list">
                         <li><a href="{{ route('lifehub.dashboard') }}">NEXUS AI OFFICE</a></li>
                         <li><a href="{{ route('kasira.dashboard') }}">Kasira</a></li>
                         @if (!empty($auraUrl))
                             <li><a href="{{ $auraUrl }}" target="_blank" rel="noopener">AURA GLOBAL</a></li>
                         @else
                             <li><span class="is-soon">AURA GLOBAL</span></li>
                         @endif
                     </ul>
                 </div>
            </div>

            <div class="nx-footer__giantwrap" aria-hidden="true">
                <span class="nx-footer__giant">NEXUS AI OFFICE</span>
            </div>

             <div class="nx-footer__bottom nx-gutter">
                 <span>NEXUS AI OFFICE</span>
                 <span>Dibuat oleh Team NEXUS</span>
                 <a href="#beranda" class="nx-totop" aria-label="Kembali ke atas">
                     Kembali ke atas
                     <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                         <path d="M12 19V5M5 12l7-7 7 7" />
                     </svg>
                 </a>
             </div>
        </footer>

    </div>

    <div class="nx-cursor" id="nxCursor" aria-hidden="true">LIHAT</div>
</body>

</html>


