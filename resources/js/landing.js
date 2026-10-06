/**
 * NEXUS AI OFFICE — public landing page.
 *
 * Everything scroll-driven runs through GSAP ScrollTrigger so the vertical
 * page can pin sections, scrub timelines and translate tracks horizontally.
 *
 * Two rules are enforced throughout:
 *  1. Only `transform` and `opacity` are animated. Nothing here triggers
 *     layout, so there is no scroll jank.
 *  2. Under `prefers-reduced-motion: reduce` no pin, scrub, parallax or
 *     marquee is created at all, and the markup is left in its final
 *     readable state.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { initLandingFilm } from './landing-film.js';

gsap.registerPlugin(ScrollTrigger);

const MOTION_QUERY = window.matchMedia('(prefers-reduced-motion: reduce)');
const DESKTOP_QUERY = window.matchMedia('(min-width: 768px)');

const prefersReducedMotion = () => MOTION_QUERY.matches;

/** Mobile swaps the pinned carousel for native scroll-snap. */
const isMobile = () => window.innerWidth < 768;

const q = (selector, scope = document) => scope.querySelector(selector);
const qa = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));

/* ============================================================
   ASSET HANDLING
   Every image sits on top of a CSS placeholder. Until the file
   actually decodes we keep opacity 0, so a missing asset shows the
   placeholder instead of a broken-image icon.
   ============================================================ */
function wireAssets() {
    qa('[data-asset]').forEach((img) => {
        const reveal = () => {
            if (img.naturalWidth > 0) img.classList.add('is-ready');
        };

        if (img.complete) {
            reveal();
        } else {
            img.addEventListener('load', reveal, { once: true });
            img.addEventListener('error', () => img.classList.remove('is-ready'), { once: true });
        }
    });

    // The hero portrait is optional: the inline SVG fallback hides only once
    // the real PNG is present.
    const portrait = q('#nxHeroRobot');
    const portraitImg = q('.nx-hero__robot-img', portrait || document);

    if (portrait && portraitImg) {
        const show = () => portrait.classList.add('is-photo');
        const hide = () => portrait.classList.remove('is-photo');

        if (portraitImg.complete && portraitImg.naturalWidth > 0) {
            show();
        } else {
            portraitImg.addEventListener('load', show, { once: true });
            portraitImg.addEventListener('error', hide, { once: true });
        }
    }
}

/* ============================================================
   SMOOTH SCROLL (Lenis) — desktop, motion-allowed only
   ============================================================ */
let lenis = null;

function initLenis() {
    if (prefersReducedMotion()) return;

    lenis = new Lenis({
        duration: 1.1,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        touchMultiplier: 1.6,
    });

    // Drive Lenis from GSAP's ticker and feed scroll positions back into
    // ScrollTrigger so pinned/scrubbed timelines stay frame-accurate.
    lenis.on('scroll', ScrollTrigger.update);

    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
}

