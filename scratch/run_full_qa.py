import os
import sys
import time
import json
from playwright.sync_api import sync_playwright

HERO_DIR = r'docs/visual-qa/hero'
PINNED_DIR = r'docs/visual-qa/pinned'
os.makedirs(HERO_DIR, exist_ok=True)
os.makedirs(PINNED_DIR, exist_ok=True)

EDGE_PATH = r'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe'

VIEWPORTS_HERO = [
    {'name': '1920x972', 'width': 1920, 'height': 972},
    {'name': '1440x900', 'width': 1440, 'height': 900},
    {'name': '1366x768', 'width': 1366, 'height': 768},
    {'name': '1280x645', 'width': 1280, 'height': 645},
    {'name': '390x844',  'width': 390,  'height': 844, 'is_mobile': True},
]

def intersects(r1, r2):
    if not r1 or not r2:
        return False, 0.0
    overlap_x = max(0, min(r1['right'], r2['right']) - max(r1['left'], r2['left']))
    overlap_y = max(0, min(r1['bottom'], r2['bottom']) - max(r1['top'], r2['top']))
    return (overlap_x > 0 and overlap_y > 0), float(overlap_x * overlap_y)

def test_hero(browser):
    hero_results = {}
    print('\n======================================================')
    print('          PHASE 1: TESTING HERO SECTION')
    print('======================================================')

    for vp in VIEWPORTS_HERO:
        context = browser.new_context(viewport={'width': vp['width'], 'height': vp['height']})
        page = context.new_page()
        page.goto('http://127.0.0.1:8000/', wait_until='networkidle')
        page.wait_for_timeout(800)

        vp_name = vp['name']
        w = vp['width']
        h = vp['height']
        is_mobile = vp.get('is_mobile', False)

        print(f'\n--- VIEWPORT {vp_name} ---')

        # 1. Check fonts
        font_orbitron = page.evaluate("() => document.fonts.check('900 24px Orbitron')")
        font_outfit = page.evaluate("() => document.fonts.check('900 24px Outfit')")
        font_inter = page.evaluate("() => document.fonts.check('500 14px Inter')")
        print(f'Fonts -> Orbitron: {font_orbitron}, Outfit: {font_outfit}, Inter: {font_inter}')

        # 2. Check Horizontal Scroll
        scroll_width = page.evaluate("() => document.documentElement.scrollWidth")
        no_h_scroll = (scroll_width <= w)
        print(f'No horizontal scroll: scrollWidth={scroll_width} vs innerWidth={w} -> {no_h_scroll}')

        # 3. Measure Headline Lines
        headline_metrics = page.evaluate("""() => {
            const hl = document.querySelector('.nx-hero__headline');
            const inners = Array.from(hl.querySelectorAll('.nx-line-inner'));
            const vw = window.innerWidth;
            return inners.map((inn, idx) => {
                const rect = inn.getBoundingClientRect();
                return {
                    lineIndex: idx + 1,
                    text: inn.innerText.trim(),
                    widthPx: rect.width,
                    rightPx: rect.right,
                    rightVw: (rect.right / vw) * 100
                };
            });
        }""")
        max_right_vw = max(m['rightVw'] for m in headline_metrics)
        for m in headline_metrics:
            print(f"  Line {m['lineIndex']}: '{m['text']}' -> right={m['rightPx']:.1f}px ({m['rightVw']:.1f}vw)")
        hl_pass = (max_right_vw <= 66.0) if not is_mobile else True
        print(f"Headline longest line right edge: {max_right_vw:.1f}vw <= 66vw -> {'PASS' if hl_pass else 'N/A (mobile)'}")

        # 4. Measure BBoxes & Intersections
        boxes = page.evaluate("""() => {
            const robot = document.querySelector('.nx-hero__visual');
            const glass = document.querySelector('.nx-glass');
            const headline = document.querySelector('.nx-hero__headline');
            const tagline = document.querySelector('.nx-hero__foot');
            const rotor = document.querySelector('.nx-swap');

            const rRect = robot ? robot.getBoundingClientRect() : null;
            let faceRect = null;
            if (rRect) {
                const fLeft = rRect.left + rRect.width * 0.42;
                const fRight = rRect.left + rRect.width * 0.58;
                const fTop = rRect.top + rRect.height * 0.18;
                const fBottom = rRect.top + rRect.height * 0.56;
                faceRect = { left: fLeft, right: fRight, top: fTop, bottom: fBottom, width: fRight - fLeft, height: fBottom - fTop };
            }

            return {
                robot: rRect,
                face: faceRect,
                glass: glass ? glass.getBoundingClientRect() : null,
                headline: headline ? headline.getBoundingClientRect() : null,
                tagline: tagline ? tagline.getBoundingClientRect() : null,
                rotor: rotor ? rotor.getBoundingClientRect() : null
            };
        }""")

        face = boxes['face']
        glass = boxes['glass']
        headline = boxes['headline']
        tagline = boxes['tagline']
        rotor = boxes['rotor']

        int_face_glass = False
        int_face_hl = False
        int_face_tag = False
        int_rotor_glass = False

        if not is_mobile and face:
            int_face_glass, a_fg = intersects(face, glass)
            int_face_hl, a_fhl = intersects(face, headline)
            int_face_tag, a_ft = intersects(face, tagline)
            int_rotor_glass, a_rg = intersects(rotor, glass)

            print(f"Intersections:")
            print(f"  Face vs Glass Card: {int_face_glass} (area: {a_fg:.1f}px) -> {'PASS' if not int_face_glass else 'FAIL'}")
            print(f"  Face vs Headline:   {int_face_hl} (area: {a_fhl:.1f}px) -> {'PASS' if not int_face_hl else 'FAIL'}")
            print(f"  Face vs Tagline:    {int_face_tag} (area: {a_ft:.1f}px) -> {'PASS' if not int_face_tag else 'FAIL'}")
            print(f"  Rotor vs Glass:     {int_rotor_glass} (area: {a_rg:.1f}px) -> {'PASS' if not int_rotor_glass else 'FAIL'}")

        # 5. Screenshot unmasked
        shot_unmasked = os.path.join(HERO_DIR, f"hero-{vp_name}-unmasked.png")
        page.screenshot(path=shot_unmasked)
        print(f"Screenshot unmasked: {shot_unmasked}")

        # 6. Trigger Mask (set is-masked)
        page.evaluate("() => { const v = document.querySelector('#nxHeroVisual'); if (v) v.classList.add('is-masked'); }")
        page.wait_for_timeout(700)
        shot_masked = os.path.join(HERO_DIR, f"hero-{vp_name}-masked.png")
        page.screenshot(path=shot_masked)
        print(f"Screenshot masked: {shot_masked}")

        # 7. 10 Rotor samples on 1920x972
        rotor_samples = []
        if vp_name == '1920x972':
            print('\n10 Rotor samples (every 500ms):')
            for i in range(10):
                page.wait_for_timeout(500)
                word = page.evaluate("() => document.querySelector('.nx-swap').textContent.trim()")
                r_box = page.evaluate("() => document.querySelector('.nx-swap').getBoundingClientRect()")
                i_rg, _ = intersects(r_box, glass)
                rotor_samples.append({'sample': i + 1, 'word': word, 'intersects_glass': i_rg})
                print(f"  [{i+1}/10] Word: '{word}', Overlaps Glass: {i_rg}")

        hero_results[vp_name] = {
            'font_orbitron': font_orbitron,
            'font_inter': font_inter,
            'no_h_scroll': no_h_scroll,
            'longest_line_right_vw': max_right_vw,
            'face_glass_intersect': int_face_glass,
            'face_headline_intersect': int_face_hl,
            'face_tagline_intersect': int_face_tag,
            'rotor_glass_intersect': int_rotor_glass,
            'shot_unmasked': shot_unmasked,
            'shot_masked': shot_masked,
            'rotor_samples': rotor_samples
        }
        context.close()

    return hero_results

