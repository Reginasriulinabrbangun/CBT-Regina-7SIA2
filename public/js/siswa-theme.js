/* Portal siswa (Orchid, Bootstrap 5): toggle tema, sidebar collapsible, drawer mobile. */
(function () {
    'use strict';

    var THEME_KEY = 'siswa-bs-theme';
    var SIDEBAR_KEY = 'siswa-sidebar';
    var root = document.documentElement;

    var SUN = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="currentColor"><path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm.5-9.5a.5.5 0 0 1 1 0v1a.5.5 0 0 1-1 0v-1Zm0 11a.5.5 0 0 1 1 0v1a.5.5 0 0 1-1 0v-1ZM2.5 7.5a.5.5 0 0 1 0 1h-1a.5.5 0 0 1 0-1h1Zm11 0a.5.5 0 0 1 0 1h-1a.5.5 0 0 1 0-1h1ZM3.535 3.465a.5.5 0 0 1 .708 0l.707.707a.5.5 0 1 1-.707.707l-.707-.707a.5.5 0 0 1 0-.707Zm8.486 8.486a.5.5 0 0 1 .707 0l.707.707a.5.5 0 0 1-.707.707l-.707-.707a.5.5 0 0 1 0-.707ZM3.535 12.535a.5.5 0 0 1 0-.707l.707-.707a.5.5 0 0 1 .707.707l-.707.707a.5.5 0 0 1-.707 0Zm8.486-8.486a.5.5 0 0 1 0-.707l.707-.707a.5.5 0 0 1 .707.707l-.707.707a.5.5 0 0 1-.707 0Z"/></svg>';
    var MOON = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="currentColor"><path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278Z"/></svg>';

    var drawerObserver = null;
    var lastFocused = null;

    function store(key, value) {
        try {
            localStorage.setItem(key, value);
        } catch (e) {
            /* localStorage bisa diblokir; abaikan. */
        }
    }

    function read(key) {
        try {
            return localStorage.getItem(key);
        } catch (e) {
            return null;
        }
    }

    /* ------------------------------------------------------------------
     * Tema terang/gelap
     * ------------------------------------------------------------------ */

    function currentTheme() {
        return root.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
    }

    function renderThemeButton(btn) {
        var dark = currentTheme() === 'dark';
        btn.innerHTML = dark ? SUN : MOON;
        btn.setAttribute('aria-label', dark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
        btn.title = dark ? 'Mode terang' : 'Mode gelap';
    }

    function ensureThemeButton() {
        var existing = document.getElementById('siswaThemeToggle');

        if (existing) {
            renderThemeButton(existing);
            return;
        }

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.id = 'siswaThemeToggle';
        btn.className = 'btn btn-outline-secondary rounded-circle position-fixed d-inline-flex align-items-center justify-content-center';
        btn.style.cssText = 'width:2.75rem;height:2.75rem;right:1rem;bottom:1rem;z-index:1050;';

        btn.addEventListener('click', function () {
            var next = currentTheme() === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-bs-theme', next);
            store(THEME_KEY, next);
            renderThemeButton(btn);
        });

        renderThemeButton(btn);
        document.body.appendChild(btn);
    }

    /* ------------------------------------------------------------------
     * Sidebar collapsible (desktop) — status tersimpan di localStorage
     * ------------------------------------------------------------------ */

    function isCollapsed() {
        return root.getAttribute('data-siswa-sidebar') === 'collapsed';
    }

    function drawerIsOpen() {
        return document.body.classList.contains('menu-open');
    }

    function syncSidebarAria() {
        var toggle = document.getElementById('siswaSidebarToggle');

        if (toggle) {
            toggle.setAttribute('aria-expanded', isCollapsed() ? 'false' : 'true');
            toggle.setAttribute('aria-label', isCollapsed() ? 'Perbesar sidebar' : 'Perkecil sidebar');
        }

        var hamburger = document.getElementById('siswaMenuToggle');

        if (hamburger) {
            hamburger.setAttribute('aria-expanded', drawerIsOpen() ? 'true' : 'false');
        }
    }

    function bindSidebarToggle() {
        var toggle = document.getElementById('siswaSidebarToggle');

        if (!toggle || toggle.dataset.siswaBound) {
            return;
        }

        toggle.dataset.siswaBound = '1';

        toggle.addEventListener('click', function () {
            var next = isCollapsed() ? 'expanded' : 'collapsed';
            root.setAttribute('data-siswa-sidebar', next);
            store(SIDEBAR_KEY, next);
            syncSidebarAria();
        });
    }

    /* ------------------------------------------------------------------
     * Label tooltip rail + aria menu
     * ------------------------------------------------------------------ */

    function decorateMenu() {
        document.querySelectorAll('.siswa-aside .nav-link').forEach(function (link) {
            var label = link.querySelector('span.text-break');

            if (label) {
                var text = label.textContent.trim();

                if (text) {
                    link.setAttribute('data-label', text);
                    link.setAttribute('aria-label', text);
                }
            }

            if (link.classList.contains('active')) {
                link.setAttribute('aria-current', 'page');
            }
        });
    }

    /* ------------------------------------------------------------------
     * Drawer mobile: fokus, backdrop, ESC, trap Tab
     * ------------------------------------------------------------------ */

    function drawerFocusables() {
        var nav = document.getElementById('headerMenuCollapse');

        if (!nav) {
            return [];
        }

        var selector = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

        return Array.prototype.filter.call(nav.querySelectorAll(selector), function (el) {
            return el.offsetParent !== null;
        });
    }

    function focusDrawer() {
        var nav = document.getElementById('headerMenuCollapse');

        if (!nav) {
            return;
        }

        lastFocused = document.activeElement;
        nav.focus({ preventScroll: true });
    }

    function restoreFocus() {
        if (lastFocused && typeof lastFocused.focus === 'function' && document.contains(lastFocused)) {
            lastFocused.focus({ preventScroll: true });
        }

        lastFocused = null;
    }

    function closeDrawer() {
        document.body.classList.remove('menu-open');
    }

    function watchDrawer() {
        if (!('MutationObserver' in window)) {
            return;
        }

        if (drawerObserver) {
            drawerObserver.disconnect();
        }

        var wasOpen = drawerIsOpen();

        drawerObserver = new MutationObserver(function () {
            var open = drawerIsOpen();

            if (open === wasOpen) {
                return;
            }

            wasOpen = open;

            if (open) {
                focusDrawer();
            } else {
                restoreFocus();
            }

            syncSidebarAria();
        });

        drawerObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
    }

    /* Handler global — dipasang sekali; document tidak diganti Turbo. */
    document.addEventListener('click', function (event) {
        var target = event.target;

        if (target && target.closest && target.closest('#siswaMenuBackdrop')) {
            closeDrawer();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (!drawerIsOpen()) {
            return;
        }

        if (event.key === 'Escape') {
            closeDrawer();
            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        var items = drawerFocusables();

        if (!items.length) {
            return;
        }

        var first = items[0];
        var last = items[items.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    var desktopQuery = window.matchMedia('(min-width: 992px)');

    function onDesktopChange(event) {
        if (event.matches && drawerIsOpen()) {
            closeDrawer();
        }
    }

    if (desktopQuery.addEventListener) {
        desktopQuery.addEventListener('change', onDesktopChange);
    } else if (desktopQuery.addListener) {
        desktopQuery.addListener(onDesktopChange);
    }

    /* ------------------------------------------------------------------
     * Init — dijalankan saat muat awal DAN setiap navigasi Turbo,
     * karena Turbo mengganti <body> dan tidak mengulang inline script di <head>.
     * ------------------------------------------------------------------ */

    function init() {
        var state = root.getAttribute('data-siswa-sidebar');

        if (state !== 'collapsed' && state !== 'expanded') {
            root.setAttribute('data-siswa-sidebar', read(SIDEBAR_KEY) === 'collapsed' ? 'collapsed' : 'expanded');
        }

        // Turbo menyimpan snapshot <body>: pastikan drawer selalu tertutup.
        document.body.classList.remove('menu-open');

        ensureThemeButton();
        bindSidebarToggle();
        decorateMenu();
        syncSidebarAria();
        watchDrawer();
    }

    document.addEventListener('DOMContentLoaded', init);
    document.addEventListener('turbo:load', init);
    document.addEventListener('turbo:render', decorateMenu);
})();
