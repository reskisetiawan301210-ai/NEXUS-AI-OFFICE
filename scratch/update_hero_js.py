import re

with open(r'resources/js/landing.js', 'r', encoding='utf-8') as f:
    js = f.read()

# Replace initHero and initWordRotate default fallback words
old_init_hero = """function initHero() {
    const hero = q('#nxHero');
    if (!hero) return;

    const lines = qa('[data-hero-line]', hero);
    const fades = qa('[data-hero-fade]', hero);
    const visual = q('[data-hero-visual]', hero);
    const fine = window.matchMedia('(pointer: fine)').matches;

    // Aset final pemilik (hero-visual.*): saat termuat, placeholder kapsul
    // + animasinya nonaktif via .is-media (CSS).
    const media = visual ? q('img, video', visual) : null;
    if (visual && media) {
        const show = () => visual.classList.add('is-media');
        // Aset belum ada (hero-visual.* menyusul dari pemilik): hapus elemen
        // agar tidak tampil ikon gambar rusak; placeholder kapsul tetap jalan.
        const hide = () => {
            visual.classList.remove('is-media');
            media.remove();
        };
        if (media.tagName === 'VIDEO') {
            show();
        } else if (media.complete && media.naturalWidth > 0) {
            show();
        } else {
            media.addEventListener('load', show, { once: true });
            media.addEventListener('error', hide, { once: true });
        }
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

    // Parallax kursor maks 12px, hanya pointer:fine (Bagian 7.2).
    // Gerak rotateY kapsul (±25°, 8s) berjalan di CSS.
    if (visual && DESKTOP_QUERY.matches && fine) {
        const xTo = gsap.quickTo(visual, 'x', { duration: 0.8, ease: 'power3.out' });
        const yTo = gsap.quickTo(visual, 'y', { duration: 0.8, ease: 'power3.out' });

        hero.addEventListener('mousemove', (event) => {
            const rect = hero.getBoundingClientRect();
            const nx = (event.clientX - rect.left) / rect.width - 0.5;
            const ny = (event.clientY - rect.top) / rect.height - 0.5;
            xTo(nx * 24 > 12 ? 12 : nx * 24 < -12 ? -12 : nx * 24);
            yTo(ny * 24 > 12 ? 12 : ny * 24 < -12 ? -12 : ny * 24);
        });
    }

    // TIDAK ada pin / scale / fade saat scroll: hero tergulung ke atas dan
    // section hitam (#000) masuk langsung di bawahnya tanpa celah.
}"""

new_init_hero = """function initHero() {
    const hero = q('#nxHero');
    if (!hero) return;

    const lines = qa('[data-hero-line]', hero);
    const fades = qa('[data-hero-fade]', hero);
    const visual = q('[data-hero-visual]', hero);
    const fine = window.matchMedia('(pointer: fine)').matches;

    // Masker toggle: State 1 (wajah terbuka) <-> State 2 (masker SVG presisi)
    const mask = q('#nxRobotMask', hero);
    if (mask && visual && !prefersReducedMotion()) {
        let isMasked = false;
        setInterval(() => {
            isMasked = !isMasked;
            visual.classList.toggle('is-masked', isMasked);
        }, 4800);

        visual.style.pointerEvents = 'auto';
        visual.style.cursor = 'pointer';
        visual.addEventListener('click', () => {
            isMasked = !isMasked;
            visual.classList.toggle('is-masked', isMasked);
        });
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

    // Parallax kursor maks 14px untuk desktop fine pointer
    if (visual && DESKTOP_QUERY.matches && fine) {
        const xTo = gsap.quickTo(visual, 'x', { duration: 0.8, ease: 'power3.out' });
        const yTo = gsap.quickTo(visual, 'y', { duration: 0.8, ease: 'power3.out' });

        hero.addEventListener('mousemove', (event) => {
            const rect = hero.getBoundingClientRect();
            const nx = (event.clientX - rect.left) / rect.width - 0.5;
            const ny = (event.clientY - rect.top) / rect.height - 0.5;
            xTo(nx * 28 > 14 ? 14 : nx * 28 < -14 ? -14 : nx * 28);
            yTo(ny * 28 > 14 ? 14 : ny * 28 < -14 ? -14 : ny * 28);
        });
    }
}"""

if old_init_hero in js:
    js = js.replace(old_init_hero, new_init_hero)
else:
    # Use regex replacement if whitespace differed
    pattern = r'function initHero\(\)\s*\{.*?// TIDAK ada pin.*?\n\}'
    js = re.sub(pattern, new_init_hero, js, flags=re.DOTALL)

# Update word scramble fallback words
js = js.replace(
    "words = ['PRODUKTIF', 'TERPADU', 'CEPAT', 'RINGAN'];",
    "words = ['DIGITAL', 'PINTAR', 'OTOMATIS', 'PRODUKTIF'];"
)

with open(r'resources/js/landing.js', 'w', encoding='utf-8') as f:
    f.write(js)

print('Updated initHero and initWordRotate in landing.js successfully')