def test_pinned(browser):
    print('\n======================================================')
    print('      PHASE 2: TESTING PINNED SECTION (SCRUB + MINI FILM)')
    print('======================================================')

    pinned_results = {}
    context = browser.new_context(viewport={'width': 1920, 'height': 972})
    page = context.new_page()
    page.goto('http://127.0.0.1:8000/', wait_until='networkidle')
    page.wait_for_timeout(800)

    scrub_top = page.evaluate("() => document.querySelector('#nxScrub').offsetTop")
    scrub_height = page.evaluate("() => document.querySelector('#nxScrub').offsetHeight")
    print(f"Pinned Section: top={scrub_top}px, height={scrub_height}px")

    # 1. Test Scroll progress: 0%, 25%, 50%, 75%, 100%
    scroll_progs = [0, 0.25, 0.50, 0.75, 1.0]
    scroll_data = []

    for p in scroll_progs:
        target_y = scrub_top + (scrub_height - 972) * p
        page.evaluate(f"window.scrollTo(0, {target_y})")
        page.wait_for_timeout(700)

        metrics = page.evaluate("""() => {
            const card = document.querySelector('#nxScrubCard');
            const left = document.querySelector('#nxScrubLeft');
            const right = document.querySelector('#nxScrubRight');
            const stage = document.querySelector('#nxScrubStage');
            const section = document.querySelector('#nxScrub');

            const cRect = card.getBoundingClientRect();
            const lRect = left.getBoundingClientRect();
            const rRect = right.getBoundingClientRect();
            const vw = window.innerWidth;
            const vh = window.innerHeight;

            return {
                cardWidthVw: (cRect.width / vw) * 100,
                cardHeightVh: (cRect.height / vh) * 100,
                cardRect: cRect,
                leftRect: lRect,
                rightRect: rRect,
                bgColor: window.getComputedStyle(section).backgroundColor
            };
        }""")

        c_box = metrics['cardRect']
        l_box = metrics['leftRect']
        r_box = metrics['rightRect']

        int_lc, a_lc = intersects(l_box, c_box)
        int_rc, a_rc = intersects(r_box, c_box)

        pct_val = int(p * 100)
        shot_p_path = os.path.join(PINNED_DIR, f"pinned-scroll-{pct_val}pct.png")
        page.screenshot(path=shot_p_path)

        print(f"Scroll {pct_val}% -> Card: {metrics['cardWidthVw']:.1f}vw x {metrics['cardHeightVh']:.1f}vh, Bg: {metrics['bgColor']}")
        print(f"  Overlap Left vs Card:  {int_lc} (area: {a_lc:.1f}px)")
        print(f"  Overlap Right vs Card: {int_rc} (area: {a_rc:.1f}px)")
        print(f"  Screenshot: {shot_p_path}")

        scroll_data.append({
            'progress': p,
            'cardWidthVw': metrics['cardWidthVw'],
            'cardHeightVh': metrics['cardHeightVh'],
            'bgColor': metrics['bgColor'],
            'int_left_card': int_lc,
            'int_right_card': int_rc,
            'shot': shot_p_path
        })

    # 2. Test Mini Film Scenes (1 to 8)
    page.evaluate(f"window.scrollTo(0, {scrub_top + 600})")
    page.wait_for_timeout(500)

    print('\nTesting Mini Film Scenes (1 to 8):')
    scene_shots = []
    scenes = [
        {'id': '#nxScene1', 'num': 1, 'name': 'Scene 1: Riak gelombang & Setiap kerja dimulai'},
        {'id': '#nxScene2', 'num': 2, 'name': 'Scene 2: Disolusi piksel & ruang kosong'},
        {'id': '#nxScene3', 'num': 3, 'name': 'Scene 3: Tempat sampah gooey & tetesan'},
        {'id': '#nxScene46', 'num': 4, 'name': 'Scene 4-6: Pill jatuh & kerucut cahaya'},
        {'id': '#nxScene7', 'num': 7, 'name': 'Scene 7: Kejelasan masuk & highlight'},
        {'id': '#nxScene8', 'num': 8, 'name': 'Scene 8: Orbit bola kaca & kata berganti'}
    ]

    for sc in scenes:
        sc_id = sc['id']
        page.evaluate(f"""() => {{
            document.querySelectorAll('.nx-film__scene').forEach(s => s.classList.remove('is-active'));
            const target = document.querySelector('{sc_id}');
            if (target) {{
                target.classList.add('is-active');
                target.style.opacity = '1';
                target.style.visibility = 'visible';
            }}
            if ('{sc_id}' === '#nxScene1') {{
                const r = document.querySelector('.nx-film__ripple.r-1');
                if (r) r.style.transform = 'scale(1.8)';
            }}
            if ('{sc_id}' === '#nxScene2') {{
                const wc = document.querySelector('#nxWhiteCard');
                if (wc) {{ wc.style.opacity = '1'; wc.style.filter = 'none'; }}
            }}
            if ('{sc_id}' === '#nxScene3') {{
                const tb = document.querySelector('#nxTrashBtn');
                if (tb) tb.style.transform = 'translateY(0)';
            }}
            if ('{sc_id}' === '#nxScene46') {{
                const p = document.querySelector('#nxDropPill1');
                if (p) p.style.opacity = '1';
            }}
            if ('{sc_id}' === '#nxScene7') {{
                const hl = document.querySelector('#nxHighlight');
                if (hl) hl.classList.add('is-lit');
            }}
        }}""")
        page.wait_for_timeout(350)
        s_num = sc['num']
        shot_path = os.path.join(PINNED_DIR, f"scene-{s_num}.png")
        page.screenshot(path=shot_path)
        print(f"Captured {sc['name']} -> {shot_path}")
        scene_shots.append({'num': sc['num'], 'name': sc['name'], 'shot': shot_path})

    pinned_results['scroll'] = scroll_data
    pinned_results['scenes'] = scene_shots
    context.close()
    return pinned_results

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(executable_path=EDGE_PATH, headless=True)
        hero_res = test_hero(browser)
        pinned_res = test_pinned(browser)
        browser.close()

    summary = {
        'hero': hero_res,
        'pinned': pinned_res
    }
    with open('docs/visual-qa/qa-summary.json', 'w', encoding='utf-8') as f:
        json.dump(summary, f, indent=2)
    print('\n======================================================')
    print('ALL VISUAL QA TESTS COMPLETED!')
    print('Summary saved to docs/visual-qa/qa-summary.json')
    print('======================================================')

if __name__ == '__main__':
    run()

