from playwright.sync_api import sync_playwright
import json

EDGE_PATH = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
with sync_playwright() as p:
    b = p.chromium.launch(executable_path=EDGE_PATH, headless=True)
    page = b.new_page(viewport={"width": 1920, "height": 972})
    page.goto("http://127.0.0.1:8000/", wait_until="networkidle")
    
    cream = page.locator("#fitur")
    cream.scroll_into_view_if_needed()
    
    data = page.evaluate("""() => {
        const cream = document.querySelector('.nx-cream');
        const stage = document.querySelector('.nx-cream__stage');
        const list = document.querySelector('.nx-modules__list');
        const frame = document.querySelector('.nx-modules__frame');
        const detail = document.querySelector('.nx-modules__detail');
        const head = document.querySelector('.nx-cream__head');
        
        return {
            scrollX: window.scrollX,
            scrollY: window.scrollY,
            list_rect: list.getBoundingClientRect(),
            frame_rect: frame.getBoundingClientRect(),
            detail_rect: detail.getBoundingClientRect(),
            head_rect: head.getBoundingClientRect(),
            stage_rect: stage.getBoundingClientRect(),
            stage_transform: window.getComputedStyle(stage).transform
        };
    }""")
    print(json.dumps(data, indent=2))
    b.close()

