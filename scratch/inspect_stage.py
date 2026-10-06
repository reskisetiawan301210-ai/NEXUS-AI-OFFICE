from playwright.sync_api import sync_playwright
import json

EDGE_PATH = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
with sync_playwright() as p:
    b = p.chromium.launch(executable_path=EDGE_PATH, headless=True)
    page = b.new_page(viewport={"width": 1920, "height": 972})
    page.goto("http://127.0.0.1:8000/", wait_until="networkidle")
    data = page.evaluate("""() => {
        const cream = document.querySelector('.nx-cream');
        const stage = document.querySelector('.nx-cream__stage');
        const modules = document.querySelector('.nx-modules');
        const list = document.querySelector('.nx-modules__list');
        const head = document.querySelector('.nx-cream__head');
        
        return {
            cream_rect: cream.getBoundingClientRect(),
            cream_pad: window.getComputedStyle(cream).padding,
            stage_rect: stage.getBoundingClientRect(),
            stage_pad: window.getComputedStyle(stage).padding,
            modules_rect: modules.getBoundingClientRect(),
            list_rect: list.getBoundingClientRect(),
            head_rect: head.getBoundingClientRect(),
            stage_classes: stage.className,
            cream_classes: cream.className
        };
    }""")
    print(json.dumps(data, indent=2))
    b.close()

