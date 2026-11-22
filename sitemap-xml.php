<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/site-architecture.php';
require_once __DIR__ . '/includes/content-repository.php';
require_once __DIR__ . '/includes/career-repository.php';
$site = require __DIR__ . '/config/site.php';

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex, follow');

$pages = [];
foreach (website_sitemap_public_urls() as $path => $title) {
    $pages[canonical_url($site, $path)] = null;
}

// Veröffentlichte Stellenprofile können im CMS deaktiviert werden.
try {
    foreach (career_all_jobs() as $job) {
        $slug = (string)($job['slug'] ?? '');
        if (preg_match('/^karriere-[a-z-]+\.php$/', $slug) === 1 && is_file(__DIR__ . '/' . $slug)) {
            $pages[canonical_url($site, '/' . $slug)] = null;
        }
    }
} catch (Throwable $e) {
    error_log('XML-Sitemap: Stellenprofile nicht abrufbar: ' . $e->getMessage());
}

// CMS-Entwürfe, Archive, Suchergebnisse und intern geschützte Seiten ausschließen.
try {
    foreach (cms_items('blog', 'published', 500, false) as $post) {
        $slug = (string)($post['slug'] ?? '');
        if (preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) !== 1) continue;
        $canonical = trim((string)($post['canonical_url'] ?? ''));
        $ownCanonical = canonical_url($site, '/blog-artikel.php', ['slug' => $slug]);
        if ($canonical !== '' && rtrim($canonical, '/') !== rtrim($ownCanonical, '/')) {
            // Extern kanonisierte Beiträge gehören nicht in die lokale Sitemap.
            continue;
        }
        $date = (string)($post['updated_at'] ?? $post['published_at'] ?? '');
        $timestamp = $date !== '' ? strtotime($date) : false;
        $pages[$ownCanonical] = $timestamp !== false ? gmdate('Y-m-d', $timestamp) : null;
    }
} catch (Throwable $e) {
    error_log('XML-Sitemap: Veröffentlichte Artikel nicht abrufbar: ' . $e->getMessage());
}

ksort($pages, SORT_STRING);
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as $url => $lastmod) {
    echo "  <url>\n    <loc>", htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8'), "</loc>\n";
    // lastmod nur für die tatsächlich gepflegten Veröffentlichungsdaten aus dem CMS.
    if ($lastmod !== null) echo '    <lastmod>', $lastmod, "</lastmod>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
