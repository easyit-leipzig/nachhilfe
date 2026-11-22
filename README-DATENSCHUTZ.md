# Nachhilfe: Alfahosting-Datenschutzupdate (22.11.2026)

Dieses Paket ersetzt die bisherige Teilaktualisierung und enthält die **kompletten** Dateien
`config/privacy-nachhilfe.php`, `datenschutz.php`, `tools/check_datenschutz.php`.
Die bestehende Webseite wird nicht verändert; es handelt sich nur um das Datenschutz-Update.

## Eingetragene und überprüfte Angaben

- Hostinganbieter: **Alfahosting GmbH**, **Edmund-von-Lippmann-Straße 13–15, 06112 Halle (Saale)**; die alte Adresse *Ankerstraße 3b* wird nicht verwendet.
- Logfiles: Zugriffs- und Fehlerprotokolle; die Dokumentation von Alfahosting nennt insbesondere IP-Adresse, besuchte Webseite, Zugriffszeit, übertragene Bytes, Referrer, Browser und Betriebssystem. Fehler-Logdateien dienen der Diagnose fehlerhafter Anforderungen.
- IP-Anonymisierung: **nach 24 Stunden** (Nullung des letzten Oktetts).
- Löschung von Server-Logs: **spätestens nach 7 Tagen**.
- Backups: Webspace/Unterverzeichnisse, eingerichtete Datenbanken und bei Alfahosting gehostete E-Mail-Postfächer.
- Backupintervall und -frist: in der Regel alle **3 Stunden**, archiviert für **7 oder 14 Tage je nach Tarif**. Die tatsächliche Tarif-Frist ist **nicht verifiziert**. Daher ist `hoster_backup_days` weiterhin `null`, die veröffentlichte Beschreibung weist auf die Tarifabhängigkeit hin.
- Laufende Webdaten und Datenbanken haben **keine pauschal dokumentierte Löschfrist**. Darum `hoster_retention_days => null` und eine gesonderte Fristenregel.
- Der AVV-Status ist **nicht verifiziert** und bleibt `hoster_avv_concluded => null`.

## Trennung von Datenarten

**Server-Log-Daten** werden beim Zugriff technisch erhoben. **Webspace-, Datenbank- und Postfachinhalte** werden nur soweit gespeichert, wie Sie sie selbst über die Webseite, die Datenbank oder Alfahosting-E-Mail-Dienste bereitstellen. Die Einträge in `hoster_stored_data` und `hoster_backup_data` unterscheiden dies ausdrücklich. Deaktivieren oder ändern Sie Kategorien, die in Ihrem tatsächlichen Vertrag nicht vorkommen.

## Quellen (Alfahosting, eingesehen am 22.11.2026)

1. Datenschutz & DSGVO; Datenkategorien, IP-Anonymisierung, Löschung nach 7 Tagen, AVV: https://hilfe.alfahosting.de/hc/de/articles/20041611846033-Datenschutz-DSGVO
2. Welche Daten werden im Backup gesichert? Webspace, Datenbanken, Postfächer, 7 bzw. 14 Tage: https://hilfe.alfahosting.de/hc/de/articles/18733709531537-Welche-Daten-werden-im-Backup-gesichert-und-wie-weit-zur%C3%BCck-stehen-diese-zur-Verf%C3%BCgung
3. Access-, Error- und FTP-Logs: https://hilfe.alfahosting.de/hc/de/articles/18733688124049-Wo-finde-ich-Logfiles-zu-meiner-Webseite
4. Aktuelle Postanschrift: https://hilfe.alfahosting.de/hc/de/articles/31208668061201-Formular-Inhaberwechsel
5. Datenschutzhinweise des Hosters: https://alfahosting.de/datenschutz/
6. Allgemeine Aussage zu Deutschland als Hosting-Standort: https://alfahosting.de/webhosting/

## Installation und Prüfung

1. Vorhandene `datenschutz.php` und `config/privacy-nachhilfe.php` sichern.
2. Dateien aus diesem ZIP mit unveränderter Ordnerstruktur in das Nachhilfe-Verzeichnis kopieren.
3. Im Alfahosting-Kundencenter verifizieren, ob der gebuchte Tarif 7 oder 14 Tage Backup-Aufbewahrung enthält. Dann `hoster_backup_days` auf `7` oder `14` setzen **und** `hoster_backup_policy` gegebenenfalls leeren bzw. anpassen, damit keine widersprüchlichen Angaben bestehen.
4. Prüfen, ob E-Mail-Postfächer und eine Datenbank tatsächlich bei Alfahosting betrieben werden; ansonsten die betreffenden Datenkategorien entfernen.
5. Prüfen, ob ein AVV abgeschlossen ist (`hoster_avv_concluded`). Wenn ja, `true` setzen.
6. Mit `php tools/check_datenschutz.php --strict` technische und noch offene Konfigurationspunkte prüfen. Zurzeit soll der Test **wegen der tarifabhängigen Backup-Frist und des unbekannten AVV-Status** Warnungen melden.

**Wichtig:** Die Angaben für den verantwortlichen Webseitenbetreiber sind aus dem vorherigen Teil-Update übernommen; bitte vor Veröffentlichung mit dem Impressum prüfen. Es handelt sich um eine technische Vorlage, nicht um rechtliche Freigabe.
