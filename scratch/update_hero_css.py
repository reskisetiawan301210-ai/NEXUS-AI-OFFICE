import re

with open(r'resources/css/landing.css', 'r', encoding='utf-8') as f:
    css = f.read()

m1 = re.search(r'/\* =+\s*SECTION 1\b.*?\*/', css, re.DOTALL)
m2 = re.search(r'/\* =+\s*SECTION 2\b', css)

if not m1 or not m2:
    print('Failed to find Section 1 bounds')
    exit(1)

new_hero_css = """/* ============================================================
   SECTION 1 — HERO
   Refined to precisely mirror ZYWRA reference aesthetics:
   - Living warm amber/orange radial glow
   - Centered-right high-tech cyber bust (hero-robot) with mask layer
   - 3-line Orbitron display headline overlapping character
   - Amber scramble word in front of the bust
   - Translucent frosted glass card with 3D glowing orb
   - Balanced editorial tagline
   ============================================================ */
.nx-hero {
    position: relative;
    z-index: 1;
    height: 100vh;
    height: 100svh;
    min-height: 600px;
    overflow: hidden;
    isolation: isolate;
    background: radial-gradient(ellipse 75% 65% at 58% 38%, #FF7200 0%, #D94800 35%, #9E1A04 70%, #2B0701 100%) !important;
    background-color: #2B0701 !important;
}

/* Subtle corner vignette so white navbar and logo pop with crisp contrast */
.nx-hero__shade {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background: radial-gradient(circle at 12% 12%, rgba(0, 0, 0, 0.4) 0%, transparent 45%),
                linear-gradient(to top, rgba(15, 3, 0, 0.4) 0%, transparent 20%);
}

/* Fine-grain noise texture */
.nx-hero__grain {
    position: absolute;
    inset: 0;
    z-index: 6;
    pointer-events: none;
    opacity: 0.035;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)'/%3E%3C/svg%3E");
}

/* -------------------------------------------------------------
   HERO FIGURE (Sosok Robot Utama)
   Terletak di tengah-kanan, dada terpotong di tepi bawah layar.
   Dua state: Wajah Terbuka (State 1) dan Masker SVG (State 2).
   ------------------------------------------------------------- */
.nx-hero__visual {
    position: absolute;
    left: 54%;
    bottom: 0;
    transform: translateX(-50%);
    width: clamp(520px, 58vw, 1100px);
    height: 94svh;
    z-index: 2;
    pointer-events: none;
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

.nx-hero__mask {
    z-index: 3;
    opacity: 0;
    transition: opacity 0.8s var(--nx-ease);
}

/* State 2: Masker aktif */
.nx-hero__visual.is-masked .nx-hero__mask {
    opacity: 1;
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
    padding-left: clamp(2rem, 6.5vw, 6.5rem);
    padding-right: clamp(2rem, 6.5vw, 6.5rem);
    pointer-events: none;
}

.nx-hero__copy {
    position: relative;
    z-index: 4;
    width: 100%;
    max-width: none;
    pointer-events: auto;
}

/* Badge di atas headline */
.nx-badge {
    align-self: flex-start;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    height: 32px;
    padding-inline: 0.95rem;
    margin-bottom: clamp(14px, 2.5vh, 24px);
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

/* Headline Orbitron 900 3 Baris */
.nx-hero__headline {
    position: relative;
    z-index: 4;
    max-width: none;
    margin: 0;
    font-family: var(--font-hero);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    line-height: 1.06;
    color: #FFFFFF;
    font-size: clamp(2.4rem, 5.4vw, 5.8rem);
    text-shadow: 0 4px 28px rgba(0, 0, 0, 0.35);
}

/* Kata berganti: Amber solid yang berdiri di depan sosok robot */
.nx-swap {
    position: relative;
    z-index: 5;
    display: inline-block;
    min-width: 8ch;
    color: var(--nx-amber);
    font: inherit;
    font-weight: 900;
    white-space: nowrap;
    text-shadow: 0 0 30px rgba(255, 154, 0, 0.5);
}

.nx-line-mask {
    overflow: hidden;
    display: block;
    padding-bottom: 0.06em;
    margin-bottom: -0.06em;
}

.nx-line-inner {
    display: block;
    will-change: transform;
}

@media (min-width: 1024px) {
    .nx-hero__headline .nx-line-mask,
    .nx-hero__headline .nx-line-inner {
        white-space: nowrap;
    }
}

/* -------------------------------------------------------------
   KARTU KACA KANAN-BAWAH (Tentang NEXUS)
   Translucent glass dengan blur tinggi, border halus, orb 3D.
   ------------------------------------------------------------- */
.nx-glass {
    position: absolute;
    right: clamp(2rem, 5vw, 5.5rem);
    top: 52%;
    transform: translateY(-30%);
    width: clamp(310px, 24vw, 360px);
    z-index: 5;
    padding: 1.5rem;
    border-radius: 22px;
    border: 1px solid rgba(255, 255, 255, 0.22);
    background: rgba(35, 12, 4, 0.52);
    -webkit-backdrop-filter: blur(28px);
    backdrop-filter: blur(28px);
    box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.2);
    color: #FFFFFF;
}

.nx-glass__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 0.875rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.14);
}

.nx-glass__brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.95);
}

.nx-glass__body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-block: 1rem;
    gap: 1rem;
}

.nx-glass__features {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.nx-glass__feat {
    margin: 0;
    font-family: var(--font-hero);
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    line-height: 1.35;
    color: #FFFFFF;
}

.nx-glass__orb-wrap {
    flex: none;
    width: 76px;
    height: 76px;
    display: grid;
    place-items: center;
}

.nx-glass__orb {
    width: 100%;
    height: 100%;
    animation: nx-orb-float 6s ease-in-out infinite alternate;
}

@keyframes nx-orb-float {
    0% { transform: translateY(0px) rotate(0deg); }
    100% { transform: translateY(-4px) rotate(8deg); }
}

.nx-glass__desc {
    margin: 0;
    padding-top: 0.5rem;
    font-family: var(--font-body);
    font-size: 12px;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.78);
}

/* -------------------------------------------------------------
   TAGLINE KIRI-BAWAH (Hero Foot)
   ------------------------------------------------------------- */
.nx-hero__foot {
    position: absolute;
    left: clamp(2rem, 6.5vw, 6.5rem);
    bottom: clamp(1.5rem, 4vh, 2.5rem);
    z-index: 5;
    max-width: 44ch;
    margin: 0;
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 500;
    line-height: 1.65;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.68);
}

/* -------------------------------------------------------------
   RESPONSIVE HERO (Tablet & Mobile)
   ------------------------------------------------------------- */
@media (max-width: 1024px) {
    .nx-hero__visual {
        left: 56%;
        width: 65vw;
    }

    .nx-glass {
        right: 2rem;
        top: 56%;
        width: 320px;
    }
}

@media (max-width: 768px) {
    .nx-hero {
        height: auto;
        min-height: 100svh;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding-bottom: 2.5rem;
    }

    .nx-hero__visual {
        left: 50%;
        transform: translateX(-50%);
        width: 92vw;
        height: 56svh;
        opacity: 0.45;
        bottom: 8vh;
    }

    .nx-hero__content {
        padding-inline: 1.25rem;
        padding-top: calc(var(--nx-nav-h) + 1rem);
        justify-content: flex-end;
    }

    .nx-hero__headline {
        font-size: clamp(1.85rem, 8.5vw, 2.75rem);
        line-height: 1.1;
    }

    .nx-glass {
        position: relative;
        right: auto;
        top: auto;
        transform: none;
        width: 100%;
        margin-top: 1.5rem;
    }

    .nx-hero__foot {
        position: relative;
        left: auto;
        bottom: auto;
        margin-top: 1.5rem;
        max-width: 100%;
    }
}

"""

css = css[:m1.start()] + new_hero_css + css[m2.start():]

with open(r'resources/css/landing.css', 'w', encoding='utf-8') as f:
    f.write(css)

print('Updated Section 1 in resources/css/landing.css successfully')

