import sys
import time
import os
from playwright.sync_api import sync_playwright

def main():
    out_dir = r"C:\Users\ACER\OneDrive\lifehub-ai-v2\scratch\film_screens"
    os.makedirs(out_dir, exist_ok=True)

    errors = []

    with sync_playwright() as p:
        browser = p.chromium.launch(channel="msedge", headless=True)
        context = browser.new_context(viewport={"width": 1440, "height": 900})
        page = context.new_page()

        page.on("pageerror", lambda err: errors.append(f"PageError: {err}"))
        page.on("console", lambda msg: errors.append(f"ConsoleError: {msg.text}") if msg.type == "error" else None)

        print("Navigating to http://127.0.0.1:8000/...")
        page.goto("http://127.0.0.1:8000/", wait_until="networkidle")
        time.sleep(1)

        # Scroll to #nxScrub
        print("Scrolling to #nxScrub...")
        page.evaluate("""() => {
            const el = document.querySelector('#nxScrub');
            if (el) {
                el.scrollIntoView({ behavior: 'instant', block: 'start' });
            }
        }""")
        time.sleep(0.5)

        # Let's inspect the timeline and capture scenes
        # Scene 0 (1.5s into film)
        time.sleep(1.5)
        print("Capturing Scene 0...")
        page.screenshot(path=os.path.join(out_dir, "scene_0_desktop.png"))

        # Wait for Scene 2 (at ~4.8s total)
        time.sleep(3.3)
        print("Capturing Scene 2...")
        page.screenshot(path=os.path.join(out_dir, "scene_2_desktop.png"))

        # Wait for Scene 3 (at ~7.5s total)
        time.sleep(2.7)
        print("Capturing Scene 3...")
        page.screenshot(path=os.path.join(out_dir, "scene_3_desktop.png"))

        # Wait for Scene 4 (at ~10.5s total)
        time.sleep(3.0)
        print("Capturing Scene 4...")
        page.screenshot(path=os.path.join(out_dir, "scene_4_desktop.png"))

        # Wait for Scene 5 (at ~14.5s total)
        time.sleep(4.0)
        print("Capturing Scene 5...")
        page.screenshot(path=os.path.join(out_dir, "scene_5_desktop.png"))

        # Check card metrics on desktop
        desktop_metrics = page.evaluate("""() => {
            const card = document.querySelector('#nxScrubCard');
            const rect = card ? card.getBoundingClientRect() : null;
            const bg = card ? getComputedStyle(card).backgroundColor : null;
            return { rect, bg };
        }""")
        print("Desktop metrics:", desktop_metrics)

        # Mobile viewport test
        print("Testing Mobile viewport (390x844)...")
        mobile_context = browser.new_context(viewport={"width": 390, "height": 844})
        mobile_page = mobile_context.new_page()
        mobile_page.goto("http://127.0.0.1:8000/", wait_until="networkidle")
        mobile_page.evaluate("""() => {
            const el = document.querySelector('#nxScrub');
            if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
        }""")
        time.sleep(2)
        mobile_page.screenshot(path=os.path.join(out_dir, "scene_mobile.png"))

        mobile_metrics = mobile_page.evaluate("""() => {
            const card = document.querySelector('#nxScrubCard');
            const rect = card ? card.getBoundingClientRect() : null;
            const docWidth = document.documentElement.clientWidth;
            const scrollWidth = document.documentElement.scrollWidth;
            return { rect, docWidth, scrollWidth, overflow: scrollWidth > docWidth };
        }""")
        print("Mobile metrics:", mobile_metrics)

        browser.close()

    print(f"Total Errors: {len(errors)}")
    for err in errors:
        print("ERR:", err)

if __name__ == "__main__":
    main()

