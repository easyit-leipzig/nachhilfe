/* easyIT Nachhilfe: wandelt helle HTML-Infokarten in dunkle um.
   Bilder, Canvas, SVG, Iframes, Navigation, Logos und Buttons bleiben unverändert. */
(function () {
  'use strict';
  if (window.__easyitDarkUnifyLoaded) return;
  window.__easyitDarkUnifyLoaded = true;

  const skipTags = new Set(['HTML', 'BODY', 'HEAD', 'SCRIPT', 'STYLE', 'LINK', 'META', 'IMG',
    'PICTURE', 'CANVAS', 'SVG', 'VIDEO', 'AUDIO', 'IFRAME', 'BUTTON', 'INPUT',
    'SELECT', 'TEXTAREA', 'OPTION', 'OPTGROUP', 'PROGRESS', 'METER']);
  const candidateTags = new Set(['DIV', 'ASIDE', 'SECTION', 'ARTICLE', 'FIELDSET',
    'FORM', 'DETAILS', 'DIALOG', 'TABLE', 'BLOCKQUOTE', 'FIGURE', 'UL', 'OL']);
  const cardClues = /(?:card|box|panel|notice|info|hint|callout|aside|privacy|feature|tile|alert|note|summary)/i;
  const lightThreshold = 219;
  let scheduled = false;

  function rgba(s) {
    const m = s && s.match(/^rgba?\(\s*([\d.]+)[,\s]+([\d.]+)[,\s]+([\d.]+)(?:[,\s\/]+([\d.]+))?\s*\)/i);
    return m ? [Number(m[1]), Number(m[2]), Number(m[3]), m[4] == null ? 1 : Number(m[4])] : null;
  }
  function luminance(rgb) { return rgb[0] * .2126 + rgb[1] * .7152 + rgb[2] * .0722; }
  function isPale(rgb) {
    return rgb && Math.min(rgb[0], rgb[1], rgb[2]) > lightThreshold && luminance(rgb) > 227;
  }
  function whiteBackground(cs) {
    const bg = rgba(cs.backgroundColor);
    if (bg && bg[3] > .80 && isPale(bg)) return true;
    // Helle CSS-Verlaeufe aus Weiss/fast Weiss ebenfalls erfassen.
    const gradient = cs.backgroundImage || '';
    if (!/^linear-gradient\(/i.test(gradient)) return false;
    const colors = [...gradient.matchAll(/rgba?\([^)]*\)/gi)].map(m => rgba(m[0]));
    return colors.length >= 2 && colors.every(c => c && c[3] >= .8 && isPale(c));
  }
  function isExcluded(el) {
    if (skipTags.has(el.tagName)) return true;
    if (el.closest('header, nav, footer, [role="navigation"], [role="banner"], [role="contentinfo"]')) return true;
    if (el.closest('[data-easyit-dark-ignore], .logo, .brand-logo, .mathjax, .MathJax, .katex, .leaflet-container, .mapboxgl-map, .codemirror, .CodeMirror, .monaco-editor')) return true;
    if (el.closest('[contenteditable="true"]')) return true;
    if (el.matches('[role="button"], [role="img"], [role="progressbar"], [role="slider"]')) return true;
    return false;
  }
  function visible(el, cs) {
    if (cs.display === 'none' || cs.visibility === 'hidden') return false;
    const rect = el.getBoundingClientRect();
    return rect.width >= 116 && rect.height >= 48 && rect.width * rect.height >= 8500;
  }
  function isLikelyCard(el) {
    if (candidateTags.has(el.tagName)) return true;
    const c = typeof el.className === 'string' ? el.className : '';
    return cardClues.test(c);
  }
  function fixText(el) {
    let count = 0;
    const nodes = el.querySelectorAll('div, span, p, small, label, h1, h2, h3, h4, h5, h6, strong, b, li, dt, dd');
    for (const node of nodes) {
      if (++count > 200) break;
      if (node.closest('button, [role="button"], [data-easyit-dark-ignore]')) continue;
      if (node.closest('header, nav, footer')) continue;
      const cs = window.getComputedStyle(node), c = rgba(cs.color);
      if (!c || c[3] < .65) continue;
      if (luminance(c) < 135) {
        node.classList.add('easyit-dark-contrast-text');
        node.style.setProperty('color', '#eff8ff', 'important');
      }
    }
  }
  function apply(el) {
    if (el.classList.contains('easyit-dark-surface') || isExcluded(el) || !isLikelyCard(el)) return;
    const cs = window.getComputedStyle(el);
    if (!whiteBackground(cs) || !visible(el, cs)) return;
    // Keine rein grafischen Einbettungen umgestalten.
    if (el.querySelector('canvas, iframe, video') && !el.querySelector('p, h2, h3, label, input, textarea')) return;
    el.classList.add('easyit-dark-surface');
    // Auch hart kodierte Inline-Farben oder ID-Selektoren mit !important ueberstimmen.
    el.style.setProperty('background', 'linear-gradient(155deg, #103756 0%, #0a2a49 100%)', 'important');
    el.style.setProperty('color', '#eff8ff', 'important');
    el.style.setProperty('border-color', '#2c7198', 'important');
    fixText(el);
  }
  function fixForms(root) {
    const excluded = new Set(['hidden','checkbox','radio','range','color','image','button','submit','reset']);
    for (const field of root.querySelectorAll('input, select, textarea')) {
      const type = (field.getAttribute('type') || 'text').toLowerCase();
      if (field.tagName === 'INPUT' && excluded.has(type)) continue;
      const cs = window.getComputedStyle(field);
      const bg = rgba(cs.backgroundColor);
      if (bg && bg[3] >= .75 && luminance(bg) > 170) {
        field.style.setProperty('background', '#0b2c4d', 'important');
        field.style.setProperty('color', '#eff8ff', 'important');
        field.style.setProperty('-webkit-text-fill-color', '#eff8ff', 'important');
        field.style.setProperty('border-color', '#2c7198', 'important');
      }
    }
    for (const label of root.querySelectorAll('form label, form legend, .form-label, .input-label, .field-label')) {
      const c = rgba(window.getComputedStyle(label).color);
      if (c && c[3] > .6 && luminance(c) < 120) {
        label.style.setProperty('color', '#e0f1ff', 'important');
      }
    }
  }
  function fixPaleButtons(root) {
    for (const b of root.querySelectorAll('button, input[type="button"], input[type="reset"], input[type="submit"], a[role="button"]')) {
      if (b.classList.contains('easyit-dark-danger') || b.classList.contains('easyit-dark-light-button')) continue;
      if (b.closest('header, nav, footer, [data-easyit-dark-ignore]')) continue;
      const c = rgba(window.getComputedStyle(b).backgroundColor);
      if (!c || c[3] <= .8 || luminance(c) <= 219) continue;
      const txt = (b.textContent || b.value || '').trim();
      b.classList.add(/^(?:×|✕|✖|x|X|−)$/.test(txt) ? 'easyit-dark-danger' : 'easyit-dark-light-button');
    }
  }
  function scan() {
    scheduled = false;
    const roots = document.querySelectorAll('main, [role="main"], .page-main, .site-main, .main-content, .content-area, dialog');
    const targets = roots.length ? [...roots] : [document.body];
    const seen = new Set();
    let count = 0;
    for (const root of targets) {
      if (!root) continue;
      fixForms(root);
      fixPaleButtons(root);
      for (const el of [root, ...root.querySelectorAll('div, aside, section, article, fieldset, form, details, dialog, table, blockquote, figure, ul, ol')]) {
        if (seen.has(el)) continue;
        seen.add(el);
        if (++count > 4000) return;
        apply(el);
      }
    }
  }
  function schedule() {
    if (scheduled) return;
    scheduled = true;
    window.requestAnimationFrame(scan);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', schedule, {once:true});
  else schedule();
  window.addEventListener('load', schedule);
  window.addEventListener('pageshow', schedule);
  const observer = new MutationObserver(schedule);
  const start = () => { if (document.body) observer.observe(document.body, {childList:true, subtree:true}); };
  if (document.body) start();
  else document.addEventListener('DOMContentLoaded', start, {once:true});
  // Eigene Klasse wird als Attribut gesetzt, daher werden nur hinzugefügte DOM-Knoten beobachtet.
})();
