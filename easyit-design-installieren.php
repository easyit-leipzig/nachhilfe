<?php
/**
 * easyIT Nachhilfe — globales dunkelblaues Design
 * Aufruf: php easyit-design-installieren.php --check | --apply | --status | --restore
 * Sichere, wiederholbare Bearbeitung von PHP/HTML-Kopfbloecken.
 */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Dieses Installationsprogramm kann nur lokal über die Kommandozeile aufgerufen werden.');
}

$root = __DIR__;
$mode = in_array('--apply', $argv, true) ? 'apply' :
    (in_array('--restore', $argv, true) ? 'restore' :
    (in_array('--status', $argv, true) ? 'status' : 'check'));
$css = $root . '/assets/css/easyit-dark-unify.css';
$js = $root . '/assets/js/easyit-dark-unify.js';
$helper = $root . '/includes/easyit-theme-head.php';
$backupDir = $root . '/private/_easyit_design_backups';
$manifestPath = $backupDir . '/manifest.json';
$marker = 'easyIT-GLOBAL-DARK-DESIGN-v1';

if (!is_file($css) || !is_file($js) || !is_file($helper)) {
    fwrite(STDERR, "FEHLER: Die Design-Dateien fehlen. ZIP direkt in das Website-Hauptverzeichnis entpacken.\n");
    exit(2);
}

function normalizeRel(string $relative): string {
    return str_replace('\\', '/', $relative);
}
function markContent(string $content, string $ext, string $marker): ?array {
    if (str_contains($content, $marker)) return ['already' => true];
    if ($ext === 'php' || $ext === 'phtml') {
        $tokens = token_get_all($content);
        $offset = 0;
        foreach ($tokens as $token) {
            $text = is_array($token) ? $token[1] : $token;
            if (is_array($token) && $token[0] === T_INLINE_HTML && preg_match('~</head\s*>~i', $text, $m, PREG_OFFSET_CAPTURE)) {
                return ['position' => $offset + $m[0][1], 'already' => false];
            }
            $offset += strlen($text);
        }
        return null;
    }
    if (preg_match('~</head\s*>~i', $content, $m, PREG_OFFSET_CAPTURE)) {
        return ['position' => $m[0][1], 'already' => false];
    }
    return null;
}
function listHtmlFiles(string $root): array {
    $excludeDirs = [
      '.git', '.svn', '.idea', 'node_modules', 'vendor', 'assets', 'cache', 'tmp',
      'logs', 'log', 'private', 'storage', 'backups', 'tests', 'test', 'dist', 'build',
      'third_party', 'third-party', '_fachbilder_backups', '_fachbilder_backups_v2',
    ];
    $iter = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
            static function (SplFileInfo $file) use ($excludeDirs): bool {
                if ($file->isLink()) return false;
                if ($file->isDir()) {
                    $name = strtolower($file->getFilename());
                    return !in_array($name, $excludeDirs, true) &&
                        !str_starts_with($name, '_easyit_design_backups');
                }
                return $file->isFile();
            }
        )
    );
    $files = [];
    foreach ($iter as $file) {
        if ($file->getSize() > 3 * 1024 * 1024) continue;
        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        if (!in_array($ext, ['php', 'phtml', 'html', 'htm'], true)) continue;
        if (in_array(strtolower($file->getFilename()),
            ['easyit-design-installieren.php', 'design-vorschau.html', 'easyit-theme-head.php'], true)) continue;
        $files[] = (string) $file->getPathname();
    }
    sort($files);
    return $files;
}
function relToRoot(string $root, string $file): string {
    return normalizeRel(substr($file, strlen($root) + 1));
}
function makeSnippet(string $root, string $file, string $ext, string $marker): string {
    if ($ext === 'php' || $ext === 'phtml') {
        $rel = normalizeRel(dirname(relToRoot($root, $file)));
        $up = ($rel === '.' || $rel === '') ? '' : str_repeat('../', count(explode('/', $rel)));
        return "<!-- {$marker}:START -->\n" .
          "<?php require_once __DIR__ . '/{$up}includes/easyit-theme-head.php'; ?>\n" .
          "<!-- {$marker}:END -->\n";
    }
    $rel = normalizeRel(dirname(relToRoot($root, $file)));
    $up = ($rel === '.' || $rel === '') ? '' : str_repeat('../', count(explode('/', $rel)));
    return "<!-- {$marker}:START -->\n" .
      '<link rel="stylesheet" href="' . $up . 'assets/css/easyit-dark-unify.css?v=1.0.0">' . "\n" .
      '<script defer src="' . $up . 'assets/js/easyit-dark-unify.js?v=1.0.0"></script>' . "\n" .
      "<!-- {$marker}:END -->\n";
}
function safeMkdir(string $path): void {
    if (!is_dir($path) && !mkdir($path, 0700, true) && !is_dir($path)) {
        throw new RuntimeException('Ordner konnte nicht erstellt werden: ' . $path);
    }
}
function manifestRead(string $path): array {
    $json = is_file($path) ? json_decode((string) file_get_contents($path), true) : null;
    return is_array($json) ? $json : ['format' => 1, 'files' => []];
}
function manifestWrite(string $path, array $data): void {
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) === false || !rename($tmp, $path)) {
        throw new RuntimeException('Manifest konnte nicht gespeichert werden.');
    }
}

