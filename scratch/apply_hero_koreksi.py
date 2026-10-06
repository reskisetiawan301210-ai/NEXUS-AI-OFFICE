import re

# =========================================================================
# 1. UPDATE resources/views/landing.blade.php
# =========================================================================
with open(r'resources/views/landing.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

# Update rotateWords array: PRODUKTIF, TERPADU, CEPAT, RINGAN
blade = re.sub(
    r'\$rotateWords\s*=\s*\[.*?\];',
    "$rotateWords = ['PRODUKTIF', 'TERPADU', 'CEPAT', 'RINGAN'];",
    blade,
    count=1
)

# New Hero HTML
new_hero_html = """{{-- ============================================================
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
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>MEMBANGUN RUANG</span></span>
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>KERJA <span class="nx-swap" data-word-scramble
                                    data-words='@json($rotateWords)'>PRODUKTIF</span></span></span>
                        <span class="nx-line-mask"><span class="nx-line-inner" data-hero-line>DALAM SATU TEMPAT</span></span>
                    </h1>
                </div>
            </div>

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
                MULAI DARI SATU WORKSPACE. AKSES SEMUA TOOLS TANPA PERLU BERALIH APLIKASI.
            </p>
        </section>"""

blade = re.sub(
    r'\{\{-- =+\s*SECTION 1 &mdash; HERO.*?\{\{-- =+\s*SECTION 2',
    new_hero_html + '\n\n        {{-- ============================================================\n             SECTION 2',
    blade,
    flags=re.DOTALL
)

with open(r'resources/views/landing.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)
print('Updated landing.blade.php')

# =========================================================================
# 2. UPDATE resources/css/landing.css
# =========================================================================
with open(r'resources/css/landing.css', 'r', encoding='utf-8') as f:
    css = f.read()

# Update root variables if needed
if '--robot-h:' not in css:
    css = css.replace(
        ':root {',
        ':root {\n    --robot-h: 112vh;\n    --eyes-y: 30vh;\n    --face-x: 65vw;\n    --nx-amber: #F5A623;'
    )
else:
    css = re.sub(r'--robot-h:.*?;', '--robot-h: 112vh;', css)
    css = re.sub(r'--eyes-y:.*?;', '--eyes-y: 30vh;', css)
    css = re.sub(r'--face-x:.*?;', '--face-x: 65vw;', css)
    css = re.sub(r'--nx-amber:.*?;', '--nx-amber: #F5A623;', css)

new_hero_css = """/* ============================================================
   SECTION 1 — HERO
   Memenuhi semua syarat ukur:
   1. --robot-h: 112vh; --eyes-y: 30vh; --face-x: 65vw;
   2. Bbox wajah (x 42%-58%, y 18%-56%) tidak berpotongan dengan kartu kaca, headline, atau tagline.
   3. Headline 3 baris: MEMBANGUN RUANG / KERJA [rotor] / DALAM SATU TEMPAT
      Ujung kanan baris terpanjang <= 66vw. Rotor amber #F5A623 min-width 9ch.
   4. Kartu kaca: kanan-bawah, tidak menutupi wajah robot maupun rotor.
   5. Tagline: kiri-bawah.
   6. Latar: linear-gradient(135deg, #8E0E00 0%, #E8430F 50%, #FF8A1F 100%).
      Vignette tipis kiri-atas (opacity <= 0.25).
   ============================================================ */
.nx-hero {
    position: relative;
    z-index: 1;
    height: 100vh;
    height: 100svh;
    min-height: 560px;
    overflow: hidden;
    isolation: isolate;
    background: linear-gradient(135deg, #8E0E00 0%, #E8430F 50%, #FF8A1F 100%) !important;
    background-color: #E8430F !important;
}

/* Vignette sangat tipis kiri-atas (opacity <= 0.25) agar navbar terbaca */
.nx-hero__shade {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.22) 0%, rgba(0, 0, 0, 0) 32%);
}

/* Noise texture fractal */
.nx-hero__grain {
    position: absolute;
    inset: 0;
    z-index: 6;
    pointer-events: none;
    opacity: 0.035;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)'/%3E%3C/svg%3E");
}

/* -------------------------------------------------------------
   HERO ROBOT (Sosok Utama)
   Berbasis variabel --robot-h, --eyes-y, --face-x
   ------------------------------------------------------------- */
.nx-hero__visual {
    position: absolute;
    height: var(--robot-h);
    width: calc(var(--robot-h) * 1.5);
    left: calc(var(--face-x) - (var(--robot-h) * 1.5 * 0.5));
    top: calc(var(--eyes-y) - (var(--robot-h) * 0.285));
    z-index: 2;
    pointer-events: auto;
    cursor: pointer;
    will-change: transform;
}

.nx-hero__figure {
    position: relative;
    width: 100%;
    height: 100%;
}

.nx-hero__media,
.nx-hero__mask {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: bottom center;
    pointer-events: none;
}

.nx-hero__media--base {
    z-index: 2;
}

/* Masker dengan sapuan dari dagu (chin sweep) */
.nx-hero__mask {
    z-index: 3;
    opacity: 0;
    clip-path: inset(100% 0 0 0);
    transform: translateY(8px);
    transition: clip-path 0.65s cubic-bezier(0.16, 1, 0.3, 1),
                opacity 0.45s ease,
                transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
}

.nx-hero__visual.is-masked .nx-hero__mask {
    opacity: 1;
    clip-path: inset(0 0 0 0);
    transform: translateY(0);
}

/* -------------------------------------------------------------
   HERO CONTENT (Headline, Badge, Copy)
   ------------------------------------------------------------- */
.nx-hero__content {
    position: relative;
    z-index: 4;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
    padding-top: var(--nx-nav-h);
    padding-left: clamp(2rem, 4vw, 4rem);
    padding-right: 2rem;
    pointer-events: none;
}

.nx-hero__copy {
    position: relative;
    z-index: 4;
    width: 100%;
    max-width: 65vw;
    pointer-events: auto;
}

.nx-badge {
    align-self: flex-start;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    height: 32px;
    padding-inline: 0.95rem;
    margin-bottom: clamp(14px, 2.5vh, 22px);
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.22);
    background: rgba(255, 255, 255, 0.12);
    -webkit-backdrop-filter: blur(14px);
    backdrop-filter: blur(14px);
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    white-space: nowrap;
}

.nx-badge::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 999px;
    background: #FFFFFF;
    flex: none;
}

/* Headline Orbitron 900 3 baris:
   Ujung kanan baris terpanjang <= 66vw */
.nx-hero__headline {
    position: relative;
    z-index: 4;
    max-width: 62vw;
    margin: 0;
    font-family: var(--font-hero);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    line-height: 1.14;
    color: #FFFFFF;
    font-size: clamp(1.9rem, 3.2vw, 3.8rem);
    text-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
}

/* Rotor kata: amber #F5A623, min-width 9ch */
.nx-swap {
    position: relative;
    z-index: 5;
    display: inline-block;
    min-width: 9ch;
    color: #F5A623;
    font: inherit;
    font-weight: 900;
    white-space: nowrap;
    text-shadow: 0 0 24px rgba(245, 166, 35, 0.45);
}

.nx-line-mask {
    overflow: hidden;
    display: block;
    padding-bottom: 0.06em;
    margin-bottom: -0.06em;
    white-space: nowrap;
}

.nx-line-inner {
    display: block;
    will-change: transform;
}

/* -------------------------------------------------------------
   KARTU KACA KANAN-BAWAH
   Bbox kartu tidak berpotongan dengan wajah robot
   ------------------------------------------------------------- */
.nx-glass {
    position: absolute;
    right: clamp(1.5rem, 2.5vw, 3rem);
    bottom: clamp(1.5rem, 4.5vh, 3.5rem);
    width: clamp(260px, 20vw, 310px);
    z-index: 5;
    padding: 1.25rem 1.35rem;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    background: rgba(45, 14, 5, 0.55);
    -webkit-backdrop-filter: blur(28px);
    backdrop-filter: blur(28px);
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.45), inset 0 1px 1px rgba(255, 255, 255, 0.2);
    color: #FFFFFF;
}

.nx-glass__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.nx-glass__brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.02em;
    color: rgba(255, 255, 255, 0.95);
}

.nx-glass__content {
    padding-top: 0.25rem;
}

.nx-glass__title {
    margin: 0.75rem 0 0.5rem;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.45;
    color: #FFFFFF;
}

.nx-glass__desc {
    margin: 0;
    font-family: var(--font-body);
    font-size: 11.5px;
    line-height: 1.55;
    color: rgba(255, 255, 255, 0.78);
}

/* -------------------------------------------------------------
   TAGLINE KIRI-BAWAH (Hero Foot)
   ------------------------------------------------------------- */
.nx-hero__foot {
    position: absolute;
    left: clamp(2rem, 4vw, 4rem);
    bottom: clamp(1.5rem, 4.5vh, 3.5rem);
    z-index: 5;
    max-width: 38ch;
    margin: 0;
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.6;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.75);
}

/* -------------------------------------------------------------
   RESPONSIVE (Tablet & Mobile)
   ------------------------------------------------------------- */
@media (max-width: 1024px) {
    :root {
        --robot-h: 90vh;
        --eyes-y: 35vh;
        --face-x: 62vw;
    }

    .nx-hero__headline {
        max-width: 58vw;
        font-size: clamp(1.8rem, 3.6vw, 3.2rem);
    }

    .nx-glass {
        width: 280px;
        right: 1.5rem;
        bottom: 2rem;
    }
}

@media (max-width: 768px) {
    :root {
        --robot-h: 70vh;
        --eyes-y: 28vh;
        --face-x: 50vw;
    }

    .nx-hero {
        height: auto;
        min-height: 100svh;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding-bottom: 2.5rem;
    }

    .nx-hero__visual {
        position: absolute;
        height: 65svh;
        width: calc(65svh * 1.5);
        left: calc(50vw - (65svh * 1.5 * 0.5));
        top: 15vh;
        opacity: 0.45;
        z-index: 2;
    }

    .nx-hero__content {
        padding-inline: 1.25rem;
        padding-top: calc(var(--nx-nav-h) + 1.5rem);
        justify-content: flex-end;
    }

    .nx-hero__copy {
        max-width: 100%;
    }

    .nx-hero__headline {
        max-width: 100%;
        font-size: clamp(1.65rem, 7.5vw, 2.5rem);
        line-height: 1.15;
    }

    .nx-hero__headline .nx-line-mask {
        white-space: normal;
    }

    .nx-glass {
        position: relative;
        right: auto;
        bottom: auto;
        width: 100%;
        margin-top: 1.5rem;
    }

    .nx-hero__foot {
        position: relative;
        left: auto;
        bottom: auto;
        margin-top: 1.25rem;
        max-width: 100%;
    }
}
"""

m1 = re.search(r'/\* =+\s*SECTION 1\b.*?\*/', css, re.DOTALL)
m2 = re.search(r'/\* =+\s*SECTION 2\b', css)

if m1 and m2:
    css = css[:m1.start()] + new_hero_css + css[m2.start():]
    with open(r'resources/css/landing.css', 'w', encoding='utf-8') as f:
        f.write(css)
    print('Updated landing.css Section 1')
else:
    print('Failed to find Section 1 markers in landing.css')

# =========================================================================
# 3. UPDATE resources/js/landing.js
# =========================================================================
with open(r'resources/js/landing.js', 'r', encoding='utf-8') as f:
    js = f.read()

# Update initHero with chin sweep, hover/tap, auto-cycle stops on interaction
hero_js_pattern = r'function initHero\(\)\s*\{.*?// TIDAK ada pin.*?\n\}|function initHero\(\)\s*\{.*?\n\}'

new_hero_js = """function initHero() {
    const hero = q('#nxHero');
    if (!hero) return;

    const lines = qa('[data-hero-line]', hero);
    const fades = qa('[data-hero-fade]', hero);
    const visual = q('#nxHeroVisual') || q('[data-hero-visual]', hero);
    const fine = window.matchMedia('(pointer: fine)').matches;

    // Interaksi Masker sesuai spesifikasi 5.5:
    // - Hover / Tap pada robot
    // - Sapuan dari dagu (chin sweep)
    // - Siklus otomatis berhenti permanen setelah ada interaksi pengguna
    if (visual) {
        let isMasked = false;
        let autoCycleTimer = null;
        let userHasInteracted = false;

        const setMaskState = (masked) => {
            isMasked = masked;
            visual.classList.toggle('is-masked', masked);
        };

        const stopAutoCycle = () => {
            if (autoCycleTimer) {
                clearInterval(autoCycleTimer);
                autoCycleTimer = null;
            }
            userHasInteracted = true;
        };

        // Siklus otomatis awal jika belum ada interaksi pengguna
        if (!prefersReducedMotion()) {
            autoCycleTimer = setInterval(() => {
                if (!userHasInteracted) {
                    setMaskState(!isMasked);
                }
            }, 4000);
        }

        // Hover desktop (pointer: fine)
        if (fine) {
            visual.addEventListener('mouseenter', () => {
                stopAutoCycle();
                setMaskState(true);
            });
            visual.addEventListener('mouseleave', () => {
                setMaskState(false);
            });
        }

        // Click / Tap toggle
        visual.addEventListener('click', () => {
            stopAutoCycle();
            setMaskState(!isMasked);
        });

        visual.addEventListener('touchstart', () => {
            stopAutoCycle();
            setMaskState(!isMasked);
        }, { passive: true });
    }

    if (prefersReducedMotion()) {
        gsap.set([lines, fades], { clearProps: 'all' });
        return;
    }

    gsap.set(lines, { yPercent: 110 });
    gsap.set(fades, { y: 20, opacity: 0 });
    if (visual) gsap.set(visual, { opacity: 0 });

    const tl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: 0.15 });

    tl.to(lines, {
        yPercent: 0,
        duration: 0.9,
        stagger: 0.08,
    })
        .to(
            visual,
            { opacity: 1, duration: 1.1, ease: 'power2.out' },
            0.1,
        )
        .to(fades, { y: 0, opacity: 1, duration: 0.7, stagger: 0.08 }, 0.35);

    // Parallax kursor mikro
    if (visual && DESKTOP_QUERY.matches && fine) {
        const xTo = gsap.quickTo(visual, 'x', { duration: 0.8, ease: 'power3.out' });
        const yTo = gsap.quickTo(visual, 'y', { duration: 0.8, ease: 'power3.out' });

        hero.addEventListener('mousemove', (event) => {
            const rect = hero.getBoundingClientRect();
            const nx = (event.clientX - rect.left) / rect.width - 0.5;
            const ny = (event.clientY - rect.top) / rect.height - 0.5;
            xTo(nx * 20 > 10 ? 10 : nx * 20 < -10 ? -10 : nx * 20);
            yTo(ny * 20 > 10 ? 10 : ny * 20 < -10 ? -10 : ny * 20);
        });
    }
}"""

# Replace initHero
m_hero = re.search(r'function initHero\(\)\s*\{', js)
if m_hero:
    # Find matching brace
    start_pos = m_hero.start()
    depth = 0
    end_pos = -1
    for i in range(start_pos, len(js)):
        if js[i] == '{':
            depth += 1
        elif js[i] == '}':
            depth -= 1
            if depth == 0:
                end_pos = i + 1
                break
    if end_pos != -1:
        js = js[:start_pos] + new_hero_js + js[end_pos:]
        print('Updated initHero in landing.js')

# Update fallback words in initWordRotate
js = js.replace(
    "words = ['DIGITAL', 'PINTAR', 'OTOMATIS', 'PRODUKTIF'];",
    "words = ['PRODUKTIF', 'TERPADU', 'CEPAT', 'RINGAN'];"
)

with open(r'resources/js/landing.js', 'w', encoding='utf-8') as f:
    f.write(js)
print('Updated landing.js successfully')

