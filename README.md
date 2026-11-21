# easyIT Nachhilfe – bereinigtes Website-Paket mit easyIT-Design

Dieses Paket enthält den aktiven PHP-Webauftritt, den Adminbereich, Konfigurationen, benötigte Ressourcen sowie Datenbankgrundlage und Migrationen.

## Installation

- Den Inhalt des Ordners `nachhilfe/` in das gewünschte Webverzeichnis übertragen.
- PHP und MariaDB/MySQL für die aktuelle Konfiguration bereitstellen.
- `config/config.local.php` bzw. `config/config.server.php` sowie `config/database.local.php` prüfen.
- Auf einer neuen Datenbank den erhaltenen Gesamtstand unter `database/easyit_stand_26.07.26.sql` und gegebenenfalls danach die erforderlichen neueren Migrationen prüfen. SQL-Dateien nicht ungeprüft mehrfach importieren.
- Für die Erstinstallation des Adminbereichs ist `admin/setup.php` weiterhin enthalten; nach Anlage eines Administrators wird die Seite gemäß Anwendungscode gesperrt.
- `storage/` und `uploads/` müssen die nötigen Schreibrechte besitzen. Ihre Zugriffssperren (`.htaccess`) sind erhalten.

## Hinweise

Die Konfigurationsdateien der hochgeladenen Version wurden unverändert übernommen. Sie können sensible Zugangsdaten enthalten. ZIP nicht öffentlich bereitstellen.

Die Bilder für dynamisch konfigurierte Startseiten-Blöcke wurden überwiegend beibehalten, weil die produktive Datenbank andere Bildpfade enthalten kann als der mitgelieferte Datenbankstand.

Dies ist eine bereinigte Momentaufnahme, kein getestetes Live-Deployment. Einzelheiten in `BEREINIGUNG.md`.

## Layoutanpassung 21.11.2026

Header, Hauptbereich und Footer verwenden die dunkelblau-türkise easyIT-Designlinie mit dem Löwenlogo. Die zentralen Layoutdateien und eigene Stylesheets erlauben weitere Anpassungen ohne Einzelbearbeitung der Fachseiten. Details siehe `EASYIT_DESIGN.md`.
