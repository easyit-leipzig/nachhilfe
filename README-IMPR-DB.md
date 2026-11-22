# Korrektur des Impressums – Datenbank `easyit_homepage`

**Fehler aus dem Screenshot:** `SQLSTATE[HY000] [1049] Unknown database 'easyit'` in `D:\xampp\htdocs\nachhilfe\includes\database.php:12`, aufgerufen durch `impressum.php:33`.

## Sofortkorrektur (direkt)

In der vorhandenen `includes/database.php` ausschließlich den Datenbanknamen ändern:

- vorher: `dbname=easyit` (oder `'easyit'` als Datenbankname)
- nachher: `dbname=easyit_homepage` (bzw. `'easyit_homepage'`)

**Nicht** Benutzername, Passwort, Host oder das übrige PHP-Skript ändern. Die Nachhilfe-Webseite verwendet nach früherer Projektkonfiguration `easyit_homepage`. Hierdurch wird keine Datenbank neu erstellt.

## Automatische Korrektur unter XAMPP

1. ZIP in `D:\xampp\htdocs\nachhilfe\` entpacken. Es wird nur `tools/` und die README/BAT-Datei ergänzt – **keine** bestehende Datenbank- oder Impressumsdatei überschrieben.
2. Datei `DB-FIX-AUSFUEHREN.cmd` doppelt anklicken. Alternativ im Nachhilfe-Verzeichnis `D:\xampp\php\php.exe tools\fix-impressum-db.php` ausführen.
3. Das Skript sucht exakt den alten Datenbanknamen in gängigen Datenbank-Konfigurationsdateien und ersetzt ihn durch `easyit_homepage`. Bestehende Zugangsdaten bleiben erhalten. Eine Sicherung liegt im **System-Temp-Verzeichnis außerhalb des Webroots**.
4. Danach `http://localhost/nachhilfe/impressum.php` neu laden.

## Prüfung

`D:\xampp\php\php.exe tools\pruefe-impressum-db.php`

Das Skript ruft die vorhandene `db()`-Funktion auf und fragt `SELECT DATABASE()` ab. Es gibt keine Kennwörter aus. Wenn der Fehler danach `Unknown database 'easyit_homepage'` lautet, ist die Datenbank noch nicht in MySQL/MariaDB angelegt bzw. eingebunden. Falls `Access denied` erscheint, passen Benutzer/Passwort oder dessen Rechte nicht. Falls `Table ... doesn't exist` erscheint, ist der Datenbankname korrekt, aber das Impressum benötigt noch seine Tabellen.

**Hinweis:** Das gesamte aktuelle Nachhilfe-Webseiten-ZIP stand in dieser Unterhaltung nicht als bearbeitbare Datei zur Verfügung. Der Patch ändert die vorhandene Installation gezielt, statt eine ungesicherte Neuimplementierung der Datenbankverbindung einzuspielen.