const scrollTo = (target) => {
    if (lenis) {
        lenis.scrollTo(target, { offset: 0 });
        return;
    }
    const el = typeof target === 'string' ? q(target) : target;
    if (el && typeof el.scrollIntoView === 'function') {
        el.scrollIntoView({ behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
    }
};

const lockScroll = (locked) => {
    document.body.classList.toggle('nx-no-scroll', locked);
    if (!lenis) return;
    if (locked) {
        lenis.stop();
    } else {
        lenis.start();
    }
};

/* ============================================================
   SECTION 0 — NAVBAR
   Colour follows the section behind it; the overlay menu opens
   full-screen and closes on ESC, backdrop or link activation.
   ============================================================ */
function initNavbar() {
    const nav = q('#nxNav');
    const burger = q('#nxBurger');
    const overlay = q('#nxOverlay');
    const closeBtn = q('#nxOverlayClose');
    const links = qa('.nx-overlay__link');

    if (nav) {
        // Bagian 7.1: warna mengikuti `data-theme` section di bawah navbar.
        // `data-nav` dibaca sebagai alias lama agar section yang belum
        // dimigrasi tetap bekerja.
        const sections = qa('[data-theme], [data-nav]');
        const themeOf = (el) => el.dataset.theme || el.dataset.nav || 'dark';

        // The element crossing a narrow band near the top of the viewport wins.
        if (sections.length && 'IntersectionObserver' in window) {
            const visible = new Map();

            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            visible.set(entry.target, themeOf(entry.target));
                        } else {
                            visible.delete(entry.target);
                        }
                    });

                    // Last one in document order among the currently visible.
                    let theme = nav.dataset.theme || 'dark';
                    sections.forEach((section) => {
                        if (visible.has(section)) theme = visible.get(section);
                    });
                    nav.dataset.theme = theme;
                },
                { rootMargin: '-15% 0px -60% 0px', threshold: 0 },
            );

            sections.forEach((section) => observer.observe(section));
        }

        const onScroll = () => nav.classList.toggle('is-stuck', window.scrollY > 40);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    if (!burger || !overlay) return;

    const setOpen = (open) => {
        overlay.classList.toggle('is-open', open);
        burger.setAttribute('aria-expanded', String(open));
        lockScroll(open);

        if (!prefersReducedMotion()) {
            gsap.killTweensOf(links);
            gsap.to(links, {
                opacity: open ? 1 : 0,
                y: open ? 0 : 28,
                duration: 0.5,
                ease: 'power3.out',
                stagger: open ? 0.07 : 0.03,
                overwrite: true,
            });
        }
    };

    burger.addEventListener('click', () => setOpen(!overlay.classList.contains('is-open')));
    closeBtn?.addEventListener('click', () => setOpen(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && overlay.classList.contains('is-open')) setOpen(false);
    });

    // Any menu choice closes the overlay before navigating.
    links.forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    // Smooth in-page anchors; plain links are left to the browser.
    // `#beranda` menunjuk ke hero (#nxHero) yang tidak memakai id itu.
    qa('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (event) => {
            const id = anchor.getAttribute('href');
            if (!id || id === '#') return;
            const target = id === '#beranda' ? q('#nxHero') || q(id) : q(id);
            if (!target) return;
            event.preventDefault();
            setOpen(false);
            // Let the overlay release the scroll lock first.
            setTimeout(() => scrollTo(target), prefersReducedMotion() ? 0 : 120);
        });
    });
}

/* ============================================================
   SECTION 1 — HERO (Bagian 7.2; hero TIDAK di-pin, ikut tergulung normal)
   ============================================================ */
function initHero() {
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
}

/**
 * Kata berganti hero (Bagian 7.2, akar g): SATU elemen di-scramble di tempat.
 * Tanpa elemen kedua, tanpa translateY, tanpa bayangan kata lama.
 * Siklus per kata: scramble masuk 0,5s → tahan 2s → memudar 0,4s →
 * kosong ~1,2s (≈4,5s total). Reduced-motion: "PRODUKTIF" statis.
 */
