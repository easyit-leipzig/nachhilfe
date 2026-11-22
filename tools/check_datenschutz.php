<?php
/**
 * Aus dem Projektverzeichnis: php tools/check_datenschutz.php
 * Vor Produktivsetzung:       php tools/check_datenschutz.php --strict
 * Der Test prüft Syntax und Struktur, nicht die sachliche Richtigkeit.
 */
declare(strict_types=1);
$root = dirname(__DIR__);
$required = ['datenschutz.php', 'config/privacy-nachhilfe.php'];
foreach ($required as $name) {
    if (!is_file($root . '/' . $name)) {
        fwrite(STDERR, "FEHLT: $name\n");
        exit(2);
    }
}
$page = file_get_contents($root . '/datenschutz.php');
$cfg = require $root . '/config/privacy-nachhilfe.php';
if (!is_array($cfg)) { fwrite(STDERR, "Konfiguration ist kein Array\n"); exit(3); }
$errors = [];
$warnings = [];
foreach (['controller_name', 'street', 'postal_code', 'city', 'email'] as $key) {
    if (empty(trim((string)($cfg[$key] ?? '')))) { $errors[] = "Verantwortlicher: $key fehlt"; }
}
if (!filter_var((string)($cfg['email'] ?? ''), FILTER_VALIDATE_EMAIL)) { $errors[] = 'Ungültige E-Mail-Adresse'; }
foreach (['Lorem ipsum', 'Musterstraße', 'Musterstadt', 'example.com', 'hier eintragen', 'TODO', '[E-Mail]', '[Adresse]', 'Förderverein Zentrum für Drogenhilfe', 'Google Analytics', 'Alfahosting GmbH'] as $marker) {
    if (stripos($page, $marker) !== false) { $errors[] = "Unerwünschter Text in datenschutz.php: $marker"; }
}
$hostingKeys = ['hoster_name', 'hoster_street', 'hoster_postal_code', 'hoster_city', 'hoster_country', 'hoster_server_location', 'hoster_email', 'hoster_privacy_url', 'hoster_stored_data', 'hoster_server_log_data', 'hoster_backup_data', 'hoster_retention_days', 'hoster_retention_policy', 'server_log_days', 'server_log_ip_anonymization_hours', 'hoster_backup_days', 'hoster_backup_policy', 'hoster_avv_concluded'];
foreach ($hostingKeys as $key) {
    if (!array_key_exists($key, $cfg)) { $errors[] = "Hosting-Konfigurationsfeld fehlt: $key"; }
    if (strpos($page, "'$key'") === false && strpos($page, '"' . $key . '"') === false) {
        $errors[] = "Hosting-Konfigurationsfeld wird in datenschutz.php nicht gelesen: $key";
    }
}
foreach (['hoster_stored_data', 'hoster_server_log_data', 'hoster_backup_data'] as $key) {
    if (isset($cfg[$key]) && (!is_array($cfg[$key]) || count(array_filter($cfg[$key], 'is_string')) !== count($cfg[$key]))) {
        $errors[] = "$key muss eine Liste von Texten sein";
    }
}
foreach (['hoster_retention_days', 'server_log_days', 'server_log_ip_anonymization_hours', 'hoster_backup_days', 'contact_storage_days'] as $key) {
    $value = $cfg[$key] ?? null;
    if ($value !== null && (!is_int($value) || $value <= 0)) { $errors[] = "$key muss null oder eine positive ganze Zahl sein"; }
}
if (!in_array($cfg['hoster_avv_concluded'] ?? null, [null, true, false], true)) {
    $errors[] = 'hoster_avv_concluded muss null, true oder false sein';
}
if (!empty($cfg['hoster_privacy_url'])) {
    $url = (string)$cfg['hoster_privacy_url'];
    $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
    if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array($scheme, ['http', 'https'], true)) {
        $errors[] = 'hoster_privacy_url muss eine gültige http(s)-URL sein';
    }
}
foreach (['datenschutz.php', 'config/privacy-nachhilfe.php'] as $filename) {
    $output = [];
    $code = 0;
    exec('php -l ' . escapeshellarg($root . '/' . $filename) . ' 2>&1', $output, $code);
    if ($code !== 0) { $errors[] = "PHP-Syntaxfehler in $filename: " . implode(' ', $output); }
}
$present = static fn(string $key): bool => isset($cfg[$key]) && trim((string)$cfg[$key]) !== '';
if (!$present('hoster_name')) { $warnings[] = 'Name des Hostingbetreibers nicht eingetragen'; }
if (!$present('hoster_server_location')) { $warnings[] = 'Server-/Rechenzentrumsstandort nicht eingetragen'; }
if (empty($cfg['hoster_stored_data'])) { $warnings[] = 'Gespeicherte Datenarten beim Hoster fehlen'; }
if (empty($cfg['hoster_server_log_data'])) { $warnings[] = 'Konkrete Server-Log-Datenarten fehlen'; }
if (!$present('hoster_retention_days') && !$present('hoster_retention_policy')) { $warnings[] = 'Speicherdauer bzw. Löschregel beim Hoster fehlt'; }
if (!$present('server_log_days')) { $warnings[] = 'Konkrete Speicherdauer der Server-Logs fehlt'; }
if (!empty($cfg['hoster_backup_data']) && !$present('hoster_backup_days') && !$present('hoster_backup_policy')) {
    $warnings[] = 'Backup-Daten genannt, aber keine Backup-Frist bzw. Löschregel hinterlegt';
}
if (empty($cfg['hoster_backup_days']) &&
    stripos((string)($cfg['hoster_backup_policy'] ?? ''), '7 oder 14 Tage') !== false) {
    $warnings[] = 'Tarifabhängige Backup-Frist: 7 oder 14 Tage im eigenen Hostingvertrag prüfen';
}
if (($cfg['hoster_avv_concluded'] ?? null) === null) { $warnings[] = 'AVV-Status noch nicht geprüft'; }
if ($errors) { fwrite(STDERR, "FEHLER:\n- " . implode("\n- ", $errors) . "\n"); exit(1); }
echo "OK: PHP-Syntax, Konfigurationsfelder und dynamische Einbindung geprüft.\n";
if ($warnings) { echo "NOCH ZU PRÜFEN:\n- " . implode("\n- ", $warnings) . "\n"; }
if (in_array('--strict', $argv, true) && $warnings) { exit(4); }
echo "Hinweis: Eine fachliche Prüfung der tatsächlich verarbeiteten Daten ist zusätzlich nötig.\n";
