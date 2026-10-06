import os
import json
import time
from playwright.sync_api import sync_playwright

EDGE_PATH = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
BASE_URL = "http://127.0.0.1:8000/"

DOCS_DIR = os.path.abspath("docs/visual-qa")
os.makedirs(os.path.join(DOCS_DIR, "hero"), exist_ok=True)
os.makedirs(os.path.join(DOCS_DIR, "pinned"), exist_ok=True)
os.makedirs(os.path.join(DOCS_DIR, "sections"), exist_ok=True)
os.makedirs(os.path.join(DOCS_DIR, "mobile"), exist_ok=True)

results = {}

with sync_playwright() as p:
    browser = p.chromium.launch(
        executable_path=EDGE_PATH,
        headless=True,
        args=["--disable-web-security", "--no-sandbox"]
    )
    
    # =========================================================================
    # 1. DESKTOP 1920x972
    # =========================================================================
    context = browser.new_context(viewport={"width": 1920, "height": 972})
    page = context.new_page()
    page.goto(BASE_URL, wait_until="networkidle")
    time.sleep(1.5)

    # 1.1 Hero Section
    page.screenshot(path=os.path.join(DOCS_DIR, "hero", "hero-1920x972.png"))

    # 1.2 Marquee
    marquee = page.locator(".nx-marquee")
    if marquee.count() > 0:
        marquee.scroll_into_view_if_needed()
        time.sleep(0.5)
        page.screenshot(path=os.path.join(DOCS_DIR, "sections", "marquee-1920x972.png"))
        results["marquee_has_icons"] = page.evaluate("() => document.querySelectorAll('.nx-marquee__icon').length > 0")

    # 1.3 Modul Section
    cream = page.locator("#fitur")
    cream.scroll_into_view_if_needed()
    time.sleep(0.8)
    page.screenshot(path=os.path.join(DOCS_DIR, "sections", "modul-1920x972.png"))

    mod_data = page.evaluate("""() => {
        const head = document.querySelector('.nx-cream__head');
        const frame = document.querySelector('.nx-modules__frame');
        const list = document.querySelector('.nx-modules__list');
        const detail = document.querySelector('.nx-modules__detail');
        const tabs = Array.from(document.querySelectorAll('.nx-modules__item')).map(t => t.textContent.trim());

        const hRect = head.getBoundingClientRect();
        const fRect = frame.getBoundingClientRect();
        const lRect = list.getBoundingClientRect();
        const dRect = detail.getBoundingClientRect();

        const vw = window.innerWidth;
        const vh = window.innerHeight;

        return {
            heading_text: head.textContent.trim(),
            heading_mb_vh: (fRect.top - hRect.bottom) / vh * 100,
            tabs: tabs,
            list_x_vw: (lRect.left / vw) * 100,
            frame_x_start_vw: (fRect.left / vw) * 100,
            frame_x_end_vw: (fRect.right / vw) * 100,
            frame_w_vw: (fRect.width / vw) * 100,
            frame_h_vh: (fRect.height / vh) * 100,
            frame_ratio: (fRect.width / fRect.height).toFixed(2),
            detail_x_vw: (dRect.left / vw) * 100
        };
    }""")
    results["modul"] = mod_data

    # Screenshot each module tab
    tab_count = page.locator(".nx-modules__item").count()
    for idx in range(tab_count):
        tab = page.locator(f".nx-modules__item[data-module-tab='{idx}']")
        tab.click()
        time.sleep(0.5)
        page.screenshot(path=os.path.join(DOCS_DIR, "sections", f"modul-tab-{idx}-1920x972.png"))

    # 1.4 Statement Section
    statement = page.locator(".nx-statement")
    statement.scroll_into_view_if_needed()
    time.sleep(0.5)
    page.screenshot(path=os.path.join(DOCS_DIR, "sections", "statement-1920x972.png"))
    stmt_text = page.locator(".nx-statement__text").text_content()
    results["statement"] = {
        "text": stmt_text.strip(),
        "has_10_plus": "10+" in stmt_text
    }

    # 1.5 Proyek Section
    proyek = page.locator("#proyek")
    proyek.scroll_into_view_if_needed()
    time.sleep(0.8)
    page.screenshot(path=os.path.join(DOCS_DIR, "sections", "proyek-1920x972.png"))

    proj_data = page.evaluate("""() => {
        const cards = Array.from(document.querySelectorAll('.nx-project'));
        const vw = window.innerWidth;
        const card0 = cards[0];
        const media = card0.querySelector('.nx-project__media');
        const cat = card0.querySelector('.nx-project__cat');

        const cRect = card0.getBoundingClientRect();
        const mRect = media.getBoundingClientRect();
        const catStyle = window.getComputedStyle(cat);

        return {
            card_w_vw: (cRect.width / vw) * 100,
            media_ratio: (mRect.width / mRect.height).toFixed(2),
            cat_fontsize: catStyle.fontSize,
            cat_text_transform: catStyle.textTransform,
            cat_text: cat.textContent.trim()
        };
    }""")
    results["proyek"] = proj_data

    # Test Proyek Cursor
    first_card = page.locator(".nx-project").first
    card_box = first_card.bounding_box()
    if card_box:
        page.mouse.move(card_box["x"] + card_box["width"] / 2, card_box["y"] + card_box["height"] / 2)
        time.sleep(0.4)
        cursor_data = page.evaluate("""() => {
            const cursor = document.querySelector('#nxCursor');
            const rect = cursor.getBoundingClientRect();
            const style = window.getComputedStyle(cursor);
            return {
                width: rect.width,
                height: rect.height,
                text: cursor.textContent.trim(),
                opacity: style.opacity,
                bg: style.backgroundColor
            };
        }""")
        results["cursor"] = cursor_data
        page.screenshot(path=os.path.join(DOCS_DIR, "sections", "proyek-cursor-hover-1920x972.png"))

    # 1.6 Prinsip Section
    prinsip = page.locator("#prinsip")
    prinsip.scroll_into_view_if_needed()
    time.sleep(0.8)
    page.screenshot(path=os.path.join(DOCS_DIR, "sections", "prinsip-1920x972.png"))

    prinsip_data = page.evaluate("""() => {
        const head = document.querySelector('.nx-principles__title');
        const sticky = document.querySelector('#nxPrinciplesSticky');
        const wrap = document.querySelector('#nxPrinciplesScrollWrap');
        const cards = Array.from(document.querySelectorAll('.nx-pcard'));
        const vw = window.innerWidth;

        const sRect = sticky.getBoundingClientRect();
        const c0Rect = cards[0] ? cards[0].getBoundingClientRect() : null;

        return {
            heading_text: head.textContent.trim(),
            sticky_title: sticky.querySelector('.nx-psticky__title').textContent.trim(),
            sticky_sub: sticky.querySelector('.nx-psticky__sub').textContent.trim(),
            sticky_w_vw: (sRect.width / vw) * 100,
            cards_count: cards.length,
            card0_w_vw: c0Rect ? (c0Rect.width / vw) * 100 : null,
            card0_bg: cards[0] ? window.getComputedStyle(cards[0]).backgroundColor : null,
            has_nav_arrows: document.querySelectorAll('.nx-pnav__btn').length === 2
        };
    }""")
    results["prinsip"] = prinsip_data

    # Test Arrow Click
    next_btn = page.locator("#nxPrinciplesNext")
    if next_btn.count() > 0:
        scroll_before = page.evaluate("() => document.querySelector('#nxPrinciplesScrollWrap').scrollLeft")
        next_btn.click()
        time.sleep(0.5)
        scroll_after = page.evaluate("() => document.querySelector('#nxPrinciplesScrollWrap').scrollLeft")
        results["prinsip"]["arrow_scroll_delta"] = scroll_after - scroll_before

    # 1.7 CTA Section
    cta = page.locator(".nx-cta")
    cta.scroll_into_view_if_needed()
    time.sleep(0.5)
    page.screenshot(path=os.path.join(DOCS_DIR, "sections", "cta-1920x972.png"))

    # 1.8 Footer Section
    footer = page.locator(".nx-footer")
    footer.scroll_into_view_if_needed()
    time.sleep(0.8)
    page.screenshot(path=os.path.join(DOCS_DIR, "sections", "footer-1920x972.png"))

    ftr_data = page.evaluate("""() => {
        const ftr = document.querySelector('.nx-footer');
        const giant = ftr.querySelector('.nx-footer__giant');
        const gWrap = ftr.querySelector('.nx-footer__giantwrap');
        const curve = ftr.querySelector('.nx-footer__abstract-curve');
        const links = Array.from(ftr.querySelectorAll('.nx-footer__list a')).map(a => a.textContent.trim());
        const totop = ftr.querySelector('.nx-totop');

        const gRect = giant.getBoundingClientRect();
        const wRect = gWrap.getBoundingClientRect();

        return {
            links: links,
            totop_text: totop ? totop.textContent.trim() : null,
            giant_text: giant.textContent.trim(),
            giant_w: gRect.width,
            wrap_w: wRect.width,
            giant_clipped_sides: gRect.width > window.innerWidth,
            has_curve_svg: curve !== null && curve.querySelectorAll('svg').length > 0,
            has_team_nexus: ftr.textContent.includes('Dibuat oleh Team NEXUS')
        };
    }""")
    results["footer"] = ftr_data

    # Check horizontal scroll on page
    h_scroll = page.evaluate("() => document.documentElement.scrollWidth <= window.innerWidth")
    results["page_no_h_scroll_desktop"] = h_scroll

    context.close()

    # =========================================================================
    # 2. MOBILE 390x844
    # =========================================================================
    m_ctx = browser.new_context(
        viewport={"width": 390, "height": 844},
        is_mobile=True,
        has_touch=True
    )
    m_page = m_ctx.new_page()
    m_page.goto(BASE_URL, wait_until="networkidle")
    time.sleep(1.5)

    m_page.screenshot(path=os.path.join(DOCS_DIR, "mobile", "hero-390x844.png"))

    m_mod = m_page.locator("#fitur")
    m_mod.scroll_into_view_if_needed()
    time.sleep(0.5)
    m_page.screenshot(path=os.path.join(DOCS_DIR, "mobile", "modul-390x844.png"))

    m_proj = m_page.locator("#proyek")
    m_proj.scroll_into_view_if_needed()
    time.sleep(0.5)
    m_page.screenshot(path=os.path.join(DOCS_DIR, "mobile", "proyek-390x844.png"))

    m_prin = m_page.locator("#prinsip")
    m_prin.scroll_into_view_if_needed()
    time.sleep(0.5)
    m_page.screenshot(path=os.path.join(DOCS_DIR, "mobile", "prinsip-390x844.png"))

    m_ftr = m_page.locator(".nx-footer")
    m_ftr.scroll_into_view_if_needed()
    time.sleep(0.5)
    m_page.screenshot(path=os.path.join(DOCS_DIR, "mobile", "footer-390x844.png"))

    m_h_scroll = m_page.evaluate("() => document.documentElement.scrollWidth <= window.innerWidth + 2")
    results["page_no_h_scroll_mobile"] = m_h_scroll

    m_ctx.close()
    browser.close()

with open(os.path.join(DOCS_DIR, "qa-summary-stage2.json"), "w", encoding="utf-8") as f:
    json.dump(results, f, indent=2, ensure_ascii=False)

print("QA execution complete.")
print(json.dumps(results, indent=2, ensure_ascii=False))