if ($mode === 'restore') {
    $manifest = manifestRead($manifestPath);
    if (empty($manifest['files'])) {
        echo "Keine Installation zurueckzusetzen.\n";
        exit(0);
    }
    $restored = 0;
    $skipped = 0;
    foreach ($manifest['files'] as $rel => $info) {
        $path = $root . '/' . $rel;
        $backup = $backupDir . '/' . $info['backup'];
        if (!is_file($path) || !is_file($backup)) { echo "NICHT GEFUNDEN: {$rel}\n"; $skipped++; continue; }
        if (hash_file('sha256', $path) !== $info['installed_sha256']) {
            echo "SPAETER BEARBEITET — NICHT UEBERSCHRIEBEN: {$rel}\n";
            $skipped++;
            continue;
        }
        if (!copy($backup, $path)) { echo "FEHLER: {$rel}\n"; $skipped++; continue; }
        $restored++;
        echo "WIEDERHERGESTELLT: {$rel}\n";
        unset($manifest['files'][$rel]);
    }
    manifestWrite($manifestPath, $manifest);
    echo "Ergebnis: {$restored} wiederhergestellt, {$skipped} nicht zurueckgesetzt.\n";
    exit($skipped ? 1 : 0);
}

$files = listHtmlFiles($root);
$findings = [];
$already = 0;
foreach ($files as $path) {
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $content = file_get_contents($path);
    if ($content === false) continue;
    $match = markContent($content, $ext, $marker);
    if ($match === null) continue;
    if ($match['already']) { $already++; continue; }
    $findings[] = ['path' => $path, 'content' => $content,
       'position' => $match['position'], 'ext' => $ext];
}
echo "easyIT Nachhilfe – Einheitliches Dunkeldesign\n";
echo "Verzeichnis: {$root}\n";
echo 'PHP/HTML-Dateien: ' . count($files) . '; bereits integriert: ' . $already .
    '; neu geeignet: ' . count($findings) . "\n";
foreach ($findings as $f) echo '  + ' . relToRoot($root, $f['path']) . "\n";

if ($mode === 'status' || $mode === 'check') {
    echo $mode === 'status' ? "Nur Status; keine Dateien geaendert.\n" :
        "Nur Pruefung; Installation mit --apply.\n";
    exit(count($findings) + $already === 0 ? 1 : 0);
}
if (count($findings) + $already === 0) {
    fwrite(STDERR, "Keine HTML-Kopfbereiche erkannt. Keine Aenderungen vorgenommen.\n");
    exit(1);
}

safeMkdir($backupDir);
$htaccess = $backupDir . '/.htaccess';
if (!is_file($htaccess)) file_put_contents($htaccess,
    "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n");
$manifest = manifestRead($manifestPath);
$written = 0;
foreach ($findings as $f) {
    $rel = relToRoot($root, $f['path']);
    $snippet = makeSnippet($root, $f['path'], $f['ext'], $marker);
    $out = substr_replace($f['content'], $snippet, $f['position'], 0);
    if (!isset($manifest['files'][$rel])) {
        $backupRel = 'files/' . $rel . '.bak';
        safeMkdir(dirname($backupDir . '/' . $backupRel));
        if (file_put_contents($backupDir . '/' . $backupRel, $f['content']) === false) {
            echo "FEHLER BEIM BACKUP: {$rel}\n";
            continue;
        }
        $manifest['files'][$rel] = [
            'backup' => $backupRel,
            'original_sha256' => hash('sha256', $f['content']),
            'installed_sha256' => hash('sha256', $out)
        ];
    }
    $tempFile = $f['path'] . '.easyit-tmp';
    if (file_put_contents($tempFile, $out) === false || !rename($tempFile, $f['path'])) {
        echo "FEHLER BEIM SCHREIBEN: {$rel}\n";
        @unlink($tempFile);
        continue;
    }
    $written++;
    echo "AKTUALISIERT: {$rel}\n";
}
manifestWrite($manifestPath, $manifest);
echo "FERTIG: {$written} Dateien aktualisiert. Originale liegen unter private/_easyit_design_backups/.\n";
echo "Test: http://localhost/nachhilfe/notenrechner.php (Strg+F5)\n";
exit(0);
