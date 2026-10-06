import os
import sys
import time
import json
from playwright.sync_api import sync_playwright

DOCS_DIR = r'docs/visual-qa/hero'
os.makedirs(DOCS_DIR, exist_ok=True)

EDGE_PATH = r'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe'

VIEWPORTS = [
    {'name': '1920x972', 'width': 1920, 'height': 972},
    {'name': '1440x900', 'width': 1440, 'height': 900},
    {'name': '1366x768', 'width': 1366, 'height': 768},
    {'name': '1280x645', 'width': 1280, 'height': 645},
    {'name': '390x844',  'width': 390,  'height': 844, 'is_mobile': True},
]

def intersects(r1, r2):
    # Two rectangles intersect if they overlap in both X and Y
    overlap_x = max(0, min(r1['right'], r2['right']) - max(r1['left'], r2['left']))
    overlap_y = max(0, min(r1['bottom'], r2['bottom']) - max(r1['top'], r2['top']))
    return (overlap_x > 0 and overlap_y > 0), overlap_x * overlap_y

def run():
    results = {}
    with sync_playwright() as p:
        browser = p.chromium.launch(executable_path=EDGE_PATH, headless=True)

        for vp in VIEWPORTS:
            context = browser.new_context(viewport={'width': vp['width'], 'height': vp['height']})
            page = context.new_page()
            url = 'http://127.0.0.1:8000/'
            page.goto(url, wait_until='networkidle')
            page.wait_for_timeout(1000)

            vp_name = vp['name']
            w = vp['width']
            h = vp['height']
            is_mobile = vp.get('is_mobile', False)

            print(f'\\n=================== TESTING VIEWPORT {vp_name} ===================')

            # 1. Check document.fonts.check
            font_orbitron = page.evaluate("() => document.fonts.check('900 24px Orbitron')")
            font_inter = page.evaluate("() => document.fonts.check('500 14px Inter')")
            print(f'Font Orbitron loaded: {font_orbitron}, Font Inter loaded: {font_inter}')

            # 2. Check Horizontal Scroll
            has_no_h_scroll = page.evaluate("() => document.documentElement.scrollWidth <= window.innerWidth")
            scroll_width = page.evaluate("() => document.documentElement.scrollWidth")
            print(f'Horizontal scroll: scrollWidth={scroll_width} vs innerWidth={w} -> PASS={has_no_h_scroll}')

            # 3. Measure Headline Lines Width
            headline_metrics = page.evaluate("""() => {
                const hl = document.querySelector('.nx-hero__headline');
                const masks = Array.from(hl.querySelectorAll('.nx-line-mask'));
                const vw = window.innerWidth;
                const vh = window.innerHeight;
                return masks.map((m, idx) => {
                    const rect = m.getBoundingClientRect();
                    return {
                        lineIndex: idx + 1,
                        text: m.innerText.trim(),
                        widthPx: rect.width,
                        rightPx: rect.right,
                        widthVw: (rect.width / vw) * 100,
                        rightVw: (rect.right / vw) * 100
                    };
                });
            }""")
            max_right_vw = max(m['rightVw'] for m in headline_metrics)
            max_width_vw = max(m['widthVw'] for m in headline_metrics)
            print(f'Headline lines:')
            for m in headline_metrics:
                print(f"  Line {m['lineIndex']}: '{m['text']}' -> width: {m['widthPx']:.1f}px ({m['widthVw']:.1f}vw), right: {m['rightPx']:.1f}px ({m['rightVw']:.1f}vw)")
            print(f"Longest line right edge: {max_right_vw:.1f}vw (Target <= 66vw: {'PASS' if max_right_vw <= 66 else 'FAIL'})")

            # 4. Measure BBoxes: Robot Face vs Glass Card vs Headline vs Tagline
            boxes = page.evaluate("""() => {
                const robot = document.querySelector('.nx-hero__visual');
                const glass = document.querySelector('.nx-glass');
                const headline = document.querySelector('.nx-hero__headline');
                const tagline = document.querySelector('.nx-hero__foot');
                const rotor = document.querySelector('.nx-swap');

                const rRect = robot ? robot.getBoundingClientRect() : null;
                const gRect = glass ? glass.getBoundingClientRect() : null;
                const hRect = headline ? headline.getBoundingClientRect() : null;
                const tRect = tagline ? tagline.getBoundingClientRect() : null;
                const rotRect = rotor ? rotor.getBoundingClientRect() : null;

                // Wajah robot: x 42%-58%, y 18%-56% dari elemen visual robot
                let faceRect = null;
                if (rRect) {
                    const fLeft = rRect.left + rRect.width * 0.42;
                    const fRight = rRect.left + rRect.width * 0.58;
                    const fTop = rRect.top + rRect.height * 0.18;
                    const fBottom = rRect.top + rRect.height * 0.56;
                    faceRect = {
                        left: fLeft,
                        right: fRight,
                        top: fTop,
                        bottom: fBottom,
                        width: fRight - fLeft,
                        height: fBottom - fTop
                    };
                }

                return {
                    robot: rRect,
                    face: faceRect,
                    glass: gRect,
                    headline: hRect,
                    tagline: tRect,
                    rotor: rotRect
                };
            }""")

            face = boxes['face']
            glass = boxes['glass']
            headline = boxes['headline']
            tagline = boxes['tagline']
            rotor = boxes['rotor']

            print(f'\\nBounding Boxes (Viewport {vp_name}):')
            if face:
                print(f"  Face:     left={face['left']:.1f}, right={face['right']:.1f}, top={face['top']:.1f}, bottom={face['bottom']:.1f}")
            if glass:
                print(f"  Glass:    left={glass['left']:.1f}, right={glass['right']:.1f}, top={glass['top']:.1f}, bottom={glass['bottom']:.1f}")
            if headline:
                print(f"  Headline: left={headline['left']:.1f}, right={headline['right']:.1f}, top={headline['top']:.1f}, bottom={headline['bottom']:.1f}")
            if tagline:
                print(f"  Tagline:  left={tagline['left']:.1f}, right={tagline['right']:.1f}, top={tagline['top']:.1f}, bottom={tagline['bottom']:.1f}")
            if rotor:
                print(f"  Rotor:    left={rotor['left']:.1f}, right={rotor['right']:.1f}, top={rotor['top']:.1f}, bottom={rotor['bottom']:.1f}, width={rotor['width']:.1f}")

            # Calculate intersections on desktop
            if not is_mobile and face:
                int_face_glass, area_fg = intersects(face, glass)
                int_face_hl, area_fhl = intersects(face, headline)
                int_face_tag, area_ft = intersects(face, tagline)
                int_rotor_glass, area_rg = intersects(rotor, glass)

                print(f"Intersection Face vs Glass:   {int_face_glass} (Overlap: {area_fg:.1f}px) -> {'PASS' if not int_face_glass else 'FAIL'}")
                print(f"Intersection Face vs Headline:{int_face_hl} (Overlap: {area_fhl:.1f}px) -> {'PASS' if not int_face_hl else 'FAIL'}")
                print(f"Intersection Face vs Tagline: {int_face_tag} (Overlap: {area_ft:.1f}px) -> {'PASS' if not int_face_tag else 'FAIL'}")
                print(f"Intersection Rotor vs Glass:  {int_rotor_glass} (Overlap: {area_rg:.1f}px) -> {'PASS' if not int_rotor_glass else 'FAIL'}")

            # 5. Take standard screenshot
            shot_base_path = os.path.join(DOCS_DIR, f'hero-{vp_name}-unmasked.png')
            page.screenshot(path=shot_base_path)
            print(f'Screenshot saved: {shot_base_path}')

            # 6. Test Mask Interaction & Take Masked Screenshot
            # Trigger mask by hovering or clicking robot visual
            page.click('#nxHeroVisual')
            page.wait_for_timeout(700)
            is_masked_active = page.evaluate("() => document.querySelector('#nxHeroVisual').classList.contains('is-masked')")
            mask_opacity = page.evaluate("() => window.getComputedStyle(document.querySelector('#nxRobotMask')).opacity")
            print(f'Mask active on click/tap: {is_masked_active}, mask computed opacity: {mask_opacity}')

            shot_masked_path = os.path.join(DOCS_DIR, f'hero-{vp_name}-masked.png')
            page.screenshot(path=shot_masked_path)
            print(f'Masked screenshot saved: {shot_masked_path}')

            # 7. 10 Rotor Time Samples (for 1920x972)
            rotor_samples = []
            if vp_name == '1920x972':
                print('\\nSampling 10 rotor time intervals:')
                for i in range(10):
                    page.wait_for_timeout(600)
                    word = page.evaluate("() => document.querySelector('.nx-swap').textContent.trim()")
                    r_box = page.evaluate("() => document.querySelector('.nx-swap').getBoundingClientRect()")
                    int_rg, _ = intersects(r_box, glass)
                    rotor_samples.append({'sample': i + 1, 'word': word, 'intersects_glass': int_rg})
                    print(f"  Sample {i+1}: word='{word}', intersects_glass={int_rg}")

            results[vp_name] = {
                'font_orbitron': font_orbitron,
                'font_inter': font_inter,
                'no_h_scroll': has_no_h_scroll,
                'headline_max_right_vw': max_right_vw,
                'face_glass_intersect': int_face_glass if not is_mobile and face else False,
                'face_headline_intersect': int_face_hl if not is_mobile and face else False,
                'face_tagline_intersect': int_face_tag if not is_mobile and face else False,
                'rotor_glass_intersect': int_rotor_glass if not is_mobile and face else False,
                'mask_active': is_masked_active,
                'screenshot_unmasked': shot_base_path,
                'screenshot_masked': shot_masked_path,
                'rotor_samples': rotor_samples
            }

            context.close()
        browser.close()

    with open(os.path.join(DOCS_DIR, 'qa-results.json'), 'w', encoding='utf-8') as f:
        json.dump(results, f, indent=2)
    print('\\nQA Script Completed! Results written to docs/visual-qa/hero/qa-results.json')

if __name__ == '__main__':
    run()

