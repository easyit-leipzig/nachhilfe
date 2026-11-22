<?php
$site = $site ?? require __DIR__ . '/../config/site.php';
?>
<footer class="site-footer easyit-footer" aria-label="Fußbereich der Webseite">
    <div class="easyit-footer__grid">
        <div class="easyit-footer__identity">
            <a href="<?= e(app_path('/index.php')) ?>" class="easyit-footer__brand" aria-label="easyIT Nachhilfe – Startseite">
                <img src="<?= e(app_path('/assets/img/easyit-leipzig-original.png')) ?>" alt="easyIT Nachhilfe" width="1536" height="1024" loading="lazy">
                <span><strong>Nachhilfe in Leipzig</strong><small>Ein Angebot von easyIT Leipzig</small></span>
            </a>
            <p>Naturwissenschaften, Sprachen und Soziales. Individuelle Nachhilfe für Leipzig und Umgebung – strukturiert, verständlich und persönlich.</p>
        </div>
        <nav class="easyit-footer__links" aria-label="Schnellnavigation">
            <h2>Nachhilfe entdecken</h2>
            <a href="<?= e(app_path('/faecher.php')) ?>">Unsere Fächer</a>
            <a href="<?= e(app_path('/methodik.php')) ?>">Methodik</a>
            <a href="<?= e(app_path('/preise.php')) ?>">Preise &amp; Ablauf</a>
            <a href="<?= e(app_path('/bewertungen.php')) ?>">Erfahrungen</a>
            
        </nav>
        <div class="easyit-footer__contact">
            <h2>Kontakt</h2>
            <?php if (!empty($site['email'])): ?>
                <a href="mailto:<?= e((string)$site['email']) ?>"><?= e((string)$site['email']) ?></a>
            <?php endif; ?>
            <?php if (!empty($site['phone'])): ?>
                <a href="tel:<?= e(preg_replace('/[^+0-9]/', '', (string)$site['phone'])) ?>"><?= e((string)$site['phone']) ?></a>
            <?php endif; ?>
            <a class="easyit-footer__cta" href="<?= e(app_path('/kontakt.php')) ?>">Nachhilfe anfragen <span aria-hidden="true">→</span></a>
        </div>
    </div>
    <div class="easyit-footer__bottom">
        <p>© <?= date('Y') ?> <?= e((string)$site['site_name']) ?></p>
        <nav aria-label="Rechtliche Informationen">
            <a href="<?= e(app_path('/impressum.php')) ?>">Impressum</a>
            <a href="<?= e(app_path('/datenschutz.php')) ?>">Datenschutz</a>
            <a href="<?= e(app_path('/bildnachweis.php')) ?>">Bildnachweise</a>
            <a href="<?= e(app_path('/sitemap.php')) ?>">Sitemap</a>
        </nav>
    </div>
</footer>
<script src="<?= e(asset_url('assets/js/app.js')) ?>" defer></script>
<script src="<?= e(asset_url('assets/js/menu-flag.js')) ?>" defer></script>
<script src="<?= e(asset_url('assets/js/search-index.js')) ?>" defer></script>
<script src="<?= e(asset_url('assets/js/search.js')) ?>" defer></script>
<script src="<?= e(asset_url('assets/js/consent.js')) ?>" defer></script>
<script src="<?= e(asset_url('assets/js/analytics.js')) ?>" defer></script>
<script nonce="<?= e(security_csp_nonce()) ?>">
if ('serviceWorker' in navigator) {window.addEventListener('load',function(){navigator.serviceWorker.register(<?= json_encode(app_path('/service-worker.js'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>);});}
</script>
