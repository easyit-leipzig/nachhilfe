# Sitemap und Navigation – easyIT Nachhilfe

Stand: 21. November 2026

## Was geändert wurde

- Die öffentliche Hauptnavigation umfasst acht aussagekräftige Bereiche: Startseite, Fächer, Lernstufen, Leipzig, Preise & Ablauf, Lernhilfen, Über uns, Kontakt.
- Alle Links stammen aus `includes/site-architecture.php` und den vorhandenen Konfigurationen für Fächer, Schulformen, Stadtteile und Lernwerkzeuge. Es werden nur existierende PHP-Seiten aufgenommen.
- `sitemap.php` ist die barrierearm gestaltete, öffentlich erreichbare HTML-Sitemap.
- `sitemap.xml` wird per Apache-Rewrite aus `sitemap-xml.php` erzeugt. Enthält ausschließlich öffentliche kanonische Seiten und veröffentlichte Blogartikel. `lastmod` wird nur bei überprüfbar datierten CMS-Artikeln ausgegeben.
- `robots.txt` wird nun per `robots.php` und `.htaccess` dynamisch mit der konfigurierten Hauptdomain generiert. Die veraltete feste `easyit-leipzig.de`-Adresse entfällt.
- Backend, Login, Formulardienst, Suche, Danke-Seiten, Offline und private Dateien kommen nicht in die Sitemap. `robots.txt` ersetzt keine Zugriffssicherung und kein `noindex` privater Seiten.

## Menüverwaltung

Bei einer noch nicht migrierten Datenbank mit dem historischen Standardmenü („Über“ + „Sonstiges“) erscheint öffentlich sofort die neue Struktur.

Im Adminbereich unter **Navigation** auf **Empfohlenes Menü in Datenbank übernehmen** klicken. Die bisherigen Einträge werden in die Tabelle `navigation_items_backup_20261121` kopiert. Danach sind die neuen Menüpunkte in der bestehenden Datenbankverwaltung vollständig editierbar. Adminrechte und eine funktionierende Datenbank sind erforderlich. Dieser Eingriff ist bewusst NICHT automatisch, weil die alte Menüstruktur individuelle Änderungen enthalten kann.

Fällt die Datenbank aus, bleibt die öffentliche Menüleiste aus der PHP-Konfiguration erreichbar.

## Konfiguration und Deployment

1. Das gesamte ZIP mit der bisherigen Verzeichnisstruktur deployen.
2. Prüfen, ob `SITE_BASE_URL` bzw. `config/site.local.php` die **richtige Nachhilfe-Domain** enthält (Standard aus `config/config.server.php`: `https://www.easyit-nachhilfe.de`).
3. Sicherstellen, dass Apache `mod_rewrite` aktiv ist. Anschließend `https://<Ihre-Domain>/sitemap.xml` und `https://<Ihre-Domain>/robots.txt` testen.
4. In der Google Search Console `sitemap.xml` für die tatsächlich kanonische Domain einreichen.
5. Das Adminmenü bei Bedarf durch die Ein-Klick-Aktion übernehmen.
6. Bei späteren neuen Fächern/Stadtteilen deren Einträge in `config/subjects.php`, `config/areas.php`, `config/school-types.php` oder `config/tools.php` ergänzen. Sitemap und Start-Menü passen sich darauf an.

## Quellen für Best Practices

- Google Search Central: https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap
- Google Search Central: https://developers.google.com/search/docs/crawling-indexing/links-crawlable
- W3C Web Accessibility Initiative: https://www.w3.org/WAI/tutorials/menus/structure/
- W3C APG: https://www.w3.org/WAI/ARIA/apg/patterns/disclosure/examples/disclosure-navigation/

## Grenzen

Die originale produktive DB wurde nicht geändert. Die Stellenangebote und Blogartikel werden erst auf dem echten Webserver aus der dortigen Datenbank gelesen. Für eine vollständige Live-Freigabe sollten die reale Domain, Suchmaschinenindexierung, Tastaturbedienung, Smartphone und alle geschützten Seiten im Browser getestet werden.
