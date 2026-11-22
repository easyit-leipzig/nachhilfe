<?php
declare(strict_types=1);

$site = $site ?? require __DIR__ . '/../config/site.php';
require_once __DIR__ . '/navigation.php';
require_once __DIR__ . '/internal-auth.php';
$currentInternalUser = internal_user(false);
?>
<a class="skip-link" href="#hauptinhalt">Direkt zum Inhalt</a>
<header class="site-header easyit-header nh-site-header" id="seitenanfang">
    <div class="nh-header__bar">
        <div class="nh-header__inner">
            <a class="nh-header__brand" href="<?= e(app_path('/index.php')) ?>" aria-label="easyIT Nachhilfe Leipzig – Startseite">
                <img src="<?= e(app_path('/assets/img/easyit-leipzig-original.png')) ?>" alt="easyIT Leipzig" width="1635" height="735">
            </a>
            <div class="nh-header__identity">
                <a href="<?= e(app_path('/index.php')) ?>">Nachhilfe<span> in Leipzig</span></a>
                <small>Mathematik · Physik · Chemie · Informatik &amp; Sprachen</small>
            </div>
            <form class="site-search nh-header__search" role="search" action="<?= e(app_path('/suche.php')) ?>" method="get">
                <label class="sr-only" for="siteSearchInput">Website durchsuchen</label>
                <input id="siteSearchInput" name="q" type="search" placeholder="Was möchtest du lernen?" autocomplete="off">
                <ul id="siteSearchResults" class="site-search__results" hidden></ul>
            </form>
            <div class="nh-header__actions">
                <?php if ($currentInternalUser): ?>
                    <a class="nh-header__login" href="<?= e(internal_start_path($currentInternalUser)) ?>">Mein Bereich</a>
                <?php else: ?>
                    <a class="nh-header__login" href="<?= e(app_path('/intern/login.php')) ?>">Anmelden</a>
                <?php endif; ?>
                <a class="nh-header__contact" href="<?= e(app_path('/kontakt.php')) ?>">Probestunde anfragen</a>
                <button class="menu-toggle nh-header__mobile-toggle" type="button" aria-expanded="false" aria-controls="main-navigation">
                    <span></span><span></span><span></span><span class="sr-only">Menü öffnen</span>
                </button>
            </div>
        </div>
    </div>
    <nav id="main-navigation" class="horizontal-nav nh-header__navigation" aria-label="Hauptnavigation">
        <div class="nh-header__nav-inner"><?= render_horizontal_menu(horizontal_menu_items()) ?></div>
    </nav>
</header>
