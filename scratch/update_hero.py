import re

# 1. Update landing.blade.php
with open(r'resources/views/landing.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

blade = blade.replace(
    "$rotateWords = ['PRODUKTIF', 'TERPADU', 'CEPAT', 'RINGAN'];",
    "$rotateWords = ['DIGITAL', 'PINTAR', 'OTOMATIS', 'PRODUKTIF'];"
)

new_hero = """{{-- ============================================================
             SECTION 1 — HERO
             ============================================================ --}}
        <section class="nx-hero" id="nxHero" data-theme="dark" data-nav="dark">
            <div class="nx-hero__shade" aria-hidden="true"></div>
            <div class="nx-hero__grain" aria-hidden="true"></div>

            {{-- Sosok Robot Hero: State 1 (wajah terbuka) & State 2 (masker SVG) --}}
            <div class="nx-hero__visual" data-hero-visual>
                <div class="nx-hero__figure" id="nxRobotFigure">
                    <picture>
                        <source srcset="{{ asset('images/landing/hero-robot.webp') }}" type="image/webp">
                        <img class="nx-hero__media nx-hero__media--base" src="{{ asset('images/landing/hero-robot.png') }}"
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

                    <h1 class="nx-hero__headline">
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>MEMBANGUN</span></span>
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>WORKSPACE <span class="nx-swap" data-word-scramble
                                    data-words='@json($rotateWords)'>DIGITAL</span></span></span>
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>TERPADU</span></span>
                    </h1>
                </div>
            </div>

            <div class="nx-glass" data-hero-fade>
                <div class="nx-glass__head">
                    <span class="nx-glass__brand">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
                        <span>Tentang NEXUS</span>
                    </span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                </div>
                <div class="nx-glass__body">
                    <div class="nx-glass__features">
                        <p class="nx-glass__feat">Perusahaan</p>
                        <p class="nx-glass__feat">Teknologi yang 90%</p>
                        <p class="nx-glass__feat">Berfokus pada</p>
                        <p class="nx-glass__feat">Pengguna</p>
                    </div>
                    <div class="nx-glass__orb-wrap">
                        <svg class="nx-glass__orb" viewBox="0 0 100 100" fill="none" aria-hidden="true">
                            <defs>
                                <radialGradient id="nxOrbGrad" cx="35%" cy="35%" r="65%">
                                    <stop offset="0%" stop-color="#FFFFFF"/>
                                    <stop offset="40%" stop-color="#FFA84A"/>
                                    <stop offset="100%" stop-color="#C93800"/>
                                </radialGradient>
                                <filter id="orbGlow" x="-20%" y="-20%" width="140%" height="140%">
                                    <feGaussianBlur stdDeviation="6" result="blur"/>
                                    <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
                                </filter>
                            </defs>
                            <circle cx="50" cy="50" r="38" fill="url(#nxOrbGrad)" filter="url(#orbGlow)"/>
                            <ellipse cx="40" cy="36" rx="14" ry="8" fill="#FFFFFF" fill-opacity="0.6"/>
                        </svg>
                    </div>
                </div>
                <p class="nx-glass__desc">Kami percaya bahwa platform kerja yang hebat bukan hanya tentang fitur &mdash; tetapi juga tentang alur kerja nyata dan efisiensi waktu.</p>
            </div>

            <p class="nx-hero__foot" data-hero-fade>
                KAMI MENGUBAH IDE MENJADI WORKSPACE DIGITAL YANG KUAT. PENGALAMAN YANG MEMBANTU ANDA BEKERJA LEBIH CEPAT, TERHUBUNG, DAN BERTUMBUH DENGAN EFISIENSI TINGGI.<br><br>
                {{ $isMember ? 'MASUK KE WORKSPACE ANDA UNTUK MELANJUTKAN' : 'MULAI DARI SATU WORKSPACE. AKSES SEMUA TOOLS TANPA PERLU BERALIH APLIKASI.' }}
            </p>
        </section>"""

blade = re.sub(
    r'\{\{-- =+\s*SECTION 1 &mdash; HERO.*?\{\{-- =+\s*SECTION 2',
    new_hero + '\n\n        {{-- ============================================================\n             SECTION 2',
    blade,
    flags=re.DOTALL
)

with open(r'resources/views/landing.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)
print('Updated landing.blade.php')
