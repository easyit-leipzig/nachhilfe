# easyIT-Nachhilfe · Layoutkorrektur

## Fehlerursache im Screenshot
Die Installation läuft unter `http://localhost/nachhilfe/`, während die alte
Entwicklungskonfiguration `http://localhost/nh_hor` vorgab. Eine Vielzahl von
Ressourcen wurde deshalb falsch adressiert. Zusätzlich widersprachen die
hellen Alt-CSS-Regeln der dunklen easyIT-Gestaltung.

## Umsetzung
- Das Firmenlogo wurde unverändert aus `easyit.zip` übernommen.
- Gemeinsamer dunkler Header und gleichfarbige Navigation mit Dropdowns.
- Vollbreite, zweispaltige Startseitenbühne statt kleiner weißer Contentbox.
- Dunkle Karten, einheitliche Abstände, Typografie und Footer.
- Neue CSS-Datei `assets/css/easyit-unified.css` wird als letzte Design-Ebene
  geladen; bestehende Funktionalität, Menüs und CMS-Inhalte bleiben erhalten.
- Dynamische Erkennung von `/nachhilfe`, `/nh_hor` oder beliebigem lokalen
  Ordnernamen für Links, CSS, Bilder und Skripte.

## Installation
ZIP entpacken, so dass `htdocs/nachhilfe/index.php` existiert. Lokale Adresse
`http://localhost/nachhilfe/`. Browser einmal mit Strg+F5 neu laden, um ältere
CSS-Versionen zu umgehen. Die neue Version des Service Workers löscht alte Caches.
Keine Datenbankmigration erforderlich. Die Menüstruktur bleibt im Admin bearbeitbar.


## November 2026 – Dunkle Inhaltsflächen statt weißer Sonderkarten

- `assets/css/erfolg.css`: Erfolgs-Hero, 90-%-Kennzahl, Nachweiskarten, Bewertungen und Transparenznotiz im dunkelblauen easyIT-Design mit weißer/hellblauer, kontrastreicher Schrift.
- `assets/css/jobs.css`: bisher helle Karriere-, Stellen-, Profil-, FAQ- und Bewerbungskarten an dieselbe Farbwelt angepasst.
- `assets/css/tutor-thiele.css`: Profil-/Bewertungs-Sonderkarten dunkel.
- `assets/css/bildnachweis.css`: Bildnachweis-Tabelle und mobile Karten dunkel.
- `assets/css/easyit-unified.css`: öffentliche Formularfelder und Meldungsflächen dunkel; Admin-/interne Oberflächen bleiben bewusst unverändert.
- Statische CSS-Verweise besitzen geänderte Versionsparameter, damit Browser neue Farben laden.
- Keine Änderungen an PHP-Fachlogik, Datenbanktabellen, Menüstruktur oder Bildmaterial.
