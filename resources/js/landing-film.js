/**
 * NEXUS AI OFFICE — Film Mini 7 Scene di dalam Kartu Ungu (Section 2 Scrub).
 *
 * Sesuai Spesifikasi & Referensi Visual Zywra:
 *  - Scene 0 (3s): Riak 3 lingkaran konsentris + teks "Setiap kerja" + badge "dimulai".
 *  - Scene 1 (1.2s): Morfisme ke tombol bulat putih dengan ikon tempat sampah + efek gooey 6 droplets.
 *  - Scene 2 (2s): Pill "Banyak tab terbuka." jatuh ke tombol (rolling 3D, blur trail), tombol memakan elastis.
 *  - Scene 3 (3s): Pill "Data tercecer." + pill "Harus ganti aplikasi." menumpuk (3 lapisan) + kerucut cahaya melebar.
 *  - Scene 4 (3.2s): Kartu putih glowing + "Lalu kejelasan masuk." dengan highlight menyapu + kursor tekan "Mulai".
 *  - Scene 5 (±12s): Orbit bola kaca (11vw), 3 cincin konsentris, 5 titik berjalan, 2 kilau bintang, 4 kata berganti:
 *                    "AI" (x=22%), "Musik" (x=14%), "Kasira" (x=6%), "Produktivitas" (x=22%, scale 1.15).
 *  - Scene 6 (0.5s): Crossfade kembali ke Scene 0 (looping tak terbatas selama pin aktif).
 *
 * Reduced Motion: Tampilkan Scene 5 secara statis tanpa loop.
 */

import { gsap } from 'gsap';

