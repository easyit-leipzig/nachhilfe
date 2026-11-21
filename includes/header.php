<?php
declare(strict_types=1);

$site = $site ?? require __DIR__ . '/../config/site.php';
require_once __DIR__ . '/navigation.php';
require_once __DIR__ . '/internal-auth.php';
$currentInternalUser = internal_user(false);
?>
<a class="skip-link" href="#hauptinhalt">Direkt zum Inhalt</a>
<header class="site-header easyit-header">
    <div class="easyit-header__bar">
        <div class="header-top">
            <div class="brand easyit-brand">
                <a class="easyit-brand__logo-link" href="<?= e(app_path('/index.php')) ?>" aria-label="easyIT Nachhilfe – Startseite">
                    <img src="<?= e(app_path('/assets/img/brand-logo.svg')) ?>" alt="easyIT Nachhilfe mit Löwenlogo" width="1536" height="1024" decoding="async">
                </a>
                <div class="brand-copy easyit-brand__copy">
                    <a class="easyit-brand__home-link" href="<?= e(app_path('/index.php')) ?>">easyIT <span>Nachhilfe</span></a>
                    <span class="easyit-brand__subtitle">Nachhilfe mit System · Leipzig</span>
                </div>
            </div>
            <form class="site-search" role="search" action="<?= e(app_path('/suche.php')) ?>" method="get">
                <label class="sr-only" for="siteSearchInput">Website durchsuchen</label>
                <input id="siteSearchInput" name="q" type="search" placeholder="Was möchtest du lernen?" autocomplete="off">
                <ul id="siteSearchResults" class="site-search__results" hidden></ul>
            </form>
            <div class="header-actions" aria-label="Schnellzugriff">
                <a class="header-action header-action--contact" href="<?= e(app_path('/kontakt.php')) ?>">
                    <img src="<?= e(app_path('/assets/icons/contact.svg')) ?>" alt="" width="25" height="25"><span>Kontakt</span>
                </a>
                <?php if ($currentInternalUser): ?>
                    <a class="header-action" href="<?= e(internal_start_path($currentInternalUser)) ?>">
                        <img src="<?= e(app_path('/assets/icons/login.svg')) ?>" alt="" width="25" height="25"><span>Mein Bereich</span>
                    </a>
                <?php else: ?>
                    <a class="header-action" href="<?= e(app_path('/intern/login.php')) ?>">
                        <img src="<?= e(app_path('/assets/icons/login.svg')) ?>" alt="" width="25" height="25"><span>Anmelden</span>
                    </a>
                <?php endif; ?>
                <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation">
                    <span></span><span></span><span></span><span class="sr-only">Menü öffnen</span>
                </button>
            </div>
        </div>
    </div>
    <nav id="main-navigation" class="horizontal-nav" aria-label="Hauptnavigation">
        <?= render_horizontal_menu(horizontal_menu_items()) ?>
    </nav>
</header>