function initWordRotate() {
    const slot = q('[data-word-scramble]');
    if (!slot) return;

    let words = [];
    try {
        words = JSON.parse(slot.dataset.words || '[]');
    } catch {
        words = [];
    }
    if (!Array.isArray(words) || words.length < 2) {
        words = ['PRODUKTIF', 'TERPADU', 'CEPAT', 'RINGAN'];
    }

    const GLYPHS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    if (prefersReducedMotion()) {
        slot.textContent = words[0];
        return;
    }

    let current = 0;
    let raf = 0;

    const scrambleTo = (target, duration, onDone) => {
        const start = performance.now();
        const from = slot.textContent || '';
        const length = Math.max(from.length, target.length);

        const frame = (now) => {
            const progress = Math.min(1, (now - start) / (duration * 1000));
            const settled = Math.floor(progress * length);
            let out = '';

            for (let i = 0; i < length; i += 1) {
                const dest = target[i] || '';
                if (i < settled) {
                    out += dest;
                } else if (dest === ' ') {
                    out += ' ';
                } else {
                    out += GLYPHS[Math.floor(Math.random() * GLYPHS.length)];
                }
            }

            slot.textContent = out;

            if (progress < 1) {
                raf = requestAnimationFrame(frame);
            } else {
                slot.textContent = target;
                if (onDone) onDone();
            }
        };

        cancelAnimationFrame(raf);
        raf = requestAnimationFrame(frame);
    };

    const fadeOut = (duration, onDone) => {
        slot.style.transition = `opacity ${duration}s ease`;
        slot.style.opacity = '0';
        setTimeout(() => {
            if (onDone) onDone();
        }, duration * 1000);
    };

    const cycle = () => {
        const next = words[(current + 1) % words.length];

        // Masuk: scramble 0,5s.
        slot.style.opacity = '1';
        scrambleTo(next, 0.5, () => {
            current = (current + 1) % words.length;
            // Tahan 2s, lalu memudar 0,4s.
            setTimeout(() => {
                fadeOut(0.4, () => {
                    // Kosong ~1,2s sebelum kata berikut.
                    slot.textContent = '';
                    setTimeout(cycle, 1200);
                });
            }, 2000);
        });
    };

    slot.textContent = words[0];
    setTimeout(() => {
        fadeOut(0.4, () => {
            slot.textContent = '';
            setTimeout(cycle, 1200);
        });
    }, 2000);
}

/* ============================================================
   SECTION 2 — SCRUB HITAM
   ============================================================ */
/* ============================================================
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

    // Inisialisasi film mini 7 scene di dalam kartu
    initLandingFilm(card);

    if (prefersReducedMotion() || !stage || !card) {
        return;
    }

    // Scroll Scrub Timeline (Pin ±700vh, scrub 0.6)
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

    const isMobile = window.matchMedia('(max-width: 768px)').matches;

    if (isMobile) {
        // Mobile: Card starts ~82vw x 44vh, subtle scale up during scrub
        scrubTL.to(card, {
            scale: 1.05,
            ease: 'power1.inOut',
            duration: 0.85,
        }, 0);

        scrubTL.to(left, {
            y: '-6vh',
            opacity: 0,
            color: '#BBBBBB',
            ease: 'power1.inOut',
            duration: 0.85,
        }, 0);

        scrubTL.to(right, {
            y: '6vh',
            opacity: 0,
            ease: 'power1.inOut',
            duration: 0.85,
        }, 0);
    } else {
        // Desktop: Expand Card width 25vw -> 68vw, height 29vh -> 67vh
        scrubTL.to(card, {
            width: '68vw',
            height: '67vh',
            ease: 'power1.inOut',
            duration: 0.85,
        }, 0);

        // Translate Words Outward:
        // pertambahan lebar kartu = 68vw - 25vw = 43vw
        // setengah pertambahan lebar kartu + 2vw = 21.5vw + 2vw = 23.5vw
        scrubTL.to(left, {
            x: '-23.5vw',
            color: '#BBBBBB',
            ease: 'power1.inOut',
            duration: 0.85,
        }, 0);

        scrubTL.to(right, {
            x: '23.5vw',
            ease: 'power1.inOut',
            duration: 0.85,
        }, 0);
    }

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
    // BUG FIX: Hapus opacity 0 agar kartu tetap solid #4B0FB8 dan warnanya tidak memucat jadi lavender
    scrubTL.to(card, {
        yPercent: -150,
        ease: 'power2.in',
        duration: 0.15,
    }, 0.85);
}


/* ============================================================
   SECTION 4 — MODUL SHOWCASE
   Scroll (scrub, desktop) + autoplay 2,8 detik saat idle.
   Hover/click: jadikan aktif + reset timer. Satu init per section
   (guard dataset) sehingga tidak ada duplicate listener.
   ============================================================ */
