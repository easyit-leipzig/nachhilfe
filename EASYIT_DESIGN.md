# easyIT Designintegration – Nachhilfe

Die öffentliche Webseite verwendet ab diesem Stand ein einheitliches easyIT-Layout mit dunkelblauem Hintergrund, türkisen Akzenten, dem bestehenden Löwenlogo und hellen Schriftfarben. Verwendet werden die zentralen Vorlagen `includes/header.php`, `includes/footer.php`, `includes/meta.php` und das Stylesheet `assets/css/easyit-theme.css`.

## Aufbau

- **Header:** Logo, Seitentitel, Suchfeld, Kontakt/Anmeldung und bestehende Datenbank-Hauptnavigation mit Untermenüs. Mobile Navigation und Such-Logik stammen weiterhin aus den bestehenden JS-Dateien.
- **Main:** Responsive Inhaltsbreite, dunkelblaue Panels und Karten sowie kontrastreiche Schaltflächen. Inhalte und Formulare wurden nicht entfernt.
- **Footer:** Jetzt nach dem `main`-Element statt darin platziert; mit Kontakt, Schnelllinks und Datenschutz/Impressum. Interne Links berücksichtigen `app_path()`.
- **Admin und Portale:** Dieselbe Farbwelt über separate CSS-Anpassungen und gemeinsame interne Header-/Footer-Bausteine.

## Grenzen

Die Gestaltung wurde anhand der bekannten easyIT-Farbwelt und des im Nachhilfe-Paket enthaltenen Logos umgesetzt. Das Original-CSS der separaten easyIT-Firmenwebseite lag diesem ZIP nicht bei, daher handelt es sich um eine konsistente Nachbildung, keinen bytegleichen Import.

Die PHP-Syntax wurde geprüft. Für einen endgültigen Freigabetest bitte Navigation und Formularversand mit der produktiven Datenbank sowie die Ansichten auf Handy/Desktop prüfen. Zugangsdaten in `config` wurden nicht geändert.
