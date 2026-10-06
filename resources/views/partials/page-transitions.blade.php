<script>
    /**
     * Cross-page transitions.
     *
     * Marks the outgoing view as leaving, then performs the real navigation.
     * Progressive enhancement only: any condition we are not sure about falls
     * straight through to the browser, so back button, direct URLs, auth
     * guards and route behaviour are untouched.
     */
    (function () {
        'use strict';

        var LEAVE_MS = 160;
        var body = document.body;
        if (!body) { return; }

        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var leaving = false;

        function isSameAppLink(anchor, event) {
            // Let the browser own modified clicks (new tab/window, download, etc.).
            if (event.defaultPrevented) { return false; }
            if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) { return false; }

            var target = anchor.getAttribute('target');
            if (target && target !== '_self') { return false; }
            if (anchor.hasAttribute('download')) { return false; }
            if (anchor.hasAttribute('data-no-transition')) { return false; }
            if (anchor.getAttribute('rel') === 'external') { return false; }

            var href = anchor.getAttribute('href');
            if (!href || href.charAt(0) === '#') { return false; }

            // Only plain http(s) links to this origin.
            var url;
            try {
                url = new URL(anchor.href, window.location.href);
            } catch (e) {
                return false;
            }
            if (url.origin !== window.location.origin) { return false; }
            if (url.protocol !== 'http:' && url.protocol !== 'https:') { return false; }
            if (url.pathname === window.location.pathname && url.search === window.location.search) { return false; }

            return true;
        }

        function navigate(anchor) {
            try {
                window.location.assign(anchor.href);
            } catch (e) {
                window.location.href = anchor.href;
            }
        }

        document.addEventListener('click', function (event) {
            if (leaving) { return; }

            var anchor = event.target.closest ? event.target.closest('a[href]') : null;
            if (!anchor || !isSameAppLink(anchor, event)) { return; }

            event.preventDefault();
            leaving = true;

            // No motion requested, or nothing to animate: navigate at once.
            if (reduced || !body.classList.contains('page-transition-ready')) {
                navigate(anchor);
                return;
            }

            body.classList.add('page-leaving');

            // Navigate after the leave transition, with a hard fallback so a
            // dropped transition event can never trap the user on the page.
            var done = false;
            var go = function () {
                if (done) { return; }
                done = true;
                navigate(anchor);
            };
            window.setTimeout(go, LEAVE_MS + 40);
        });
    })();
</script>