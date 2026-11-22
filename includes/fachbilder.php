<?php
/**
 * easyIT Nachhilfe: vier lokale Fachillustrationen je Fachseite.
 * Standalone, ohne Composer, externe Server oder Datenbank.
 */
if (!function_exists('easyit_render_fachbilder')) {
    function easyit_render_fachbilder(string $fach, string $relativerPfad = ''): void
    {
        $erlaubteFaecher = [
            'mathematik', 'physik', 'chemie', 'informatik', 'deutsch', 'englisch',
            'franzoesisch', 'spanisch', 'latein', 'sprachen', 'biologie',
            'geschichte', 'geografie'
        ];
        if (!in_array($fach, $erlaubteFaecher, true)) {
            return;
        }
        $datei = __DIR__ . '/../assets/fachbilder/fachbilder.json';
        $manifest = is_file($datei) ? json_decode((string)file_get_contents($datei), true) : null;
        if (!is_array($manifest) || empty($manifest[$fach]['bilder'])) {
            return;
        }
        $daten = $manifest[$fach];
        $name = htmlspecialchars((string)$daten['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $pfad = rtrim(str_replace('\\', '/', $relativerPfad), '/');
        $basis = $pfad === '' ? '' : $pfad . '/';
        $assets = $basis . 'assets/fachbilder/';
        $esc = static fn($s) => htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        echo "\n<!-- easyIT Fachbilder: START -->\n";
        echo '<link rel="stylesheet" href="' . $esc($assets . 'fachbilder.css') . '">' . "\n";
        echo '<section class="easyit-fachbilder" aria-labelledby="easyit-fachbilder-heading">' . "\n";
        echo '  <div class="easyit-fachbilder__intro">';
        echo '    <p class="easyit-fachbilder__eyebrow">' . $name . ' anschaulich lernen</p>';
        echo '    <h2 id="easyit-fachbilder-heading">Themen aus ' . $name . ' entdecken</h2>';
        echo '    <p>Vier anschauliche Einblicke in die Themen der individuellen Nachhilfe.</p>';
        echo '  </div>' . "\n";
        echo '  <div class="easyit-fachbilder__grid">' . "\n";
        foreach ($daten['bilder'] as $eintrag) {
            $src = $assets . $eintrag['datei'];
            $titel = $esc($eintrag['titel']);
            $beschreibung = $esc($eintrag['beschreibung']);
            $alt = $esc($daten['name'] . ': ' . $eintrag['titel']);
            echo '    <figure class="easyit-fachbilder__card">';
            echo '<img src="' . $esc($src) . '" alt="' . $alt . '" width="1200" height="720" loading="lazy" decoding="async">';
            echo '<figcaption><strong>' . $titel . '</strong><span>' . $beschreibung . '</span></figcaption>';
            echo '</figure>' . "\n";
        }
        echo '  </div>' . "\n";
        echo '</section>' . "\n";
        // Defekte Bilder im vorhandenen <main> erhalten eine lokale fachbezogene Illustration.
        // Header, Logos und bereits funktionierende Bilder bleiben unverändert.
        $fallback = $assets . $daten['bilder'][0]['datei'];
        echo '<script src="' . $esc($assets . 'fachbilder.js') . '" data-fachbilder-fallback="' . $esc($fallback) . '"></script>' . "\n";
        echo "<!-- easyIT Fachbilder: ENDE -->\n";
    }
}
