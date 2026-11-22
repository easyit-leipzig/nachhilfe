<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/site-architecture.php';
require_once __DIR__ . '/includes/content-repository.php';
require_once __DIR__ . '/includes/career-repository.php';
$site = require __DIR__ . '/config/site.php';
$groups = website_sitemap_groups();

$careerPages = [];
foreach (career_all_jobs() as $job) {
    $slug = (string)($job['slug'] ?? '');
    if (preg_match('/^karriere-[a-z-]+\.php$/', $slug) === 1 && is_file(__DIR__ . '/' . $slug)) {
        $careerPages[] = ['title' => (string)($job['title'] ?? 'Stellenprofil'), 'url' => '/' . $slug];
    }
}
if ($careerPages !== []) {
    foreach ($groups as &$group) {
        if ($group['title'] === 'Über uns & Karriere') $group['links'] = array_merge($group['links'], $careerPages);
    }
    unset($group);
}

$publishedPosts = [];
try {
    foreach (cms_items('blog', 'published', 500, false) as $post) {
        $slug = (string)($post['slug'] ?? '');
        if (preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) !== 1) continue;
        $publishedPosts[] = ['title' => (string)($post['title'] ?? 'Blogartikel'), 'url' => '/blog-artikel.php?slug=' . rawurlencode($slug)];
    }
} catch (Throwable $e) {
    error_log('Sitemap: Blogartikel nicht abrufbar: ' . $e->getMessage());
}
if ($publishedPosts !== []) {
    foreach ($groups as &$group) {
        if ($group['title'] === 'Wissen & Lernwerkzeuge') $group['links'] = array_merge($group['links'], $publishedPosts);
    }
    unset($group);
}
$count = array_sum(array_map(static fn(array $group): int => count($group['links']), $groups));
$pageTitle = 'Sitemap – alle Seiten | easyIT Nachhilfe Leipzig';
$pageDescription = 'Alle öffentlichen Seiten von easyIT Nachhilfe in Leipzig: Fächer, Klassenstufen, Stadtteile, Preise, Lernwerkzeuge und Kontakt.';
$pageCanonical = canonical_url($site, '/sitemap.php');
?><!doctype html><html lang="de" data-theme="leipzig-blau"><head><?php require __DIR__ . '/includes/meta.php'; ?>
<style nonce="<?= e(security_csp_nonce()) ?>">
.sitemap-intro{max-width:75ch;color:var(--muted,#cde1ed);line-height:1.65}
.sitemap-index{display:flex;flex-wrap:wrap;gap:.55rem;margin:1.5rem 0 2rem}
.sitemap-index a{border:1px solid var(--line,rgba(80,217,243,.3));background:#123a5a;color:#eafbff;text-decoration:none;padding:.55rem .8rem;border-radius:999px;font-weight:650}
.sitemap-index a:hover{background:#1b5479;color:#fff}
.sitemap-groups{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,280px),1fr));gap:1.2rem}
.sitemap-group{border:1px solid var(--line,rgba(80,217,243,.3));border-radius:18px;padding:clamp(1.1rem,2.5vw,1.55rem);background:linear-gradient(155deg,#133957,#0b2846)}
.sitemap-group h2{margin:0 0 .55rem;color:#fff;font-size:1.2rem}
.sitemap-group p{margin:0 0 1rem;color:#c0d9e9;font-size:.94rem}
.sitemap-group ul{padding:0;margin:0;list-style:none;display:grid;gap:.2rem}
.sitemap-group li a{display:block;border-radius:8px;padding:.44rem .55rem;color:#e8f6fe;text-decoration:none}
.sitemap-group li a:hover,.sitemap-group li a:focus-visible{background:rgba(80,217,243,.15);color:#fff;text-decoration:underline}
.sitemap-group li a::before{content:'›';color:var(--cyan,#50d9f3);margin-right:.5em;font-weight:900}
</style><!-- easyIT-GLOBAL-DARK-DESIGN-v1:START -->
<?php require_once __DIR__ . '/includes/easyit-theme-head.php'; ?>
<!-- easyIT-GLOBAL-DARK-DESIGN-v1:END -->
</head><body>
<?php require __DIR__ . '/includes/header.php'; ?>
<div class="page-shell"><?php require __DIR__ . '/includes/sidebar.php'; ?>
<main id="hauptinhalt" class="main-content"><div class="content-wrap">
<nav class="breadcrumbs" aria-label="Brotkrumen"><a href="<?= e(app_path('/')) ?>">Startseite</a><span>›</span><span aria-current="page">Sitemap</span></nav>
<section class="section">
  <span class="eyebrow">Alle öffentlichen Seiten auf einen Blick</span>
  <h1>Sitemap</h1>
  <p class="sitemap-intro">Finde direkt das passende Fach, die Schulform, deinen Leipziger Stadtteil oder Informationen rund um unsere Nachhilfe. Die Übersicht enthält <?= (int)$count ?> Verweise in <?= count($groups) ?> Bereichen.</p>
  <nav class="sitemap-index" aria-label="Sitemap-Bereiche">
    <?php foreach ($groups as $index => $group): ?><a href="#sitemap-<?= (int)$index ?>"><?= e($group['title']) ?></a><?php endforeach; ?>
  </nav>
  <div class="sitemap-groups">
    <?php foreach ($groups as $index => $group): ?>
      <section id="sitemap-<?= (int)$index ?>" class="sitemap-group" aria-labelledby="sitemap-heading-<?= (int)$index ?>">
        <h2 id="sitemap-heading-<?= (int)$index ?>"><?= e($group['title']) ?></h2>
        <p><?= e($group['intro']) ?></p>
        <ul><?php foreach ($group['links'] as $link): ?>
          <li><a href="<?= e(app_path($link['url'])) ?>"><?= e($link['title']) ?></a></li>
        <?php endforeach; ?></ul>
      </section>
    <?php endforeach; ?>
  </div>
</section>
</div></main></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body></html>
