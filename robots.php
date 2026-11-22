<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
$site = require __DIR__ . '/config/site.php';
header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
$base = rtrim((string)$site['base_url'], '/');
// Auf Neben-Hosts niemals eine falsche produktive Domain als eigene Sitemap behaupten.
echo "User-agent: *\n";
echo "Allow: /\n";
foreach (['admin','intern','config','includes','database','storage','cache','errors','uploads'] as $dir) {
    echo 'Disallow: /', $dir, "/\n";
}
echo "Disallow: /offline.php\nDisallow: /anfrage-erfolgreich.php\nDisallow: /kontakt-senden.php\n";
echo "Disallow: /*?q=\nDisallow: /*?fach=\n";
echo "Sitemap: ", $base, "/sitemap.xml\n";
