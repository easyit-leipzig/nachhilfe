/* easyIT Fachbilder V2 — eigenständige lokale Einbindung */
(function () {
 'use strict';
 const script = document.currentScript;
 if (!script) return;
 const fach = script.getAttribute('data-fach');
 const katalog = {"mathematik":{"name":"Mathematik","bilder":[{"datei":"mathematik-01.svg","titel":"Funktionen & Graphen","beschreibung":"Parabeln, Geraden und Änderungsraten"},{"datei":"mathematik-02.svg","titel":"Geometrie & Trigonometrie","beschreibung":"Formen, Winkel und Beziehungen"},{"datei":"mathematik-03.svg","titel":"Analysis & Integrale","beschreibung":"Flächen und Zusammenhänge"},{"datei":"mathematik-04.svg","titel":"Stochastik","beschreibung":"Wahrscheinlichkeit verstehen"}]},"physik":{"name":"Physik","bilder":[{"datei":"physik-01.svg","titel":"Optik & Licht","beschreibung":"Brechung, Reflexion, Linsen"},{"datei":"physik-02.svg","titel":"Elektrizität","beschreibung":"Stromkreise und Schwingungen"},{"datei":"physik-03.svg","titel":"Atom- & Kernphysik","beschreibung":"Teilchen und Strahlung"},{"datei":"physik-04.svg","titel":"Wellen & Interferenz","beschreibung":"Schwingungen sichtbar machen"}]},"chemie":{"name":"Chemie","bilder":[{"datei":"chemie-01.svg","titel":"Stoffe & Reaktionen","beschreibung":"Beobachten und erklären"},{"datei":"chemie-02.svg","titel":"Moleküle & Bindungen","beschreibung":"Strukturen verstehen"},{"datei":"chemie-03.svg","titel":"Periodensystem","beschreibung":"Elemente und Eigenschaften"},{"datei":"chemie-04.svg","titel":"Säuren & Basen","beschreibung":"Experimente und Gleichgewichte"}]},"informatik":{"name":"Informatik","bilder":[{"datei":"informatik-01.svg","titel":"Programmieren","beschreibung":"Schritt für Schritt zum Code"},{"datei":"informatik-02.svg","titel":"Netzwerke","beschreibung":"Daten und Verbindungen"},{"datei":"informatik-03.svg","titel":"Datenbanken","beschreibung":"Informationen sinnvoll ordnen"},{"datei":"informatik-04.svg","titel":"Webentwicklung","beschreibung":"Webseiten verstehen und bauen"}]},"deutsch":{"name":"Deutsch","bilder":[{"datei":"deutsch-01.svg","titel":"Lesen & Verstehen","beschreibung":"Texte erschließen"},{"datei":"deutsch-02.svg","titel":"Ausdruck & Kommunikation","beschreibung":"Gedanken klar formulieren"},{"datei":"deutsch-03.svg","titel":"Rechtschreibung","beschreibung":"Sicher mit Sprache umgehen"},{"datei":"deutsch-04.svg","titel":"Grammatik","beschreibung":"Satzbau und Struktur"}]},"englisch":{"name":"Englisch","bilder":[{"datei":"englisch-01.svg","titel":"Reading & Listening","beschreibung":"Texte sicher verstehen"},{"datei":"englisch-02.svg","titel":"Speaking","beschreibung":"Englisch im Gespräch"},{"datei":"englisch-03.svg","titel":"Vocabulary","beschreibung":"Wortschatz erweitern"},{"datei":"englisch-04.svg","titel":"Grammar","beschreibung":"Zeitformen und Satzbau"}]},"franzoesisch":{"name":"Französisch","bilder":[{"datei":"franzoesisch-01.svg","titel":"Lire & comprendre","beschreibung":"Französisch verstehen"},{"datei":"franzoesisch-02.svg","titel":"Parler","beschreibung":"Im Gespräch sicher werden"},{"datei":"franzoesisch-03.svg","titel":"Vocabulaire","beschreibung":"Wörter im Zusammenhang"},{"datei":"franzoesisch-04.svg","titel":"Grammaire","beschreibung":"Strukturen erkennen"}]},"spanisch":{"name":"Spanisch","bilder":[{"datei":"spanisch-01.svg","titel":"Leer & entender","beschreibung":"Texte und Hörverstehen"},{"datei":"spanisch-02.svg","titel":"Hablar","beschreibung":"Sprechen und reagieren"},{"datei":"spanisch-03.svg","titel":"Vocabulario","beschreibung":"Wortschatz im Alltag"},{"datei":"spanisch-04.svg","titel":"Gramática","beschreibung":"Satzbau und Formen"}]},"latein":{"name":"Latein","bilder":[{"datei":"latein-01.svg","titel":"Texte verstehen","beschreibung":"Lateinische Texte entschlüsseln"},{"datei":"latein-02.svg","titel":"Antike Lebenswelt","beschreibung":"Kultur und Geschichte"},{"datei":"latein-03.svg","titel":"Vokabeln lernen","beschreibung":"Formen sicher erkennen"},{"datei":"latein-04.svg","titel":"Grammatik","beschreibung":"Kasus und Satzgefüge"}]},"sprachen":{"name":"Sprachen","bilder":[{"datei":"sprachen-01.svg","titel":"Leseverstehen","beschreibung":"Zusammenhänge im Text"},{"datei":"sprachen-02.svg","titel":"Sprechen & Dialog","beschreibung":"Sprache aktiv verwenden"},{"datei":"sprachen-03.svg","titel":"Wortschatz","beschreibung":"Begriffe nachhaltig lernen"},{"datei":"sprachen-04.svg","titel":"Grammatik","beschreibung":"Sprachstrukturen verstehen"}]},"biologie":{"name":"Biologie","bilder":[{"datei":"biologie-01.svg","titel":"Zellbiologie","beschreibung":"Aufbau und Funktion"},{"datei":"biologie-02.svg","titel":"Genetik","beschreibung":"Erbinformation verstehen"},{"datei":"biologie-03.svg","titel":"Pflanzenbiologie","beschreibung":"Licht, Wachstum und Energie"},{"datei":"biologie-04.svg","titel":"Ökologie","beschreibung":"Lebensräume und Beziehungen"}]},"geschichte":{"name":"Geschichte","bilder":[{"datei":"geschichte-01.svg","titel":"Zeitleisten","beschreibung":"Ereignisse einordnen"},{"datei":"geschichte-02.svg","titel":"Antike","beschreibung":"Kulturen und Bauwerke"},{"datei":"geschichte-03.svg","titel":"Quellenanalyse","beschreibung":"Vergangenheit verstehen"},{"datei":"geschichte-04.svg","titel":"Epochen im Vergleich","beschreibung":"Entwicklungen erkennen"}]},"geografie":{"name":"Geografie","bilder":[{"datei":"geografie-01.svg","titel":"Kartenkunde","beschreibung":"Räume und Orientierung"},{"datei":"geografie-02.svg","titel":"Erde & Kontinente","beschreibung":"Globale Zusammenhänge"},{"datei":"geografie-03.svg","titel":"Landschaften","beschreibung":"Relief und Entstehung"},{"datei":"geografie-04.svg","titel":"Klima & Wetter","beschreibung":"Prozesse nachvollziehen"}]}};
 const daten = katalog[fach];
 if (!daten || !Array.isArray(daten.bilder) || daten.bilder.length < 4) {
   console.warn('easyIT Fachbilder: Fach nicht gefunden:', fach);
   return;
 }
 const base = new URL('.', script.src).href;
 const imageUrl = (bild) => new URL(bild.datei, base).href;

 // Die CSS-Datei wird von der Position der JS-Datei aus aufgelöst,
 // unabhängig davon, ob die PHP-Seite im Stammverzeichnis oder Unterordner liegt.
 if (!document.querySelector('link[data-easyit-fachbilder-style]')) {
   const link = document.createElement('link');
   link.rel = 'stylesheet';
   link.href = new URL('fachbilder.css', base).href;
   link.setAttribute('data-easyit-fachbilder-style', '2');
   document.head.appendChild(link);
 }

 function setHeroImage(img) {
   if (!img || img.closest('header,nav,footer,.easyit-fachbilder')) return;
   const first = imageUrl(daten.bilder[0]);
   if (img.currentSrc === first || img.src === first) return;
   const pic = img.closest('picture');
   if (pic) pic.querySelectorAll('source').forEach(source => source.remove());
   img.removeAttribute('srcset');
   img.removeAttribute('sizes');
   img.removeAttribute('data-src');
   img.removeAttribute('data-srcset');
   img.loading = 'eager';
   img.decoding = 'async';
   img.src = first;
   img.dataset.easyitFachbildRepariert = '2';
 }

 function findMain() {
   return document.querySelector('main, [role="main"], #main, .page-main, .main-content') || document.body;
 }

 function fixHero(root) {
   const imgs = Array.from(root.querySelectorAll('img')).filter(img => !img.closest('header,nav,footer,.easyit-fachbilder'));
   const alias = fach === 'mathematik' ? 'mathe' : fach;
   // Vorrang: das beschriftete Bild im Hero, das im Screenshot defekt ist.
   const candidate = imgs.find(img => /anschaulich|verstaendlich|verständlich|nachhilfe|lernen/i.test(img.alt || '') &&
     ((img.alt || '').toLocaleLowerCase('de').includes(alias) || (img.alt || '').toLocaleLowerCase('de').includes(daten.name.toLocaleLowerCase('de'))))
      || imgs.find(img => /hero|subject|fach-illustration|fachbild/i.test(img.className + ' ' + (img.closest('[class]')?.className || '')) &&
                          (img.naturalWidth === 0 || !img.getAttribute('src')))
      || imgs.find(img => img.complete && img.naturalWidth === 0);
   if (candidate) setHeroImage(candidate);
   imgs.forEach(img => {
     if (img === candidate) return;
     img.addEventListener('error', () => {
       if (img.closest('header,nav,footer,.easyit-fachbilder')) return;
       if (img.dataset.easyitFachbildRepariert === '2') return;
       setHeroImage(img);
     }, {once:true});
   });
 }

 function makeGallery() {
   const section = document.createElement('section');
   section.className = 'easyit-fachbilder';
   section.id = 'easyit-fachbilder';
   section.setAttribute('aria-label', 'Vier Bilder aus dem Fach ' + daten.name);
   const head = document.createElement('div');
   head.className = 'easyit-fachbilder__intro';
   const kicker = document.createElement('p');
   kicker.className = 'easyit-fachbilder__eyebrow';
   kicker.textContent = daten.name + ' · Vier Themen im Bild';
   const heading = document.createElement('h2');
   heading.textContent = daten.name + ' anschaulich lernen';
   const desc = document.createElement('p');
   desc.textContent = 'Vier Einblicke in wichtige Themenbereiche der Nachhilfe.';
   head.append(kicker, heading, desc);
   const grid = document.createElement('div');
   grid.className = 'easyit-fachbilder__grid';
   daten.bilder.slice(0, 4).forEach((b, i) => {
     const fig = document.createElement('figure');
     fig.className = 'easyit-fachbilder__card';
     const img = document.createElement('img');
     img.src = imageUrl(b);
     img.alt = daten.name + ': ' + b.titel;
     img.width = 1200;
     img.height = 720;
     img.loading = i < 2 ? 'eager' : 'lazy';
     img.decoding = 'async';
     const cap = document.createElement('figcaption');
     const strong = document.createElement('strong');
     strong.textContent = b.titel;
     const text = document.createElement('span');
     text.textContent = b.beschreibung;
     cap.append(strong, text);
     fig.append(img, cap);
     grid.appendChild(fig);
   });
   section.append(head, grid);
   return section;
 }

 function findHeroAnchor(root) {
   const h1 = root.querySelector('h1');
   if (!h1) return root.firstElementChild || root;
   const section = h1.closest('section, article, [class*="hero"], [class*="Hero"]');
   if (section && section !== root && !section.contains(root)) return section;
   // Suche einen gemeinsamen Elternknoten von H1 und dem Hero-Bild.
   const img = Array.from(root.querySelectorAll('img')).find(x => !x.closest('header,nav,footer,.easyit-fachbilder'));
   if (img) {
     let p = h1.parentElement;
     while (p && p !== root && p !== document.body) {
       if (p.contains(img) && p.parentElement !== root && p.parentElement !== document.body) return p;
       p = p.parentElement;
     }
   }
   let p = h1;
   while (p.parentElement && p.parentElement !== root && p.parentElement !== document.body) {
     if (p.classList && /hero|intro|teaser|subject/i.test(p.className || '')) return p;
     p = p.parentElement;
   }
   return p === h1 ? root.firstElementChild || root : p;
 }

 function boot() {
   if (document.documentElement.dataset.easyitFachbilderAktiv === fach) return;
   document.documentElement.dataset.easyitFachbilderAktiv = fach;
   const root = findMain();
   fixHero(root);
   const existing = document.querySelector('.easyit-fachbilder');
   const gallery = existing || makeGallery();
   const anchor = findHeroAnchor(root);
   if (anchor && anchor !== gallery && anchor.parentNode && !anchor.contains(gallery)) {
     anchor.insertAdjacentElement('afterend', gallery);
   } else if (!gallery.isConnected) {
     root.appendChild(gallery);
   }
   // Falls spätere Ladeprozesse das Hero-Bild zurücksetzen, noch einmal prüfen.
   document.querySelectorAll('img').forEach(img => {
     if (img.closest('.easyit-fachbilder,header,nav,footer')) return;
     img.addEventListener('error', () => {
       if (img.dataset.easyitFachbildRepariert !== '2') setHeroImage(img);
     }, {once:true});
   });
 }
 if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot, {once:true});
 else boot();
})();
