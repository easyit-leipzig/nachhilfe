# Bereinigung des ZIP-Pakets

Ergebnis der statischen Prüfung vom 21. November 2026.

Ausgangsarchiv: 983 Dateien, 25,870,186 Bytes (unkomprimiert).
Entfernt: 689 Dateien, 16,615,523 Bytes (unkomprimiert).
Im Paket erhalten: 294 Dateien vor Aktualisierung der Dokumentation.

## Entfernte Dateigruppen

- Alte Anleitungs-, Status-, Reparatur- oder Revisionsdatei: 44 Dateien
- Alter SQL-Stand, einmaliges Reparatur-/Testskript oder doppelte Migration: 9 Dateien
- Altes Projektarchiv: 63 Dateien
- Beispielkonfiguration, aktive lokale/Server-Konfigurationen sind vorhanden: 4 Dateien
- Generierte Protokoll-, Test-E-Mail- und Sicherheitsstatusdaten: 3 Dateien
- Git-Verwaltungsdaten (kein Bestandteil der laufenden Webseite): 531 Dateien
- Nicht eingebundene alte CSS-/JS-Datei oder Asset-Version: 8 Dateien
- Nicht eingebundene, alte Kontaktformular- und Mailer-Implementierung: 9 Dateien
- Nicht mehr referenziertes Hero-Bild (Startseite verwendet learning-Bilder): 4 Dateien
- Nicht referenziertes Bild bzw. altes Sammelbild: 4 Dateien
- Separater, nicht eingebundener Relaunch-Entwurf: 4 Dateien
- Unbenutztes HTML-Fragment: 1 Dateien
- Veraltete Hilfe, die aktuelle integrierte Hilfe bleibt erhalten: 1 Dateien
- Veraltetes SQL-Skript bzw. öffentlich erreichbares Diagnoseprogramm: 4 Dateien

## Erhalten

- `index.php`, öffentliche Fachseiten, Admin-/Login-/Portal-Seiten und aktive Includes
- Formularverarbeitung `kontakt-senden.php` mit `assets/php/classes/Contacts.php` und `includes/SmtpMailer.php`
- Referenzierte Stylesheets/Skripte einschließlich der im Asset-Manifest angegebenen Hash-Dateien
- Aktuelle Konfigurationen, `.htaccess`-Regeln und Webmanifest/Service Worker
- Bildressourcen mit statischen Referenzen und die meisten dynamisch nutzbaren Blockbilder
- Datenbank-Gesamtstand `database/easyit_stand_26.07.26.sql`, Migrationen und der SQL-Datensatz für reale Tutorbewertungen
- Die vollständige integrierte Blockeditor-Hilfe im Adminbereich

## Grenzen

Die Produktionsdatenbank wurde nicht eingesehen. Daher ist nicht sicher auszuschließen, dass dort Bildnamen hinterlegt sind, die in keiner PHP-Datei und keinem der mitgelieferten SQL-Stände vorkommen. Bitte nach dem Deployment insbesondere dynamische Startseiten-Blöcke und hochgeladene Bilder prüfen.

Die Live-Funktion (Datenbank, SMTP, Webserver-Konfiguration) wurde nicht getestet. Die PHP-Dateien sind statisch mit `php -l` geprüft worden, sofern der PHP-Interpreter verfügbar war.

Die Ausgangsdatei `nachhilfe.zip` bleibt unverändert.
