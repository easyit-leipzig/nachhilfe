<?php
declare(strict_types=1);

/**
 * Öffentliche Informationsarchitektur als eine gemeinsame Quelle.
 * Verwendet vorhandene Fach-, Schulform-, Stadtteil- und Werkzeugkonfigurationen.
 * Adminbereiche, Suchergebnisse, Formulare und Vorschauseiten gehören nicht in Sitemaps.
 */
function website_sitemap_groups(): array
{
    $subjects = require __DIR__ . '/../config/subjects.php';
    $schools = require __DIR__ . '/../config/school-types.php';
    $areas = require __DIR__ . '/../config/areas.php';
    $tools = require __DIR__ . '/../config/tools.php';

    $entries = static function (array $items): array {
        $result = [];
        foreach ($items as $item) {
            $path = (string)($item['file'] ?? '');
            if ($path === '' || !is_file(__DIR__ . '/../' . $path)) continue;
            $name = (string)($item['name'] ?? $path);
            $result[] = ['title' => $name, 'url' => '/' . ltrim($path, '/')];
        }
        return $result;
    };

    $static = static function (array $map): array {
        $result = [];
        foreach ($map as $url => $title) {
            if ($url !== '/' && !is_file(__DIR__ . '/../' . ltrim($url, '/'))) continue;
            $result[] = ['title' => $title, 'url' => $url];
        }
        return $result;
    };

    return [
        ['title' => 'Startseite', 'intro' => 'Der Einstieg in die Nachhilfe.', 'links' => $static(['/' => 'Startseite'])],
        ['title' => 'Fächer', 'intro' => 'Naturwissenschaften, Sprachen und gesellschaftliche Fächer.', 'links' => array_merge($static(['/faecher.php' => 'Alle Fächer']), $entries($subjects))],
        ['title' => 'Lernstufen', 'intro' => 'Von der Grundschule bis zum Studium.', 'links' => array_merge($static(['/schulformen.php' => 'Alle Schulformen']), $entries($schools))],
        ['title' => 'Leipzig & Stadtteile', 'intro' => 'Standorte und Nachhilfe in Leipzig.', 'links' => array_merge($static(['/nachhilfe-in-leipzig.php' => 'Stadtteilübersicht']), $entries($areas))],
        ['title' => 'Ablauf & Preise', 'intro' => 'Unterricht, Organisation und Erfahrungen.', 'links' => $static([
            '/preise.php' => 'Preise & Ablauf', '/methodik.php' => 'Unsere Methodik',
            '/faq.php' => 'Häufige Fragen', '/bewertungen.php' => 'Bewertungen',
            '/erfolg.php' => 'Lernerfolge',
        ])],
        ['title' => 'Wissen & Lernwerkzeuge', 'intro' => 'Kostenlose Lernhilfen und Beiträge.', 'links' => array_merge(
            $static(['/lernwerkzeuge.php' => 'Alle Lernwerkzeuge']), $entries($tools), $static(['/blog.php' => 'Lernblog'])
        )],
        ['title' => 'Über uns & Karriere', 'intro' => 'Das Team, die Werte und die Mitarbeit.', 'links' => $static([
            '/ueber-mich.php' => 'Über mich & Team', '/warum-easyit.php' => 'Warum easyIT?',
            '/karriere.php' => 'Karriere', '/jobs.php' => 'Stellenangebote',
        ])],
        ['title' => 'Kontakt & Rechtliches', 'intro' => 'Anfragen und rechtliche Informationen.', 'links' => $static([
            '/kontakt.php' => 'Nachhilfe anfragen', '/impressum.php' => 'Impressum',
            '/datenschutz.php' => 'Datenschutz', '/bildnachweis.php' => 'Bildnachweise',
            '/sitemap.php' => 'Sitemap',
        ])],
    ];
}

/** Die acht direkt verständlichen Hauptpunkte, mit maximal einer Untermenü-Ebene. */
function website_recommended_navigation(): array
{
    $groups = website_sitemap_groups();
    $groupByTitle = [];
    foreach ($groups as $group) $groupByTitle[$group['title']] = $group['links'];

    $childrenWithoutOverview = static function (string $group) use ($groupByTitle): array {
        return array_values(array_filter($groupByTitle[$group] ?? [], static fn(array $link): bool =>
            !in_array($link['url'], ['/faecher.php','/schulformen.php','/nachhilfe-in-leipzig.php','/lernwerkzeuge.php'], true)
        ));
    };
    $get = static fn(string $group): array => $groupByTitle[$group] ?? [];
    return [
        ['title' => 'Startseite', 'url' => '/'],
        ['title' => 'Fächer', 'url' => '/faecher.php', 'children' => $childrenWithoutOverview('Fächer')],
        ['title' => 'Lernstufen', 'url' => '/schulformen.php', 'children' => $childrenWithoutOverview('Lernstufen')],
        ['title' => 'Leipzig', 'url' => '/nachhilfe-in-leipzig.php', 'children' => $childrenWithoutOverview('Leipzig & Stadtteile')],
        ['title' => 'Preise & Ablauf', 'url' => '/preise.php', 'children' => array_values(array_filter($get('Ablauf & Preise'), static fn(array $i): bool => $i['url'] !== '/preise.php'))],
        ['title' => 'Lernhilfen', 'url' => '/lernwerkzeuge.php', 'children' => $childrenWithoutOverview('Wissen & Lernwerkzeuge')],
        ['title' => 'Über uns', 'url' => '/ueber-mich.php', 'children' => array_values(array_filter($get('Über uns & Karriere'), static fn(array $i): bool => $i['url'] !== '/ueber-mich.php'))],
        ['title' => 'Kontakt', 'url' => '/kontakt.php'],
    ];
}

function website_sitemap_public_urls(): array
{
    $urls = [];
    foreach (website_sitemap_groups() as $group) {
        foreach ($group['links'] as $link) $urls[$link['url']] = $link['title'];
    }
    return $urls;
}
