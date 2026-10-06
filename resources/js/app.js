import { Html5Qrcode, Html5QrcodeSupportedFormats } from 'html5-qrcode';

window.Html5Qrcode = Html5Qrcode;
window.Html5QrcodeSupportedFormats = Html5QrcodeSupportedFormats;

document.addEventListener('DOMContentLoaded', () => {
    // ============================================================
    // Opening Sequence Orchestration (Center-to-Navbar Travel)
    // ============================================================
    const intro = document.getElementById('brand-intro');
    const loginApp = document.getElementById('login-app');
    const travelingBrand = document.getElementById('traveling-brand');
    const targetNavBrand = document.getElementById('target-navbar-brand');

    if (intro && loginApp && travelingBrand && targetNavBrand) {
        const isReturning = sessionStorage.getItem('lifehub_intro_seen') === '1';
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (isReturning || prefersReducedMotion) {
            // Immediate presentation for returning users or reduced motion
            intro.style.display = 'none';
            intro.classList.add('pointer-events-none', 'invisible', 'opacity-0');
            loginApp.classList.remove('opacity-0', 'translate-y-3');
            loginApp.classList.add('opacity-100', 'translate-y-0');
            targetNavBrand.style.visibility = 'visible';
            targetNavBrand.style.opacity = '1';
        } else {
            // Stage 1: Initial center reveal (0.0s - 0.5s)
            const dot = document.getElementById('intro-dot');
            const markBox = document.getElementById('intro-mark-box');

            setTimeout(() => {
                if (dot) dot.classList.add('dot-appear');
            }, 100);

            setTimeout(() => {
                if (markBox) markBox.classList.add('stage-2-active');
            }, 260);

            // Stage 2: Brand Reveal (0.5s - 1.1s)
            setTimeout(() => {
                const typo = document.getElementById('intro-typography');
                if (typo) typo.classList.add('stage-3-active');
            }, 550);

            // Stage 3: Logo Travel Transition (1.1s - 2.5s)
            setTimeout(() => {
                // Compute exact destination coordinates dynamically for any screen resolution
                const targetRect = targetNavBrand.getBoundingClientRect();
                const travelRect = travelingBrand.getBoundingClientRect();

                const deltaX = targetRect.left - travelRect.left;
                const deltaY = targetRect.top - travelRect.top;
                const scale = targetRect.height / travelRect.height;

                // Travel transition with cubic-bezier(0.22, 1, 0.36, 1)
                travelingBrand.style.transition = 'transform 1.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.3s ease 1.35s';
                travelingBrand.style.transformOrigin = 'top left';
                travelingBrand.style.transform = `translate3d(${deltaX}px, ${deltaY}px, 0) scale(${scale})`;

                // Gradually transition background from black to transparent
                intro.style.transition = 'background-color 1.25s cubic-bezier(0.22, 1, 0.36, 1)';
                intro.style.backgroundColor = 'transparent';
            }, 1100);

            // Stage 4: Login Interface Reveal (2.1s - 2.5s)
            setTimeout(() => {
                loginApp.classList.remove('opacity-0', 'translate-y-3');
                loginApp.classList.add('opacity-100', 'translate-y-0');
            }, 2100);

            // Stage 5: Arrival & Seamless Handoff (2.5s)
            setTimeout(() => {
                targetNavBrand.style.visibility = 'visible';
                targetNavBrand.style.opacity = '1';
                travelingBrand.style.opacity = '0';
            }, 2500);

            // Final completion & interactivity (2.8s)
            setTimeout(() => {
                intro.style.display = 'none';
                intro.classList.add('pointer-events-none', 'invisible');
                sessionStorage.setItem('lifehub_intro_seen', '1');

                // Auto-focus on desktop
                if (window.innerWidth >= 1024) {
                    const loginInput = document.getElementById('login');
                    if (loginInput) loginInput.focus();
                }
            }, 2800);
        }
    }

    // ============================================================
    // Password toggle
    // ============================================================
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = button.getAttribute('data-toggle-password');
            const input = document.getElementById(targetId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const eyeOpen = button.querySelector('.icon-eye');
            const eyeClosed = button.querySelector('.icon-eye-off');

            if (eyeOpen && eyeClosed) {
                eyeOpen.classList.toggle('hidden', isPassword);
                eyeClosed.classList.toggle('hidden', !isPassword);
            }
        });
    });

    // ============================================================
    // Form submit loading state (prevent layout shifting)
    // ============================================================
    document.querySelectorAll('form[data-auth-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (!submitBtn || form.dataset.submitting === 'true') return;

            form.dataset.submitting = 'true';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-90', 'pointer-events-none');

            const btnText = submitBtn.querySelector('.btn-text');
            const btnSpinner = submitBtn.querySelector('.btn-spinner');

            if (btnText && btnSpinner) {
                btnText.classList.add('hidden');
                btnSpinner.classList.remove('hidden');
            }
        });
    });

    // ============================================================
    // Mobile nav toggle
    // ============================================================
    const mobileMenuBtn = document.querySelector('[data-mobile-menu-btn]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            const isExpanded = mobileMenuBtn.getAttribute('aria-expanded') === 'true';
            mobileMenuBtn.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
    }

    // ============================================================
    // Subtle parallax effect on desktop only
    // ============================================================
    const parallaxContainer = document.querySelector('[data-parallax-container]');
    const parallaxLayers = document.querySelectorAll('[data-parallax-depth]');

    if (parallaxContainer && parallaxLayers.length > 0) {
        const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (!motionQuery.matches && window.innerWidth >= 1024) {
            let mouseX = 0;
            let mouseY = 0;
            let currentX = 0;
            let currentY = 0;
            let ticking = false;

            window.addEventListener('mousemove', (e) => {
                const { innerWidth, innerHeight } = window;
                mouseX = (e.clientX / innerWidth - 0.5) * 2;
                mouseY = (e.clientY / innerHeight - 0.5) * 2;

                if (!ticking) {
                    requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            });

            function updateParallax() {
                currentX += (mouseX - currentX) * 0.05;
                currentY += (mouseY - currentY) * 0.05;

                parallaxLayers.forEach((layer) => {
                    const depth = parseFloat(layer.getAttribute('data-parallax-depth') || '15');
                    const moveX = currentX * depth;
                    const moveY = currentY * depth;
                    layer.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
                });

                if (Math.abs(mouseX - currentX) > 0.001 || Math.abs(mouseY - currentY) > 0.001) {
                    requestAnimationFrame(updateParallax);
                } else {
                    ticking = false;
                }
            }
        }
    }
});