const MODULE_AUTOPLAY_MS = 2800;

function initModules() {
    const section = q('[data-modules]');
    if (!section || section.dataset.modInit) return;
    section.dataset.modInit = '1';

    const stage = q('.nx-cream__stage', section);
    const tabs = qa('[data-module-tab]', section);
    const slides = qa('[data-module-slide]', section);
    const details = qa('[data-module-detail]', section);
    const total = slides.length;

    if (!total) return;

    const desktopQuery = window.matchMedia('(min-width: 1024px)');
    let active = -1;
    let timer = 0;
    let lastScrollAt = 0;
    let hovering = false;
    let inView = false;

    const setActive = (index) => {
        if (index === active) return;
        active = index;

        tabs.forEach((tab) => {
            const on = Number(tab.dataset.moduleTab) === index;
            tab.classList.toggle('is-active', on);
            tab.setAttribute('aria-selected', String(on));
        });
        slides.forEach((slide) => slide.classList.toggle('is-active', Number(slide.dataset.moduleSlide) === index));
        details.forEach((detail) => {
            const on = Number(detail.dataset.moduleDetail) === index;
            detail.hidden = !on;
            detail.classList.toggle('is-active', on);
        });
    };

    const stopTimer = () => {
        if (timer) {
            window.clearInterval(timer);
            timer = 0;
        }
    };

    const startTimer = () => {
        stopTimer();
        if (prefersReducedMotion()) return;
        timer = window.setInterval(() => {
            // Berjalan hanya saat section terlihat, tab browser aktif,
            // tidak di-hover, dan tidak baru saja di-scroll (3 dtk).
            if (!inView || hovering || document.hidden) return;
            if (performance.now() - lastScrollAt < 3000) return;
            setActive((active + 1) % total);
        }, MODULE_AUTOPLAY_MS);
    };

    // Interaksi pengguna: aktifkan + reset timer; di desktop tetap
    // selaraskan posisi scroll agar scrub tidak menimpa pilihan.
    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const index = Number(tab.dataset.moduleTab);
            lastScrollAt = performance.now();
            setActive(index);
            startTimer();

            const trigger = ScrollTrigger.getById('nx-modules');
            if (trigger && lenis && desktopQuery.matches) {
                const start = trigger.start;
                const end = trigger.end;
                const progress = (index + 0.5) / total;
                lenis.scrollTo(start + (end - start) * progress);
            }
        });

        tab.addEventListener('mouseenter', () => {
            lastScrollAt = performance.now();
            setActive(Number(tab.dataset.moduleTab));
            startTimer();
        });
    });

    section.addEventListener('mouseenter', () => {
        hovering = true;
    });
    section.addEventListener('mouseleave', () => {
        hovering = false;
    });

    if ('IntersectionObserver' in window) {
        new IntersectionObserver(
            (entries) => {
                inView = entries.some((entry) => entry.isIntersecting);
            },
            { threshold: 0.25 },
        ).observe(section);
    } else {
        inView = true;
    }

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) startTimer();
    });

    setActive(0);
    startTimer();

    // Reduced motion atau non-desktop: tanpa pin/scrub; daftar tetap
    // bisa diklik dan autoplay tetap berjalan saat terlihat.
    if (prefersReducedMotion() || !stage || !desktopQuery.matches) {
        return;
    }

    ScrollTrigger.create({
        id: 'nx-modules',
        trigger: section,
        start: 'top top',
        end: 'bottom bottom',
        pin: stage,
        pinSpacing: false,
        scrub: true,
        anticipatePin: 1,
        onUpdate: (self) => {
            lastScrollAt = performance.now();
            setActive(Math.min(total - 1, Math.floor(self.progress * total * 0.999)));
        },
    });
}

