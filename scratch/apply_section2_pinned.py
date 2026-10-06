import re

# =========================================================================
# 1. UPDATE landing.blade.php (Section 2)
# =========================================================================
with open(r'resources/views/landing.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

new_scrub_blade = """{{-- ============================================================
             SECTION 2 — PINNED SCRUB (RUANG KERJA ... TERPADU)
             Pin ±700vh, scrub 0.6; dua kata sebaris terpusat di y:50vh.
             Film mini otomatis 8 scene di dalam kartu.
             ============================================================ --}}
        <section class="nx-scrub" id="nxScrub" data-scrub data-nav="dark">
            <div class="nx-scrub__stage" id="nxScrubStage">
                <div class="nx-scrub__row" id="nxScrubRow">
                    <span class="nx-scrub__word nx-scrub__word--left" id="nxScrubLeft">RUANG KERJA</span>

                    <div class="nx-scrub__card" id="nxScrubCard" data-scrub-card>
                        <div class="nx-scrub__card-grid" aria-hidden="true"></div>

                        {{-- Film Mini Otomatis (8 Scene) --}}
                        <div class="nx-film" id="nxFilm">
                            {{-- Scene 1: Riak gelombang konsentris & "Setiap kerja dimulai" --}}
                            <div class="nx-film__scene nx-film__scene--1" id="nxScene1">
                                <div class="nx-film__ripples">
                                    <div class="nx-film__ripple r-1"></div>
                                    <div class="nx-film__ripple r-2"></div>
                                    <div class="nx-film__ripple r-3"></div>
                                </div>
                                <div class="nx-film__s1-text">
                                    <span class="nx-film__s1-lead">Setiap kerja</span>
                                    <span class="nx-film__pill nx-film__s1-pill" id="nxS1Pill">dimulai</span>
                                </div>
                            </div>

                            {{-- Scene 2: Disolusi piksel, huruf "Q", wordmark "ruang kosong" --}}
                            <div class="nx-film__scene nx-film__scene--2" id="nxScene2">
                                <div class="nx-film__pixel-grid" id="nxPixelGrid">
                                    @for ($p = 0; $p < 72; $p++)
                                        <div class="nx-film__pixel"></div>
                                    @endfor
                                </div>
                                <div class="nx-film__q-type" id="nxQType">Q</div>
                                <div class="nx-film__white-card" id="nxWhiteCard">
                                    <div class="nx-film__wordmark">
                                        <span class="nx-film__wm-bold">ruang</span>
                                        <span class="nx-film__wm-light">kosong</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Scene 3: Tombol bulat putih + tempat sampah gooey + 6 tetesan --}}
                            <div class="nx-film__scene nx-film__scene--3" id="nxScene3">
                                <div class="nx-film__goo-wrap">
                                    <div class="nx-film__trash-btn" id="nxTrashBtn">
                                        <svg class="nx-film__trash-icon" viewBox="0 0 24 24" fill="none" stroke="#4B0AA5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </div>
                                    <div class="nx-film__droplets">
                                        <div class="nx-film__drop d-1"></div>
                                        <div class="nx-film__drop d-2"></div>
                                        <div class="nx-film__drop d-3"></div>
                                        <div class="nx-film__drop d-4"></div>
                                        <div class="nx-film__drop d-5"></div>
                                        <div class="nx-film__drop d-6"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Scene 4-6: 3 Pill jatuh, tombol memakan, kerucut cahaya --}}
                            <div class="nx-film__scene nx-film__scene--46" id="nxScene46">
                                <div class="nx-film__drop-pills">
                                    <div class="nx-film__pill nx-film__pill--drop" id="nxDropPill1">Banyak tab terbuka.</div>
                                    <div class="nx-film__pill nx-film__pill--drop" id="nxDropPill2">Data tercecer.</div>
                                    <div class="nx-film__pill nx-film__pill--drop" id="nxDropPill3">Harus ganti aplikasi.</div>
                                </div>
                                <div class="nx-film__trash-eater" id="nxTrashEater">
                                    <svg class="nx-film__trash-icon" viewBox="0 0 24 24" fill="none" stroke="#4B0AA5" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </div>
                                <div class="nx-film__light-cone" id="nxLightCone"></div>
                            </div>

                            {{-- Scene 7: "Lalu kejelasan masuk." + highlight + kursor --}}
                            <div class="nx-film__scene nx-film__scene--7" id="nxScene7">
                                <div class="nx-film__s7-box">
                                    <p class="nx-film__s7-text">
                                        Lalu <span class="nx-film__highlight" id="nxHighlight">kejelasan</span> masuk.
                                    </p>
                                    <div class="nx-film__cursor-wrap" id="nxCursorWrap">
                                        <svg class="nx-film__pointer" viewBox="0 0 24 24" fill="#4B0AA5">
                                            <path d="M3 3l7 18 3-7 7-3L3 3z"/>
                                        </svg>
                                        <button type="button" class="nx-film__mini-pill" id="nxMiniPill">Lanjutkan</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Scene 8: Orbit bola kaca bercahaya, 3 cincin, titik bergerak, kata berganti --}}
                            <div class="nx-film__scene nx-film__scene--8 is-active" id="nxScene8">
                                <div class="nx-film__orbit-stage">
                                    <div class="nx-film__orbit-system" id="nxOrbitSystem">
                                        <div class="nx-film__orb-sphere">
                                            <div class="nx-film__orb-glass"></div>
                                        </div>
                                        <div class="nx-film__ring r-1"></div>
                                        <div class="nx-film__ring r-2"></div>
                                        <div class="nx-film__ring r-3"></div>
                                        <div class="nx-film__star s-1">✦</div>
                                        <div class="nx-film__star s-2">✦</div>
                                        <div class="nx-film__star s-3">✦</div>
                                        <div class="nx-film__dot dt-1"></div>
                                        <div class="nx-film__dot dt-2"></div>
                                        <div class="nx-film__dot dt-3"></div>
                                        <div class="nx-film__dot dt-4"></div>
                                    </div>
                                    <div class="nx-film__orbit-words" id="nxOrbitWords">
                                        <div class="nx-film__ow-item is-active" data-ow="0">
                                            <span class="nx-film__ow-icon">⚡</span>
                                            <span class="nx-film__ow-label">AI</span>
                                        </div>
                                        <div class="nx-film__ow-item" data-ow="1">
                                            <span class="nx-film__ow-icon">🎵</span>
                                            <span class="nx-film__ow-label">Musik</span>
                                        </div>
                                        <div class="nx-film__ow-item" data-ow="2">
                                            <span class="nx-film__ow-icon">💼</span>
                                            <span class="nx-film__ow-label">Kasira</span>
                                        </div>
                                        <div class="nx-film__ow-item" data-ow="3">
                                            <span class="nx-film__ow-icon">🚀</span>
                                            <span class="nx-film__ow-label">Produktivitas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SVG Gooey Filter untuk Scene 3 --}}
                        <svg class="nx-film__svg-filter" aria-hidden="true">
                            <defs>
                                <filter id="nxGoo">
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur" />
                                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                                    <feBlend in="SourceGraphic" in2="goo" />
                                </filter>
                            </defs>
                        </svg>
                    </div>

                    <span class="nx-scrub__word nx-scrub__word--right" id="nxScrubRight">TERPADU</span>
                </div>
            </div>
        </section>"""

idx2 = blade.find('SECTION 2')
idx3 = blade.find('SECTION 3')
start2 = blade.rfind('{{--', 0, idx2)
end2 = blade.rfind('{{--', 0, idx3)

blade = blade[:start2] + new_scrub_blade + '\n\n        ' + blade[end2:]

with open(r'resources/views/landing.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)
print('Updated Section 2 in landing.blade.php')

# =========================================================================
# 2. UPDATE landing.css (Section 2)
# =========================================================================
with open(r'resources/css/landing.css', 'r', encoding='utf-8') as f:
    css = f.read()

new_scrub_css = """/* ============================================================
   SECTION 2 — PINNED SCRUB (RUANG KERJA ... TERPADU)
   Sesuai Spesifikasi:
   A. LAYOUT:
      - Baris flex terpusat di viewport (y tengah 50vh, baseline sama)
      - Kata kiri rata-kanan (RUANG KERJA), jarak 2vw dari tepi kiri kartu
      - Kata kanan rata-kiri (TERPADU #F0490A), mulai 2vw dari tepi kanan kartu
      - Outfit 900, kapital, clamp(2.4rem, 4.7vw, 6rem), line-height 1
      - Kartu awal ≈25vw x 29vh, akhir ≈68vw x 67vh, selalu di tengah
      - Saat scroll, dua kata bergeser keluar masing-masing setengah pertambahan
        lebar kartu sampai terpotong tepi layar lalu hilang
      - Kartu tidak menutupi huruf sebelum kata bergeser keluar
      - Latar: 25vw #000, 36vw #444, 45vw #5F5F5F, 57vw #BBBBBB, 68vw #F5F5F5 -> #FFF
      - Pin ±700vh, scrub 0.6
   B. ISI KARTU = Film mini otomatis (GSAP timeline, Outfit 600, pill putih radius 22px
      teks violet #4B0AA5, kartu violet #4B0FB8 bergrid rgba(255,255,255,.08)):
      Scene 1: Riak gelombang konsentris & "Setiap kerja dimulai"
      Scene 2: Disolusi piksel & wordmark "ruang kosong"
      Scene 3: Tombol bulat putih + tempat sampah gooey + 6 tetesan
      Scene 4-6: 3 pill jatuh miring, tombol memakan, kerucut cahaya
      Scene 7: "Lalu kejelasan masuk" + highlight menyapu + kursor tekan mini pill
      Scene 8: Orbit bola kaca bercahaya, 3 cincin, titik bergerak, kata berganti
   ============================================================ */
.nx-scrub {
    position: relative;
    z-index: 10;
    height: 700vh;
    background: #000000;
    will-change: background-color;
}

.nx-scrub__stage {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    height: 100svh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    pointer-events: none;
    z-index: 10;
}

.nx-scrub__row {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
    height: 100svh;
    pointer-events: auto;
}

/* Typography dua kata sebaris */
.nx-scrub__word {
    font-family: 'Outfit', sans-serif;
    font-weight: 900;
    text-transform: uppercase;
    font-size: clamp(2.4rem, 4.7vw, 6rem);
    line-height: 1;
    white-space: nowrap;
    will-change: transform, color;
    user-select: none;
    letter-spacing: -0.02em;
}

.nx-scrub__word--left {
    text-align: right;
    color: #FFFFFF;
    margin-right: 2vw;
}

.nx-scrub__word--right {
    text-align: left;
    color: #F0490A !important;
    margin-left: 2vw;
}

/* Kartu Pinned: Awal ≈25vw x 29vh, Akhir ≈68vw x 67vh */
.nx-scrub__card {
    position: relative;
    width: 25vw;
    height: 29vh;
    background-color: #4B0FB8;
    border-radius: 24px;
    overflow: hidden;
    flex: none;
    box-shadow: 0 24px 60px -12px rgba(75, 15, 184, 0.45), inset 0 1px 1px rgba(255, 255, 255, 0.25);
    z-index: 5;
    will-change: width, height, transform;
}

.nx-scrub__card-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
    z-index: 1;
}

/* -------------------------------------------------------------
   FILM MINI OTOMATIS (8 Scene di dalam kartu)
   ------------------------------------------------------------- */
.nx-film {
    position: absolute;
    inset: 0;
    z-index: 2;
    overflow: hidden;
    font-family: 'Outfit', sans-serif;
    color: #FFFFFF;
}

.nx-film__scene {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.5s ease;
}

.nx-film__scene.is-active {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

/* Pill umum: radius 22px, teks violet #4B0AA5, background putih */
.nx-film__pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.45rem 1.15rem;
    background: #FFFFFF;
    color: #4B0AA5;
    font-size: clamp(11px, 1.2vw, 15px);
    font-weight: 600;
    border-radius: 22px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    white-space: nowrap;
}

/* Scene 1: Riak gelombang konsentris */
.nx-film__ripples {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.nx-film__ripple {
    position: absolute;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.4);
    transform: scale(0);
}

.nx-film__s1-text {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: clamp(12px, 1.4vw, 16px);
    font-weight: 500;
    color: #FFFFFF;
}

/* Scene 2: Disolusi piksel & Wordmark */
.nx-film__pixel-grid {
    position: absolute;
    inset: 0;
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    grid-template-rows: repeat(6, 1fr);
    z-index: 2;
    pointer-events: none;
}

.nx-film__pixel {
    background: #4B0FB8;
    border: 0.5px solid rgba(255, 255, 255, 0.05);
}

.nx-film__q-type {
    position: absolute;
    font-size: clamp(2.5rem, 5vw, 4.5rem);
    font-weight: 900;
    color: #FFFFFF;
    opacity: 0;
    z-index: 3;
}

.nx-film__white-card {
    position: absolute;
    inset: 8%;
    background: #FFFFFF;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
    opacity: 0;
    transform: scale(0.92);
    z-index: 4;
}

.nx-film__wordmark {
    font-size: clamp(1.2rem, 2.5vw, 2.2rem);
    color: #4B0AA5;
    letter-spacing: -0.02em;
}

.nx-film__wm-bold {
    font-weight: 800;
}

.nx-film__wm-light {
    font-weight: 300;
    margin-left: 0.25rem;
}

/* Scene 3: Tombol tempat sampah gooey */
.nx-film__goo-wrap {
    filter: url(#nxGoo);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    width: 100%;
    height: 100%;
}

.nx-film__trash-btn {
    width: clamp(64px, 8vw, 96px);
    height: clamp(64px, 8vw, 96px);
    border-radius: 50%;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    z-index: 2;
    transform: translateY(60px);
}

.nx-film__trash-icon {
    width: 45%;
    height: 45%;
}

.nx-film__droplets {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.nx-film__drop {
    position: absolute;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #FFFFFF;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
}

/* Scene 4-6: Drop Pills & Eater */
.nx-film__drop-pills {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    z-index: 3;
}

.nx-film__pill--drop {
    opacity: 0;
    transform: translateY(-80px) rotate(-3deg);
}

.nx-film__trash-eater {
    position: absolute;
    bottom: 12%;
    width: clamp(56px, 7vw, 80px);
    height: clamp(56px, 7vw, 80px);
    border-radius: 50%;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    z-index: 4;
}

.nx-film__light-cone {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(240, 230, 255, 0.95) 0%, rgba(200, 180, 250, 0.6) 50%, transparent 80%);
    opacity: 0;
    z-index: 5;
    pointer-events: none;
}

/* Scene 7: Kejelasan Masuk */
.nx-film__s7-box {
    position: absolute;
    inset: 8%;
    background: #FFFFFF;
    border-radius: 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
    padding: 1.5rem;
    z-index: 3;
}

.nx-film__s7-text {
    margin: 0;
    font-size: clamp(1.1rem, 2.2vw, 1.9rem);
    font-weight: 700;
    color: #4B0AA5;
    text-align: center;
}

.nx-film__highlight {
    background: linear-gradient(90deg, #C9B6F2 0%, #C9B6F2 100%);
    background-size: 0% 100%;
    background-repeat: no-repeat;
    padding-inline: 0.25rem;
    border-radius: 4px;
    transition: background-size 0.6s ease;
}

.nx-film__highlight.is-lit {
    background-size: 100% 100%;
}

.nx-film__cursor-wrap {
    position: absolute;
    bottom: 12%;
    right: 8%;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nx-film__pointer {
    width: 18px;
    height: 18px;
    transform: translate(-10px, -10px);
}

.nx-film__mini-pill {
    background: #4B0AA5;
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 600;
    padding: 0.35rem 0.8rem;
    border-radius: 14px;
    border: none;
    box-shadow: 0 2px 8px rgba(75, 10, 165, 0.3);
}

/* Scene 8: Orbit Bola Kaca & Kata Berganti */
.nx-film__orbit-stage {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 1.5rem;
    z-index: 3;
}

.nx-film__orbit-system {
    position: relative;
    width: clamp(90px, 14vw, 160px);
    height: clamp(90px, 14vw, 160px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.nx-film__orb-sphere {
    width: 48%;
    height: 48%;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #FFFFFF 0%, #D4BBFF 40%, #7C3AED 75%, #4B0AA5 100%);
    box-shadow: 0 0 32px rgba(167, 139, 250, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.8);
    position: relative;
    z-index: 3;
}

.nx-film__ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.nx-film__ring.r-1 { width: 75%; height: 75%; transform: rotateX(65deg) rotateY(15deg); }
.nx-film__ring.r-2 { width: 100%; height: 100%; transform: rotateX(65deg) rotateY(-25deg); }
.nx-film__ring.r-3 { width: 125%; height: 125%; transform: rotateX(65deg) rotateY(40deg); }

.nx-film__star {
    position: absolute;
    color: #FFFFFF;
    font-size: 11px;
    opacity: 0.8;
}

.nx-film__star.s-1 { top: 10%; right: 15%; animation: nx-star-twinkle 2s infinite alternate; }
.nx-film__star.s-2 { bottom: 15%; left: 10%; animation: nx-star-twinkle 2.5s infinite alternate-reverse; }
.nx-film__star.s-3 { top: 60%; right: 5%; animation: nx-star-twinkle 1.8s infinite alternate; }

@keyframes nx-star-twinkle {
    0% { transform: scale(0.6); opacity: 0.3; }
    100% { transform: scale(1.2); opacity: 1; }
}

.nx-film__dot {
    position: absolute;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #FFFFFF;
    box-shadow: 0 0 6px #FFFFFF;
}

.nx-film__dot.dt-1 { top: 20%; left: 30%; }
.nx-film__dot.dt-2 { bottom: 25%; right: 25%; }
.nx-film__dot.dt-3 { top: 45%; left: 10%; }
.nx-film__dot.dt-4 { bottom: 10%; right: 40%; }

.nx-film__orbit-words {
    position: relative;
    width: clamp(110px, 16vw, 180px);
    height: 50px;
    display: flex;
    align-items: center;
}

.nx-film__ow-item {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    opacity: 0;
    transform: translateY(8px);
    filter: blur(6px);
    transition: opacity 0.45s ease, transform 0.45s ease, filter 0.45s ease;
}

.nx-film__ow-item.is-active {
    opacity: 1;
    transform: translateY(0);
    filter: blur(0px);
}

.nx-film__ow-icon {
    font-size: clamp(1.2rem, 1.8vw, 1.6rem);
}

.nx-film__ow-label {
    font-size: clamp(1.1rem, 1.8vw, 1.6rem);
    font-weight: 700;
    color: #FFFFFF;
    letter-spacing: -0.01em;
}

/* SVG Filter hide */
.nx-film__svg-filter {
    position: absolute;
    width: 0;
    height: 0;
    pointer-events: none;
}

/* Mobile responsive scrub */
@media (max-width: 768px) {
    .nx-scrub__row {
        flex-direction: column;
        gap: 1.5rem;
    }

    .nx-scrub__word {
        font-size: clamp(1.8rem, 8vw, 2.8rem);
    }

    .nx-scrub__word--left {
        text-align: center;
        margin-right: 0;
    }

    .nx-scrub__word--right {
        text-align: center;
        margin-left: 0;
    }

    .nx-scrub__card {
        width: 82vw;
        height: 48vh;
    }
}
"""

c2_start = css.find('SECTION 2')
c3_start = css.find('SECTION 3')
sc2_start = css.rfind('/*', 0, c2_start)
sc3_start = css.rfind('/*', 0, c3_start)

css = css[:sc2_start] + new_scrub_css + '\n\n' + css[sc3_start:]

with open(r'resources/css/landing.css', 'w', encoding='utf-8') as f:
    f.write(css)
print('Updated Section 2 in landing.css')

# =========================================================================
# 3. UPDATE landing.js (Section 2 - Layout Scrub & Mini Film Timeline)
# =========================================================================
with open(r'resources/js/landing.js', 'r', encoding='utf-8') as f:
    js = f.read()

new_scrub_js = """/* ============================================================
   SECTION 2 — PINNED SCRUB (RUANG KERJA ... TERPADU)
   A. LAYOUT SCRUB:
      - Baris flex terpusat di y:50vh.
      - Kata kiri rata-kanan, kata kanan rata-kiri (oranye #F0490A).
      - Kartu awal 25vw x 29vh, akhir 68vw x 67vh.
      - Kata kiri & kanan bergeser keluar setengah pertumbuhan kartu.
      - Latar: 25vw #000, 36vw #444, 45vw #5F5F5F, 57vw #BBBBBB, 68vw #F5F5F5 -> #FFF.
      - Pin ±700vh, scrub 0.6.
      - Di akhir, kartu naik keluar layar (yPercent: -150).
   B. ISI KARTU = FILM MINI OTOMATIS (GSAP Timeline, 8 Scene).
   ============================================================ */
function initScrub() {
    const section = q('#nxScrub') || q('[data-scrub]');
    if (!section) return;

    const stage = q('#nxScrubStage', section);
    const card = q('#nxScrubCard', section);
    const left = q('#nxScrubLeft', section);
    const right = q('#nxScrubRight', section);

    // Start Mini Film when section approaches
    initMiniFilm(card);

    if (prefersReducedMotion() || !stage || !card) {
        return;
    }

    // Scroll Scrub Timeline
    const scrubTL = gsap.timeline({
        scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: 'bottom bottom',
            pin: stage,
            pinSpacing: true,
            scrub: 0.6,
            invalidateOnRefresh: true,
        },
    });

    // 1. Expand Card: width 25vw -> 68vw, height 29vh -> 67vh
    scrubTL.to(card, {
        width: '68vw',
        height: '67vh',
        ease: 'power1.inOut',
        duration: 0.85,
    }, 0);

    // 2. Translate Words Outward: kata bergeser keluar setengah pertambahan lebar kartu (+21.5vw)
    scrubTL.to(left, {
        x: '-28vw',
        color: '#333333',
        ease: 'power1.inOut',
        duration: 0.85,
    }, 0);

    scrubTL.to(right, {
        x: '28vw',
        ease: 'power1.inOut',
        duration: 0.85,
    }, 0);

    // 3. Background Color Transition
    // 25vw #000, 36vw #444, 45vw #5F5F5F, 57vw #BBBBBB, 68vw #F5F5F5 -> #FFF
    scrubTL.to(section, {
        backgroundColor: '#444444',
        duration: 0.22,
        ease: 'none',
    }, 0);
    scrubTL.to(section, {
        backgroundColor: '#5F5F5F',
        duration: 0.22,
        ease: 'none',
    }, 0.22);
    scrubTL.to(section, {
        backgroundColor: '#BBBBBB',
        duration: 0.22,
        ease: 'none',
    }, 0.44);
    scrubTL.to(section, {
        backgroundColor: '#F5F5F5',
        duration: 0.17,
        ease: 'none',
    }, 0.66);
    scrubTL.to(section, {
        backgroundColor: '#FFFFFF',
        duration: 0.15,
        ease: 'none',
    }, 0.83);

    // 4. Di akhir: Kartu naik keluar layar (yPercent: -150)
    scrubTL.to(card, {
        yPercent: -150,
        opacity: 0,
        ease: 'power2.in',
        duration: 0.15,
    }, 0.85);
}

/**
 * FILM MINI OTOMATIS (GSAP Timeline independen, bukan scrub)
 * Mulai saat section masuk viewport, tidak berbalik saat scroll naik.
 */
function initMiniFilm(card) {
    if (!card) return;

    const film = q('#nxFilm', card);
    if (!film) return;

    const s1 = q('#nxScene1', film);
    const s2 = q('#nxScene2', film);
    const s3 = q('#nxScene3', film);
    const s46 = q('#nxScene46', film);
    const s7 = q('#nxScene7', film);
    const s8 = q('#nxScene8', film);

    if (prefersReducedMotion()) {
        if (s8) s8.classList.add('is-active');
        return;
    }

    const setScene = (activeScene) => {
        qa('.nx-film__scene', film).forEach((s) => s.classList.remove('is-active'));
        if (activeScene) activeScene.classList.add('is-active');
    };

    let filmStarted = false;

    ScrollTrigger.create({
        trigger: '#nxScrub',
        start: 'top 85%',
        once: true,
        onEnter: () => {
            if (filmStarted) return;
            filmStarted = true;
            playFilm();
        },
    });

    function playFilm() {
        const tl = gsap.timeline({ repeat: 0 });

        // Scene 1: Riak gelombang konsentris & "Setiap kerja dimulai" (0s - 3s)
        tl.call(() => setScene(s1))
            .fromTo('.nx-film__ripple', { scale: 0, opacity: 0.8 }, { scale: 2.6, opacity: 0, duration: 1.8, stagger: 0.35, ease: 'power2.out' })
            .fromTo('#nxS1Pill', { scale: 0, y: 15 }, { scale: 1, y: 0, duration: 0.6, ease: 'back.out(2)' }, '-=1.2')
            .to(s1, { opacity: 0, duration: 0.4 }, '+=0.8');

        // Scene 2: Disolusi piksel & wordmark "ruang kosong" (3s - 6s)
        tl.call(() => setScene(s2))
            .set(s2, { opacity: 1 })
            .to('.nx-film__pixel', { opacity: 0, duration: 0.05, stagger: { amount: 0.45, from: 'random' } })
            .fromTo('#nxQType', { opacity: 0, scale: 0.5 }, { opacity: 1, scale: 1, duration: 0.25 })
            .to('#nxQType', { opacity: 0, duration: 0.2 }, '+=0.2')
            .fromTo('#nxWhiteCard', { opacity: 0, scale: 0.9, filter: 'blur(16px)' }, { opacity: 1, scale: 1, filter: 'blur(0px)', duration: 0.6, ease: 'power3.out' })
            .to(s2, { opacity: 0, duration: 0.4 }, '+=1.0');

        // Scene 3: Tombol tempat sampah gooey + 6 tetesan (6s - 8.5s)
        tl.call(() => setScene(s3))
            .set(s3, { opacity: 1 })
            .fromTo('#nxTrashBtn', { y: 80, scale: 0.6 }, { y: 0, scale: 1, duration: 0.7, ease: 'back.out(1.8)' })
            .fromTo('.nx-film__drop', { scale: 0, x: 0, y: 0 }, {
                scale: 1,
                x: (i) => Math.cos((i * 60 * Math.PI) / 180) * 36,
                y: (i) => Math.sin((i * 60 * Math.PI) / 180) * 36,
                duration: 0.5,
                ease: 'power2.out',
            }, '-=0.3')
            .to('.nx-film__drop', { scale: 0, opacity: 0, duration: 0.35 })
            .to(s3, { opacity: 0, duration: 0.35 }, '+=0.4');

        // Scene 4-6: 3 Pill jatuh, tombol memakan, kerucut cahaya (8.5s - 13.5s)
        tl.call(() => setScene(s46))
            .set(s46, { opacity: 1 })
            .fromTo('#nxDropPill1', { opacity: 0, y: -60, rotation: -3 }, { opacity: 1, y: 0, duration: 0.45, ease: 'power2.out' })
            .to('#nxTrashEater', { scale: 1.25, duration: 0.18, yoyo: true, repeat: 1 }, '-=0.1')
            .fromTo('#nxDropPill2', { opacity: 0, y: -60, rotation: 3 }, { opacity: 1, y: 0, duration: 0.45, ease: 'power2.out' }, '+=0.2')
            .to('#nxTrashEater', { scale: 1.25, duration: 0.18, yoyo: true, repeat: 1 }, '-=0.1')
            .fromTo('#nxDropPill3', { opacity: 0, y: -60, rotation: -2 }, { opacity: 1, y: 0, duration: 0.45, ease: 'power2.out' }, '+=0.2')
            .to('#nxTrashEater', { scale: 1.25, duration: 0.18, yoyo: true, repeat: 1 }, '-=0.1')
            .fromTo('#nxLightCone', { opacity: 0, scaleY: 0 }, { opacity: 1, scaleY: 1, transformOrigin: 'top center', duration: 0.8, ease: 'power2.inOut' }, '+=0.3')
            .to(s46, { opacity: 0, duration: 0.4 }, '+=0.6');

        // Scene 7: "Lalu kejelasan masuk." + highlight + kursor (13.5s - 17.5s)
        tl.call(() => setScene(s7))
            .set(s7, { opacity: 1 })
            .fromTo('.nx-film__s7-text', { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.5 })
            .call(() => {
                const hl = q('#nxHighlight', film);
                if (hl) hl.classList.add('is-lit');
            }, null, '+=0.2')
            .fromTo('.nx-film__cursor-wrap', { opacity: 0, x: 20 }, { opacity: 1, x: 0, duration: 0.4 }, '+=0.3')
            .to('#nxMiniPill', { scale: 0.9, duration: 0.15, yoyo: true, repeat: 1 })
            .to(s7, { opacity: 0, duration: 0.4 }, '+=0.8');

        // Scene 8: Orbit bola kaca bercahaya, 3 cincin, titik bergerak, kata berganti (17.5s+)
        tl.call(() => {
            setScene(s8);
            startOrbitLoop(film);
        });
    }

    function startOrbitLoop(film) {
        const words = qa('.nx-film__ow-item', film);
        if (!words.length) return;

        let cur = 0;
        setInterval(() => {
            words.forEach((w) => w.classList.remove('is-active'));
            cur = (cur + 1) % words.length;
            words[cur].classList.add('is-active');
        }, 2200);
    }
}
"""

# Replace initScrub in landing.js
m_scrub = re.search(r'function initScrub\(\)\s*\{', js)
m_mod = re.search(r'/\* =+\s*SECTION 4', js)

if m_scrub and m_mod:
    js = js[:m_scrub.start()] + new_scrub_js + '\n\n' + js[m_mod.start():]
    with open(r'resources/js/landing.js', 'w', encoding='utf-8') as f:
        f.write(js)
    print('Updated Section 2 in landing.js successfully')
else:
    print('Failed to find markers for initScrub in landing.js')

