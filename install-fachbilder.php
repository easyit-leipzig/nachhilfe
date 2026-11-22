<?php
/**
 * easyIT Nachhilfe — Fachbilder Version 2
 * Aufruf aus dem Webseiten-Hauptverzeichnis: php install-fachbilder.php --check|--apply|--status
 * Nur CLI! Erstellt Backups, berührt keine externen Dateien.
 */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Nur mit PHP in der Kommandozeile verwendbar.');
}
$root = __DIR__;
$mode = in_array('--apply', $argv, true) ? 'apply' : (in_array('--status', $argv, true) ? 'status' : 'check');
$assets = $root . '/assets/fachbilder/';
if (!is_file($assets . 'fachbilder-runtime.js') || !is_file($assets . 'fachbilder.css') || !is_file($assets . 'fachbilder.json')) {
    fwrite(STDERR, "FEHLER: Fachbilder-Dateien fehlen. Das ZIP muss direkt im Stammverzeichnis der Website entpackt werden.\n");
    exit(2);
}
$aliases = [
 'mathematik' => ['mathe','mathematik'],
 'physik' => ['physik'],
 'chemie' => ['chemie'],
 'informatik' => ['informatik'],
 'deutsch' => ['deutsch'],
 'englisch' => ['englisch'],
 'franzoesisch' => ['franzoesisch','franzosisch','französisch'],
 'spanisch' => ['spanisch'],
 'latein' => ['latein'],
 'sprachen' => ['sprachen','fremdsprachen'],
 'biologie' => ['biologie'],
 'geschichte' => ['geschichte'],
 'geografie' => ['geografie','geographie','erdkunde'],
];
$exclude = ['admin','api','assets','cache','config','includes','vendor','node_modules','setup','.git','tests','migrations','logs','private','tmp','_fachbilder_backups','_fachbilder_backups_v2'];
$scan = new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator(
 new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
 static function (SplFileInfo $item) use ($exclude): bool {
   return !$item->isDir() || !in_array(strtolower($item->getFilename()), $exclude, true);
 }
));
function findFach(string $relative, array $aliases): ?string {
    $parts = explode('/', strtolower(str_replace('\\', '/', $relative)));
    $base = strtolower(pathinfo(end($parts), PATHINFO_FILENAME));
    $bits = [$base];
    if (in_array($base, ['index','start','uebersicht','nachhilfe','index-nachhilfe'], true) && count($parts)>1) {
        $bits[] = $parts[count($parts)-2];
    }
    foreach ($bits as $bit) {
        $bit = preg_replace('/[^\p{L}\d]+/u','-', $bit) ?? $bit;
        foreach ($aliases as $fach => $names) {
            foreach ($names as $alias) {
                if (preg_match('~(?:^|-)'.preg_quote($alias,'~').'(?:-|$)~ui', $bit)) return $fach;
            }
        }
    }
    return null;
}
/** Liefert die Position eines HTML-Tags nur dann, wenn es ausserhalb von PHP-Code steht. */
function htmlInsertPos(string $content): ?int {
    $offset = 0;
    $bodyPos = null;
    $mainPos = null;
    foreach (token_get_all($content) as $token) {
        $value = is_array($token) ? $token[1] : $token;
        if (is_array($token) && $token[0] === T_INLINE_HTML) {
            if (preg_match_all('~</body\s*>~i', $value, $m, PREG_OFFSET_CAPTURE)) {
                $last = end($m[0]); $bodyPos = $offset + $last[1];
            }
            if (preg_match_all('~</main\s*>~i', $value, $m, PREG_OFFSET_CAPTURE)) {
                $last = end($m[0]); $mainPos = $offset + $last[1];
            }
        }
        $offset += strlen($value);
    }
    return $bodyPos ?? $mainPos;
}
/** Ist der PHP-Parser am Dateiende innerhalb eines PHP-Codeblocks? */
function endsInPhp(string $content): bool {
    $inPhp = false;
    foreach (token_get_all($content) as $token) {
        if (is_array($token)) {
            if ($token[0] === T_OPEN_TAG || $token[0] === T_OPEN_TAG_WITH_ECHO) $inPhp = true;
            if ($token[0] === T_CLOSE_TAG || $token[0] === T_INLINE_HTML) $inPhp = false;
        }
    }
    return $inPhp;
}
$counter = ['found'=>0,'changed'=>0,'already'=>0,'failed'=>0];
$backup = $root.'/_fachbilder_backups_v2';
foreach ($scan as $file) {
 if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') continue;
 $relative = str_replace('\\','/',substr($file->getPathname(),strlen($root)+1));
 if (in_array($relative, ['install-fachbilder.php','Fachbilder-pruefen.php'], true)) continue;
 $fach = findFach($relative, $aliases);
 if ($fach === null) continue;
 $counter['found']++;
 $source = file_get_contents($file->getPathname());
 if ($source === false) {echo "FEHLER beim Lesen: $relative\n"; $counter['failed']++;continue;}
 $depth = substr_count($relative,'/');
 $url = str_repeat('../', $depth).'assets/fachbilder/fachbilder-runtime.js?v=2';
 $tag = '<script defer src="'.$url.'" data-fach="'.$fach.'"></script><!-- EASYIT-FACHBILDER-V2 -->';
 $existingV2 = preg_match('~<script\b[^>]*fachbilder-runtime\.js[^>]*>\s*</script>\s*<!--\s*EASYIT-FACHBILDER-V2\s*-->~is', $source);
 if ($existingV2) {
   echo "BEREITS V2 INSTALLIERT: $relative ($fach)\n";
   $counter['already']++;
   continue;
 }
 // Alte automatisch erzeugte Version entfernen, um vier Bilder nicht doppelt einzubauen.
 $clean = preg_replace('~\s*<\?php\s*/\*\s*EASYIT-FACHBILDER-START\s*\*/.*?/\*\s*EASYIT-FACHBILDER-END\s*\*/\s*\?>\s*~is','\n',$source);
 if (!is_string($clean)) {$counter['failed']++; continue;}
 $pos = htmlInsertPos($clean);
 if ($pos !== null) {
   $new = substr($clean,0,$pos)."\n".$tag."\n".substr($clean,$pos);
   $insertion = 'vor dem schliessenden HTML-Tag';
 } else {
   // Bei PHP-Routern mit zentralem Template existiert hier kein </body>.
   // Das JS funktioniert auch als vom PHP-Router ausgegebenes letztes Script.
   $new = $clean."\n".(endsInPhp($clean) ? 'echo '.var_export("\n".$tag."\n",true).';' : "\n".$tag)."\n";
   $insertion = 'am Ende des PHP-Routers (gemeinsames Template)';
 }
 echo ($mode==='apply'?'EINBAUEN':'GEPLANT').": $relative => $fach [4 Bilder; $insertion]\n";
 if ($mode !== 'apply') continue;
 $backupPath = $backup.'/'.$relative.'.original';
 if (!is_dir(dirname($backupPath)) && !mkdir(dirname($backupPath),0700,true) && !is_dir(dirname($backupPath))) {
   echo "  FEHLER Backup-Verzeichnis\n"; $counter['failed']++;continue;
 }
 if (!is_file($backupPath) && file_put_contents($backupPath,$source,LOCK_EX)===false) {
   echo "  FEHLER Backupdatei\n"; $counter['failed']++; continue;
 }
 $tmp = $file->getPathname().'.fachbilder-v2-tmp';
 if (file_put_contents($tmp,$new,LOCK_EX)===false || !rename($tmp,$file->getPathname())) {
    @unlink($tmp);echo "  FEHLER Schreiben\n";$counter['failed']++; continue;
 }
 $counter['changed']++;
}
if ($mode === 'apply' && $counter['changed']>0) {
    @file_put_contents($backup.'/.htaccess',"Require all denied\nDeny from all\n",LOCK_EX);
    @file_put_contents($backup.'/index.html','',LOCK_EX);
}
$missing = [];
foreach ($aliases as $fach=>$_) {
  for ($i=1;$i<=4;$i++) if(!is_file($assets.$fach.'-0'.$i.'.svg'))$missing[]=$fach.'-0'.$i.'.svg';
}
echo "\nFachseiten gefunden: ".$counter['found']." | Geaendert: ".$counter['changed']." | V2 vorhanden: ".$counter['already']." | Fehler: ".$counter['failed']."\n";
echo "Lokale SVG: ".(52-count($missing))." / 52\n";
if ($missing) echo "FEHLENDE BILDER: ".implode(', ',$missing)."\n";
if ($mode==='check')echo "Noch keine Datei veraendert. Zur Installation --apply verwenden.\n";
if ($counter['found']===0) {
    fwrite(STDERR,"FEHLER: Keine Fachseiten erkannt. Pruefen, ob ZIP wirklich im Nachhilfe-Hauptordner entpackt wurde.\n");
    exit(3);
}
if ($counter['failed']>0 || $missing)exit(4);