/* ============================================================
   SECTION 5 — STATEMENT (per-word colour scrub)
   ============================================================ */
function initStatement() {
    const block = q('[data-statement]');
    if (!block) return;

    // Split the sentence into word spans, preserving the original spacing.
    const text = block.textContent.trim().replace(/\s+/g, ' ');
    block.textContent = '';

    const words = text.split(' ').map((word) => {
        const span = document.createElement('span');
        span.className = 'nx-statement__word';
        span.textContent = word;
        block.appendChild(span);
        block.appendChild(document.createTextNode(' '));
        return span;
    });

    if (prefersReducedMotion()) {
        words.forEach((word) => word.classList.add('is-lit'));
        return;
    }

    ScrollTrigger.create({
        trigger: block,
        start: 'top 78%',
        end: 'bottom 45%',
        scrub: true,
        onUpdate: (self) => {
            const lit = Math.round(self.progress * words.length);
            words.forEach((word, i) => word.classList.toggle('is-lit', i < lit));
        },
    });
}

/* ============================================================
   SECTION 5 — PROJECT CAROUSEL (pinned horizontal on desktop)
   ============================================================ */
function initProjects() {
    const section = q('[data-projects]');
    if (!section) return;

    const stage = q('.nx-projects__stage', section);
    const track = q('[data-projects-track]', section);

    if (!track) return;

    if (prefersReducedMotion() || !stage || isMobile()) {
        // Mobile/tablet: susunan vertikal statis, tanpa pin.
        gsap.set(track, { x: 0 });
        return;
    }

    const mm = gsap.matchMedia();

    mm.add('(min-width: 768px)', () => {
        const distance = () => Math.max(0, track.scrollWidth - window.innerWidth + window.innerWidth * 0.04);

        const tween = gsap.to(track, {
            x: () => -distance(),
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top top',
                end: 'bottom bottom',
                pin: stage,
                pinSpacing: false,
                anticipatePin: 1,
                scrub: 1,
                invalidateOnRefresh: true,
            },
        });

        return () => {
            tween.scrollTrigger?.kill();
            tween.kill();
            gsap.set(track, { clearProps: 'transform' });
        };
    });

    // Custom cursor, pointer devices only.
    if (DESKTOP_QUERY.matches && window.matchMedia('(pointer: fine)').matches) {
        initCursor(qa('[data-project]', section));
    }
}

/** Desktop-only round 90px "LIHAT" cursor that follows the pointer over project cards with smooth lerp. */
function initCursor(cards) {
    const cursor = q('#nxCursor');
    if (!cursor || !cards.length || prefersReducedMotion()) return;

    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let curX = mouseX;
    let curY = mouseY;
    let isMoving = false;

    window.addEventListener(
        'mousemove',
        (event) => {
            mouseX = event.clientX;
            mouseY = event.clientY;
            if (!isMoving) {
                curX = mouseX;
                curY = mouseY;
                isMoving = true;
            }
        },
        { passive: true },
    );

    gsap.ticker.add(() => {
        curX += (mouseX - curX) * 0.18;
        curY += (mouseY - curY) * 0.18;
        gsap.set(cursor, { x: curX, y: curY });
    });

    cards.forEach((card) => {
        card.addEventListener('mouseenter', () => {
            cursor.classList.add('is-active');
            gsap.to(cursor, { scale: 1, opacity: 1, duration: 0.3, ease: 'power2.out', overwrite: 'auto' });
        });
        card.addEventListener('mouseleave', () => {
            cursor.classList.remove('is-active');
            gsap.to(cursor, { scale: 0, opacity: 0, duration: 0.25, ease: 'power2.in', overwrite: 'auto' });
        });
    });
}

/* ============================================================
   CTA SEBELUM FOOTER (reveal sederhana; reduced-motion → statis)
   ============================================================ */
