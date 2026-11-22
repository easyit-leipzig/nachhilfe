<?php
/**
 * Sichere CLI-Reparatur fuer die Nachhilfe-Datenbankverbindung.
 * Aufruf: php tools/fix-impressum-db.php
 * Nur 'easyit' als DB-Name wird in vorhandenen Konfigurationsdateien ersetzt.
 * Andere Einstellungen, Benutzer und Passwoerter bleiben erhalten.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Nur ueber die Kommandozeile ausfuehrbar.');
}

$root = dirname(__DIR__);
$targetDb = 'easyit_homepage';
$oldDb = 'easyit';
$files = [
    $root . '/includes/database.php',
    $root . '/config/database.php',
    $root . '/config/db.php',
    $root . '/config/config.php',
    $root . '/config.php',
    $root . '/.env',
];

$changed = 0;
$backupDir = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR
    . 'nachhilfe-db-fix-' . date('Ymd-His') . '-' . getmypid();

foreach ($files as $path) {
    if (!is_file($path)) { continue; }
    $src = file_get_contents($path);
    if ($src === false) { fwrite(STDERR, "Nicht lesbar: {$path}\n"); exit(2); }

    $count = 0;
    $out = $src;
    // 1. Direkte MySQL-PDO-DSN-Angabe.
    $out = preg_replace('/(dbname\s*=\s*)easyit(?=\s*(?:;|[\'"\\\\]|$))/i', '${1}' . $targetDb, $out, -1, $n) ?? $out;
    $count += $n;

    // 2. String-Literal wie $dbName = 'easyit', 'database' => 'easyit', define('DB_NAME', 'easyit').
    // Nur wenn das Wort als kompletter, gequoteter Wert vorkommt. Andere Werte bleiben unberuehrt.
    $out = preg_replace('/([\'\"])easyit\\1(?![A-Za-z0-9_])/', '$1' . $targetDb . '$1', $out, -1, $n) ?? $out;
    $count += $n;

    // 3. .env-Key ohne Quotes.
    if (basename($path) === '.env') {
        $out = preg_replace('/^(\s*(?:DB_DATABASE|DB_NAME|MYSQL_DATABASE)\s*=\s*)easyit\s*$/mi', '${1}' . $targetDb, $out, -1, $n) ?? $out;
        $count += $n;
    }

    if ($count === 0 || $out === $src) { continue; }
    if (!is_dir($backupDir) && !mkdir($backupDir, 0700, true) && !is_dir($backupDir)) {
        fwrite(STDERR, "Backup-Verzeichnis nicht erstellbar. Abbruch.\n"); exit(3);
    }
    $relative = substr(str_replace('\\', '/', $path), strlen(str_replace('\\', '/', $root)) + 1);
    $backupName = str_replace('/', '__', $relative) . '.bak';
    if (file_put_contents($backupDir . DIRECTORY_SEPARATOR . $backupName, $src, LOCK_EX) === false) {
        fwrite(STDERR, "Backup fehlgeschlagen. Abbruch.\n"); exit(4);
    }
    if (file_put_contents($path, $out, LOCK_EX) === false) {
        fwrite(STDERR, "Schreiben fehlgeschlagen: {$path}\n"); exit(5);
    }
    $changed += $count;
    echo "AKTUALISIERT: {$relative} ({$count} Ersetzung/en)\n";
    echo "Sicherung: {$backupDir}" . DIRECTORY_SEPARATOR . "{$backupName}\n";
}

if ($changed === 0) {
    echo "Keine exakte Altangabe 'easyit' in den bekannten Datenbank-Konfigurationsdateien gefunden.\n";
    echo "Datei pruefen: nachhilfe/includes/database.php (Screenshot: Zeile 12).\n";
    echo "DB-Name muss lauten: {$targetDb}. Andere Zugangsdaten beibehalten.\n";
    exit(1);
}

echo "Erfolgreich korrigiert: Datenbankname -> {$targetDb}\n";
echo "Browser danach neu laden: http://localhost/nachhilfe/impressum.php\n";
