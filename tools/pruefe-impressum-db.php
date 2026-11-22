<?php
/** Liest DB-Namen der aktiven PDO-Verbindung ohne Zugangsdaten anzuzeigen. */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('Nur CLI.'); }
$file = dirname(__DIR__) . '/includes/database.php';
if (!is_file($file)) { fwrite(STDERR, "Fehlt: includes/database.php\n"); exit(2); }
require_once $file;
if (!function_exists('db')) { fwrite(STDERR, "Funktion db() nicht vorhanden.\n"); exit(3); }
try {
    $connection = db();
    if (!$connection instanceof PDO) { throw new RuntimeException('db() liefert kein PDO-Objekt.'); }
    $active = (string)$connection->query('SELECT DATABASE()')->fetchColumn();
    echo "Aktive Datenbank: {$active}\n";
    if ($active === 'easyit_homepage') {
        echo "OK: Nachhilfe-Datenbank ist korrekt gewaehlt.\n";
        exit(0);
    }
    fwrite(STDERR, "FEHLER: Erwartet 'easyit_homepage'.\n");
    exit(4);
} catch (Throwable $exception) {
    fwrite(STDERR, "Datenbankpruefung fehlgeschlagen (" . $exception->getCode() . ").\n");
    fwrite(STDERR, "Bitte Datenbankname, Server, Benutzer und Passwort im vorhandenen Projekt pruefen.\n");
    exit(5);
}