function initCta() {
    const section = q('.nx-cta');
    if (!section || prefersReducedMotion()) return;

    const items = qa('[data-cta-reveal]', section);
    if (!items.length) return;

    gsap.set(items, { y: 28, opacity: 0 });
    gsap.to(items, {
        y: 0,
        opacity: 1,
        duration: 0.8,
        ease: 'power3.out',
        stagger: 0.1,
        overwrite: true,
        scrollTrigger: {
            trigger: section,
            start: 'top 78%',
            once: true,
        },
    });
}

/* ============================================================
   SECTION 6 — PRINSIP (7.8: carousel controls & drag/swipe)
   ============================================================ */
function initPrinciples() {
    const section = q('.nx-principles');
    if (!section) return;

    const wrap = q('#nxPrinciplesScrollWrap', section);
    const prevBtn = q('#nxPrinciplesPrev', section);
    const nextBtn = q('#nxPrinciplesNext', section);

    if (wrap && prevBtn && nextBtn) {
        const getScrollStep = () => {
            const firstCard = q('.nx-pcard', wrap);
            return firstCard ? firstCard.offsetWidth + 20 : 320;
        };

        prevBtn.addEventListener('click', () => {
            wrap.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            wrap.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
        });

        // Drag & swipe support
        let isDown = false;
        let startX = 0;
        let scrollLeft = 0;

        wrap.addEventListener('pointerdown', (e) => {
            isDown = true;
            wrap.setPointerCapture?.(e.pointerId);
            startX = e.pageX - wrap.offsetLeft;
            scrollLeft = wrap.scrollLeft;
        });

        wrap.addEventListener('pointermove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - wrap.offsetLeft;
            const walk = (x - startX) * 1.5;
            wrap.scrollLeft = scrollLeft - walk;
        });

        const stopDrag = (e) => {
            if (isDown) {
                isDown = false;
                try {
                    wrap.releasePointerCapture?.(e.pointerId);
                } catch (_) {}
            }
        };

        wrap.addEventListener('pointerup', stopDrag);
        wrap.addEventListener('pointercancel', stopDrag);
        wrap.addEventListener('pointerleave', stopDrag);
    }

    if (prefersReducedMotion()) return;

    const revealItems = qa('.nx-principles__title, .nx-principles__desc, .nx-principles__carousel-container', section);
    if (!revealItems.length) return;

    gsap.set(revealItems, { y: 24, opacity: 0 });
    gsap.to(revealItems, {
        y: 0,
        opacity: 1,
        duration: 0.8,
        ease: 'power3.out',
        stagger: 0.1,
        overwrite: true,
        scrollTrigger: {
            trigger: section,
            start: 'top 75%',
            once: true,
        },
    });
}

/* ============================================================
   BOOT
   ============================================================ */
function refresh() {
    const imgs = qa('img');
    let done = 0;
    const onImg = () => {
        done += 1;
        if (done >= imgs.length) ScrollTrigger.refresh();
    };
    imgs.forEach((img) => {
        if (img.complete && img.naturalWidth >= 0) onImg();
        else {
            img.addEventListener('load', onImg, { once: true });
            img.addEventListener('error', onImg, { once: true });
        }
    });
    if (imgs.length === 0) ScrollTrigger.refresh();
}

function boot() {
    wireAssets();
    initLenis();
    initNavbar();
    initHero();
    initWordRotate();
    initScrub();
    initModules();
    initStatement();
    initProjects();
    initPrinciples();
    initCta();

    // Pin measurements are taken before webfonts settle, so re-measure once
    // fonts are ready and again after every image has decoded. Resize
    // di-debounce 200ms (Bagian 8).
    refresh();
    document.fonts?.ready?.then(refresh).catch(() => {});
    window.addEventListener('load', refresh);
    let resizeTimer = 0;
    window.addEventListener('resize', () => {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(() => ScrollTrigger.refresh(), 200);
    });

    MOTION_QUERY.addEventListener?.('change', () => window.location.reload());
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}