export function initLandingFilm(card) {
    if (!card) return;

    const film = card.querySelector('#nxFilm');
    if (!film) return;

    const s0 = film.querySelector('#nxS0');
    const s123 = film.querySelector('#nxS123');
    const s4 = film.querySelector('#nxS4');
    const s5 = film.querySelector('#nxS5');

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Ikon untuk Scene 5 (Bola Kaca)
    const SPHERE_ICONS = {
        ai: `<svg viewBox="0 0 24 24" width="28" height="28" fill="#FFFFFF" aria-hidden="true"><path d="m12 2 2.6 6.8L21 12l-6.4 3.2L12 22l-2.6-6.8L3 12l6.4-3.2L12 2z"/></svg>`,
        music: `<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>`,
        kasira: `<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1z"/><line x1="8" y1="8" x2="16" y2="8"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="8" y1="16" x2="12" y2="16"/></svg>`,
        prod: `<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>`,
    };

    const sphereIconContainer = film.querySelector('#nxSphereIcon');
    const orbitWord = film.querySelector('#nxOrbitWord');
    const sphereWrap = film.querySelector('#nxSphereWrap');

    // JIKA PREFERS REDUCED MOTION: Tampilkan Scene 5 secara statis
    if (prefersReducedMotion) {
        if (s0) s0.style.display = 'none';
        if (s123) s123.style.display = 'none';
        if (s4) s4.style.display = 'none';
        if (s5) {
            s5.style.opacity = '1';
            s5.style.display = 'flex';
        }
        if (sphereIconContainer) sphereIconContainer.innerHTML = SPHERE_ICONS.ai;
        if (orbitWord) orbitWord.textContent = 'AI';
        if (sphereWrap) {
            sphereWrap.style.left = '22%';
            sphereWrap.style.transform = 'translate(-50%, -50%)';
        }
        return null;
    }

    // Set initial states
    gsap.set([s123, s4, s5], { opacity: 0 });
    gsap.set(s0, { opacity: 1 });
    if (sphereIconContainer) sphereIconContainer.innerHTML = SPHERE_ICONS.ai;

    // Timeline Master Otomatis (looping tak terbatas)
    const tl = gsap.timeline({ repeat: -1 });

    // ============================================================
    // SCENE 0: Riak 3 lingkaran + "Setiap kerja" + "dimulai" (3.0s)
    // ============================================================
    tl.addLabel('scene0')
        .set(s0, { opacity: 1, zIndex: 10 })
        .set(s123, { opacity: 0, zIndex: 1 })
        .set(s4, { opacity: 0, zIndex: 1 })
        .set(s5, { opacity: 0, zIndex: 1 })
        .set('#nxS0Text', { opacity: 0, y: 12, scale: 0.95 })
        .set('#nxS0Badge', { opacity: 0, scale: 0.8 })
        .set('.nx-film__ripple', { scale: 0.35, opacity: 0.75 })
        // Denyut riak
        .to('.nx-film__ripple', {
            scale: 1.65,
            opacity: 0,
            duration: 1.8,
            stagger: 0.45,
            ease: 'power2.out',
        })
        // Masuk teks "Setiap kerja"
        .to('#nxS0Text', {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.6,
            ease: 'power3.out',
        }, 'scene0+=0.3')
        // Masuk badge "dimulai" dengan sedikit bounce
        .to('#nxS0Badge', {
            opacity: 1,
            scale: 1,
            duration: 0.65,
            ease: 'back.out(2)',
        }, 'scene0+=0.55')
        // Tahan sebentar
        .to({}, { duration: 1.4 });

    // ============================================================
    // SCENE 1: Morfisme ke Tombol Sampah Gooey + 6 Droplets (1.2s)
    // ============================================================
    tl.addLabel('scene1')
        .to(['#nxS0Text', '.nx-film__s0-rest'], {
            opacity: 0,
            scale: 0.6,
            duration: 0.35,
            ease: 'power2.in',
        })
        .to('#nxS0D', {
            scale: 1.4,
            duration: 0.2,
            ease: 'power1.out',
        }, '-=0.1')
        .set(s123, { opacity: 1, zIndex: 10 })
        .set(s0, { opacity: 0 })
        .fromTo('#nxTrashBtn', {
            scale: 0,
            y: 30,
        }, {
            scale: 1,
            y: 0,
            duration: 0.55,
            ease: 'back.out(1.8)',
        })
        // 6 Tetesan putih memercik radial 0.5s lalu menyatu
        .fromTo('.nx-film__drop', {
            x: 0,
            y: 0,
            scale: 0.8,
            opacity: 1,
        }, {
            x: (i) => Math.cos((i * 60 * Math.PI) / 180) * 42,
            y: (i) => Math.sin((i * 60 * Math.PI) / 180) * 42,
            duration: 0.32,
            ease: 'power2.out',
        }, '-=0.35')
        .to('.nx-film__drop', {
            x: 0,
            y: 0,
            duration: 0.28,
            ease: 'power2.in',
        })
        .to({}, { duration: 0.1 });

    // ============================================================
    // SCENE 2: Pill "Banyak tab terbuka." jatuh ke tombol (2.0s)
    // ============================================================
    tl.addLabel('scene2')
        .fromTo('#nxFilmPill1', {
            y: -90,
            rotation: -4,
            rotationX: 28,
            opacity: 0,
            filter: 'blur(6px)',
            scale: 1,
        }, {
            y: 10,
            rotation: -4,
            rotationX: 0,
            opacity: 1,
            filter: 'blur(0px)',
            duration: 0.65,
            ease: 'power2.in',
        })
        // Tombol menggembung elastis saat memakan pill
        .to('#nxTrashBtn', {
            scaleY: 1.18,
            scaleX: 0.94,
            borderRadius: '50% 50% 50% 50% / 60% 60% 40% 40%',
            duration: 0.18,
            yoyo: true,
            repeat: 1,
            ease: 'power2.out',
        })
        .to('#nxFilmPill1', {
            scale: 0.15,
            y: 40,
            opacity: 0,
            duration: 0.3,
            ease: 'power2.in',
        }, '-=0.25')
        .to({}, { duration: 0.4 });

    // ============================================================
    // SCENE 3: Pill 2 & 3 Menumpuk + Kerucut Cahaya Melebar (3.0s)
    // ============================================================
    tl.addLabel('scene3')
        // Pill 2 ("Data tercecer.") jatuh
        .fromTo('#nxFilmPill2', {
            y: -90,
            rotation: 2,
            rotationX: 28,
            opacity: 0,
            filter: 'blur(6px)',
        }, {
            y: 0,
            rotation: 2,
            rotationX: 0,
            opacity: 1,
            filter: 'blur(0px)',
            duration: 0.55,
            ease: 'power2.out',
        })
        // Pill 3 ("Harus ganti aplikasi.") jatuh menumpuk
        .fromTo('#nxFilmPill3', {
            y: -80,
            rotation: -1,
            rotationX: 28,
            opacity: 0,
            filter: 'blur(6px)',
        }, {
            y: 14,
            rotation: -1,
            rotationX: 0,
            opacity: 1,
            filter: 'blur(0px)',
            duration: 0.55,
            ease: 'power2.out',
        }, '-=0.2')
        // Pill 1 muncul di tumpukan belakang (3 lapisan: -3deg, +2deg, -1deg, offset 14px)
        .fromTo('#nxFilmPill1', {
            y: -14,
            rotation: -3,
            opacity: 0,
            scale: 0.95,
        }, {
            opacity: 0.65,
            y: -14,
            rotation: -3,
            scale: 0.95,
            duration: 0.35,
        }, '-=0.4')
        // Kerucut cahaya turun dari atas ke tumpukan
        .fromTo('#nxLightCone', {
            opacity: 0,
            scaleY: 0,
            scaleX: 0.7,
        }, {
            opacity: 1,
            scaleY: 1,
            scaleX: 1,
            transformOrigin: 'top center',
            duration: 0.65,
            ease: 'power2.inOut',
        }, '+=0.1')
        // Kerucut melebar memenuhi kartu hingga kartu putih
        .to('#nxLightCone', {
            scale: 3.5,
            opacity: 1,
            duration: 0.55,
            ease: 'power2.in',
        }, '+=0.2');

    // ============================================================
    // SCENE 4: Kartu Putih + "Lalu kejelasan masuk." + Kursor (3.2s)
    // ============================================================
    tl.addLabel('scene4')
        .set(s4, { opacity: 1, zIndex: 15 })
        .set(s123, { opacity: 0, zIndex: 1 })
        .fromTo('.nx-film__s4-title', {
            opacity: 0,
            y: 16,
        }, {
            opacity: 1,
            y: 0,
            duration: 0.5,
            ease: 'power3.out',
        })
        // Highlight kotak lavender menyapu kiri ke kanan 0.5s
        .fromTo('#nxS4HlBg', {
            scaleX: 0,
        }, {
            scaleX: 1,
            transformOrigin: 'left center',
            duration: 0.5,
            ease: 'power2.inOut',
        }, '+=0.15')
        // Kursor panah SVG bergerak dari kanan-atas ke kata, lalu ke pill "Mulai"
        .fromTo('#nxS4Cursor', {
            opacity: 0,
            x: 180,
            y: -40,
        }, {
            opacity: 1,
            x: 60,
            y: 20,
            duration: 0.65,
            ease: 'power2.out',
        }, '-=0.1')
        .to('#nxS4Cursor', {
            x: 130,
            y: 70,
            duration: 0.65,
            ease: 'power2.inOut',
        }, '+=0.15')
        // Kursor menekan label "Mulai" (scale .9)
        .to('#nxStartPill', {
            scale: 0.9,
            duration: 0.15,
            yoyo: true,
            repeat: 1,
            ease: 'power2.inOut',
        })
        .to({}, { duration: 0.4 })
        // Kartu memudar kembali ke ungu 0.4s
        .to(s4, {
            opacity: 0,
            duration: 0.4,
            ease: 'power2.inOut',
        });

    // ============================================================
    // SCENE 5: Orbit di Kartu Ungu (±12s: AI, Musik, Kasira, Produktivitas)
    // ============================================================
    tl.addLabel('scene5')
        .set(s5, { opacity: 1, zIndex: 10 })
        // Sub-state 1: "AI" (x=22%, bintang berkilau)
        .call(() => {
            if (sphereIconContainer) sphereIconContainer.innerHTML = SPHERE_ICONS.ai;
            if (orbitWord) orbitWord.textContent = 'AI';
        })
        .set(sphereWrap, { left: '22%', scale: 1 })
        .fromTo('#nxOrbitWord', {
            opacity: 0,
            filter: 'blur(8px)',
        }, {
            opacity: 1,
            filter: 'blur(0px)',
            duration: 0.45,
            ease: 'power2.out',
        })
        .to({}, { duration: 2.2 })

        // Sub-state 2: "Musik" (x=14%, not musik)
        .to('#nxOrbitWord', {
            opacity: 0,
            filter: 'blur(8px)',
            duration: 0.35,
            ease: 'power2.in',
        })
        .to(sphereWrap, {
            left: '14%',
            scale: 1,
            duration: 0.8,
            ease: 'power3.inOut',
        }, '-=0.15')
        .call(() => {
            if (sphereIconContainer) sphereIconContainer.innerHTML = SPHERE_ICONS.music;
            if (orbitWord) orbitWord.textContent = 'Musik';
        })
        .to('#nxOrbitWord', {
            opacity: 1,
            filter: 'blur(0px)',
            duration: 0.45,
            ease: 'power2.out',
        })
        .to({}, { duration: 2.2 })

        // Sub-state 3: "Kasira" (x=6% separuh terpotong, struk)
        .to('#nxOrbitWord', {
            opacity: 0,
            filter: 'blur(8px)',
            duration: 0.35,
            ease: 'power2.in',
        })
        .to(sphereWrap, {
            left: '6%',
            scale: 1,
            duration: 0.8,
            ease: 'power3.inOut',
        }, '-=0.15')
        .call(() => {
            if (sphereIconContainer) sphereIconContainer.innerHTML = SPHERE_ICONS.kasira;
            if (orbitWord) orbitWord.textContent = 'Kasira';
        })
        .to('#nxOrbitWord', {
            opacity: 1,
            filter: 'blur(0px)',
            duration: 0.45,
            ease: 'power2.out',
        })
        .to({}, { duration: 2.2 })

        // Sub-state 4: "Produktivitas" (x=22%, scale 1.15, centang)
        .to('#nxOrbitWord', {
            opacity: 0,
            filter: 'blur(8px)',
            duration: 0.35,
            ease: 'power2.in',
        })
        .to(sphereWrap, {
            left: '22%',
            scale: 1.15,
            duration: 0.8,
            ease: 'power3.inOut',
        }, '-=0.15')
        .call(() => {
            if (sphereIconContainer) sphereIconContainer.innerHTML = SPHERE_ICONS.prod;
            if (orbitWord) orbitWord.textContent = 'Produktivitas';
        })
        .to('#nxOrbitWord', {
            opacity: 1,
            filter: 'blur(0px)',
            duration: 0.45,
            ease: 'power2.out',
        })
        .to({}, { duration: 2.2 });

    // ============================================================
    // SCENE 6: Crossfade kembali ke Scene 0 (0.5s)
    // ============================================================
    tl.addLabel('scene6')
        .to(s5, {
            opacity: 0,
            duration: 0.5,
            ease: 'power2.inOut',
        })
        .to(s0, {
            opacity: 1,
            duration: 0.5,
            ease: 'power2.inOut',
        }, '<');

    return tl;
}

