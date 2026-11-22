/* Ersetzt ausschließlich defekte Bilder im Hauptinhalt der aktuellen Fachseite. */
(function () {
    'use strict';
    var script = document.currentScript;
    if (!script) return;
    var fallback = script.getAttribute('data-fachbilder-fallback');
    if (!fallback) return;
    var fallbackAbs = new URL(fallback, document.baseURI).href;
    function fix(img) {
        if (!img || img.closest('.easyit-fachbilder') || img.dataset.easyitFachbildRepariert === '1') return;
        if (img.currentSrc === fallbackAbs || img.src === fallbackAbs) return;
        img.dataset.easyitFachbildRepariert = '1';
        img.removeAttribute('srcset');
        img.removeAttribute('sizes');
        img.src = fallbackAbs;
    }
    function activate() {
        document.querySelectorAll('main img, [role="main"] img').forEach(function (img) {
            if (img.complete && img.naturalWidth === 0) fix(img);
            img.addEventListener('error', function () { fix(img); }, { once: true });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', activate, { once: true });
    else activate();
})();
