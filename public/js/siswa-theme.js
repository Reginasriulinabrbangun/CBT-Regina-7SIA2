/* Toggle dark/light portal siswa (Orchid, Bootstrap 5 color modes). */
(function () {
    var KEY = 'siswa-bs-theme';
    var SUN = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="currentColor"><path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm.5-9.5a.5.5 0 0 1 1 0v1a.5.5 0 0 1-1 0v-1Zm0 11a.5.5 0 0 1 1 0v1a.5.5 0 0 1-1 0v-1ZM2.5 7.5a.5.5 0 0 1 0 1h-1a.5.5 0 0 1 0-1h1Zm11 0a.5.5 0 0 1 0 1h-1a.5.5 0 0 1 0-1h1ZM3.535 3.465a.5.5 0 0 1 .708 0l.707.707a.5.5 0 1 1-.707.707l-.707-.707a.5.5 0 0 1 0-.707Zm8.486 8.486a.5.5 0 0 1 .707 0l.707.707a.5.5 0 0 1-.707.707l-.707-.707a.5.5 0 0 1 0-.707ZM3.535 12.535a.5.5 0 0 1 0-.707l.707-.707a.5.5 0 0 1 .707.707l-.707.707a.5.5 0 0 1-.707 0Zm8.486-8.486a.5.5 0 0 1 0-.707l.707-.707a.5.5 0 0 1 .707.707l-.707.707a.5.5 0 0 1-.707 0Z"/></svg>';
    var MOON = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="currentColor"><path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278Z"/></svg>';

    function current() {
        return document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
    }

    function render(btn) {
        var dark = current() === 'dark';
        btn.innerHTML = dark ? SUN : MOON;
        btn.setAttribute('aria-label', dark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
        btn.title = dark ? 'Mode terang' : 'Mode gelap';
    }

    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-secondary rounded-circle position-fixed d-inline-flex align-items-center justify-content-center';
        btn.style.cssText = 'width:2.75rem;height:2.75rem;right:1rem;bottom:1rem;z-index:1050;';

        btn.addEventListener('click', function () {
            var next = current() === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            try {
                localStorage.setItem(KEY, next);
            } catch (e) {}
            render(btn);
        });

        render(btn);
        document.body.appendChild(btn);
    });
})();
