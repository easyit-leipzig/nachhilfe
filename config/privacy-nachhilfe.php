<?php
/**
 * ZENTRALE DATENSCHUTZ-KONFIGURATION – Nachhilfe-Webseite
 *
 * Die Werte aus diesem Array werden in datenschutz.php ausgegeben.
 * Leere Strings, leere Listen und null = NOCH NICHT VERIFIZIERT.
 * Solche Platzhalter erscheinen NICHT als scheinbar bestätigte Fakten.
 *
 * WICHTIG: Nur die Angaben eintragen, die mit Hosting-Vertrag,
 * Hoster-Dokumentation und tatsächlicher Installation übereinstimmen.
 * Nicht als eigener Link veröffentlichen. PHP nur auf einem PHP-Server ausführen.
 */
declare(strict_types=1);

return [
    // Verantwortliche Stelle: vor Veröffentlichung mit Impressum abgleichen.
    'controller_name' => 'Dipl.-Ing. Olaf Thiele',
    'company' => 'easyIT Leipzig – Nachhilfe',
    'street' => 'An der Kotsche 1',
    'postal_code' => '04207',
    'city' => 'Leipzig',
    'email' => 'info@easyit-leipzig.de',
    'phone' => '0152 0178 34 27',

    // ----------------------------------------------------------------------
    // HOSTINGBETREIBER: Alfahosting GmbH
    // Offizielle Informationen: https://hilfe.alfahosting.de/hc/de/articles/20041611846033-Datenschutz-DSGVO
    // Aktuelle Postanschrift in der Alfahosting-Hilfe (Formular Inhaberwechsel):
    // https://hilfe.alfahosting.de/hc/de/articles/31208668061201-Formular-Inhaberwechsel
    // Die frühere Adresse „Ankerstraße 3b“ ist veraltet.
    // ----------------------------------------------------------------------
    'hoster_name' => 'Alfahosting GmbH',
    'hoster_street' => 'Edmund-von-Lippmann-Straße 13-15',
    'hoster_postal_code' => '06112',
    'hoster_city' => 'Halle (Saale)',
    'hoster_country' => 'Deutschland',
    // Deutschland wird allgemein beworben; individuellen Serverstandort ggf. im Vertrag prüfen.
    'hoster_server_location' => 'Deutschland (konkreter Standort abhängig vom Hostingvertrag)',
    'hoster_email' => 'datenschutzbeauftragter@alfahosting.de',
    'hoster_privacy_url' => 'https://alfahosting.de/datenschutz/',

    // ----------------------------------------------------------------------
    // Beim Hosting gespeicherte bzw. technisch verarbeitete Datenarten.
    // WICHTIG: Alfahosting unterscheidet eigene Server-Logs von Inhalten,
    // die der Websitebetreiber über PHP, Formulare, Datenbank oder E-Mail speichert.
    // Bedingte Kategorien sind ausdrücklich als solche gekennzeichnet.
    // ----------------------------------------------------------------------
    'hoster_stored_data' => [
        'Technische Zugriffsdaten und Server-Protokolle (Einzelheiten siehe unten)',
        'Webseiten- und Anwendungsdateien auf dem Webspace, gegebenenfalls einschließlich hochgeladener Inhalte',
        'Datenbankinhalte einschließlich personenbezogener Angaben aus Formularen, sofern die Webseite diese in einer Datenbank speichert',
        'E-Mails und Postfachinhalte, soweit E-Mail-Postfächer von Alfahosting verwendet werden',
    ],
    // Von Alfahosting ausdrücklich genannte Server-Log-Datenarten.
    // Access- und Error-Logs werden angelegt; die unten aufgeführten Angaben
    // sind die von Alfahosting dokumentierten Kategorien.
    'hoster_server_log_data' => [
        'IP-Adresse des zugreifenden Endgeräts',
        'Besuchte Webseite bzw. abgerufene Internetseite',
        'Zeitpunkt des Zugriffs',
        'Übertragene Datenmenge (in Byte)',
        'Quelle/Referrer (zuvor besuchte Seite, sofern übertragen)',
        'Verwendeter Webbrowser',
        'Verwendetes Betriebssystem',
        'Fehler- und Diagnoseinformationen bei fehlerhaften Zugriffen (Error-Logs)',
    ],
    // Laut Alfahosting-Continuous-Backup: je nach tatsächlich genutztem Dienst.
    // https://hilfe.alfahosting.de/hc/de/articles/18733709531537-Welche-Daten-werden-im-Backup-gesichert-und-wie-weit-stehen-diese-zur-Verfuegung
    'hoster_backup_data' => [
        'Webspace-Dateien und Verzeichnisse einschließlich Unterverzeichnissen',
        'Datenbanken im Webhosting-Paket, soweit eingerichtet',
        'E-Mail-Postfächer und E-Mail-Inhalte, soweit bei Alfahosting betrieben',
    ],

    // ----------------------------------------------------------------------
    // Speicherfristen laut Alfahosting Help Center (Abruf 22.11.2026).
    // Für reguläre Webspace-/Datenbank-/E-Mail-Daten gibt es keine einheitliche
    // öffentlich zugesicherte pauschale Löschfrist, daher keine Tageszahl.
    // ----------------------------------------------------------------------
    'hoster_retention_days' => null,
    'hoster_retention_policy' => 'Für laufend gespeicherte Webspace-Dateien, Datenbankinhalte und gegebenenfalls E-Mail-Postfächer gilt keine einheitliche pauschale Tagesfrist. Die Aufbewahrung richtet sich nach den eingesetzten Diensten, dem Hostingvertrag und den vom Webseitenbetreiber veranlassten Löschungen. Für Server-Logs und Sicherungskopien gelten gesonderte Fristen.',
    'server_log_days' => 7, // Laut Alfahosting: Löschung spätestens nach 7 Tagen.
    'server_log_ip_anonymization_hours' => 24, // Letztes Oktett der IP wird genullt.
    'hoster_backup_days' => null, // 7 ODER 14 Tage je nach gebuchtem Tarif: nicht raten!
    'hoster_backup_policy' => 'Alfahosting erstellt im Rahmen des tarifabhängigen Continuous Backup in der Regel alle 3 Stunden Sicherungskopien und archiviert sie je nach gebuchtem Tarif für 7 oder 14 Tage. Die konkret für diesen Hostingvertrag geltende Frist muss anhand des Tarifs geprüft werden.',
    // true erst setzen, wenn der AV-Vertrag tatsächlich abgeschlossen wurde.
    'hoster_avv_concluded' => null,

    // Separat: Frist für unverbindliche Kontaktanfragen, sofern verifiziert.
    'contact_storage_days' => null,

    'revision_date' => '22.11.2026',
];
