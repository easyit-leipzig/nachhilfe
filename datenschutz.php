<?php
/**
 * Datenschutzerklärung – easyIT Leipzig / Nachhilfe.
 * Version: 22.11.2026. Keine Muster-/Test- oder Fremdorganisationstexte.
 * Aufruf: /nachhilfe/datenschutz.php
 * Die ergänzenden Angaben zur tatsächlichen Technik sind vor Veröffentlichung
 * mit der aktiven Installation abzugleichen (vgl. README-DATENSCHUTZ.md).
 */
declare(strict_types=1);

$configFile = __DIR__ . '/config/privacy-nachhilfe.php';
$privacy = is_file($configFile) ? require $configFile : [];
if (!is_array($privacy)) { $privacy = []; }

function privText(array $cfg, string $key): string {
    return htmlspecialchars(trim((string)($cfg[$key] ?? '')), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function privHas(array $cfg, string $key): bool {
    return isset($cfg[$key]) && is_scalar($cfg[$key]) && trim((string)$cfg[$key]) !== '';
}
/** Integer-Tagesfristen: null/leer/ungültig werden nicht veröffentlicht. */
function privDays(array $cfg, string $key): ?int {
    $value = $cfg[$key] ?? null;
    if (is_int($value)) { return $value > 0 ? $value : null; }
    if (is_string($value) && preg_match('/^[1-9][0-9]*$/D', trim($value))) {
        $day = filter_var(trim($value), FILTER_VALIDATE_INT);
        return ($day !== false && $day > 0) ? $day : null;
    }
    return null;
}
/** Für öffentlich verlinkte Datenschutzhinweise sind nur HTTP(S)-URLs erlaubt. */
function privPublicUrl(array $cfg, string $key): string {
    $url = trim((string)($cfg[$key] ?? ''));
    if (!filter_var($url, FILTER_VALIDATE_URL)) { return ''; }
    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    return in_array($scheme, ['https', 'http'], true)
        ? htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '';
}
/** Leere Kategorien und Duplikate aus dem Konfigurationsarray entfernen. */
function privItems(array $cfg, string $key): array {
    $raw = $cfg[$key] ?? [];
    if (!is_array($raw)) { return []; }
    $items = [];
    foreach ($raw as $item) {
        if (!is_string($item)) { continue; }
        $item = trim($item);
        if ($item !== '' && !in_array($item, $items, true)) { $items[] = $item; }
    }
    return $items;
}

$controller = privText($privacy, 'controller_name');
$company = privText($privacy, 'company');
$email = trim((string)($privacy['email'] ?? ''));
$emailSafe = filter_var($email, FILTER_VALIDATE_EMAIL) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '';
$addressParts = array_filter([
    trim((string)($privacy['street'] ?? '')),
    trim((string)($privacy['postal_code'] ?? '') . ' ' . (string)($privacy['city'] ?? '')),
]);
$street = privText($privacy, 'street');
$postalCity = htmlspecialchars(trim((string)($privacy['postal_code'] ?? '') . ' ' . (string)($privacy['city'] ?? '')), ENT_QUOTES, 'UTF-8');
$phone = privText($privacy, 'phone');
$hasPostalAddress = count($addressParts) === 2;
$logDays = privDays($privacy, 'server_log_days');
$ipAnonymizationHours = privDays($privacy, 'server_log_ip_anonymization_hours');
$contactDays = privDays($privacy, 'contact_storage_days');
$hosterRetentionDays = privDays($privacy, 'hoster_retention_days');
$hosterBackupDays = privDays($privacy, 'hoster_backup_days');
$hosterStoredData = privItems($privacy, 'hoster_stored_data');
$hosterLogData = privItems($privacy, 'hoster_server_log_data');
$hosterBackupData = privItems($privacy, 'hoster_backup_data');
$hosterPrivacyUrl = privPublicUrl($privacy, 'hoster_privacy_url');
$hosterPostalCity = trim((string)($privacy['hoster_postal_code'] ?? '') . ' ' . (string)($privacy['hoster_city'] ?? ''));
$hosterHasContact = privHas($privacy, 'hoster_name');

$revision = privText($privacy, 'revision_date');
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="index,follow">
  <meta name="description" content="Datenschutzerklärung für die Nachhilfe von easyIT Leipzig: Informationen zur Verarbeitung personenbezogener Daten und zu Ihren Rechten.">
  <title>Datenschutz | Nachhilfe mit System</title>
  <style>
    :root{color-scheme:dark;--bg:#071521;--pane:#102538;--pane2:#0d2031;--line:#29485b;--txt:#eff6fa;--muted:#c4d7e2;--accent:#61d4f4;--focus:#f4d065}
    *{box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{margin:0;background:radial-gradient(circle at 65% -20%,#163b54 0,transparent 50%),var(--bg);color:var(--txt);font:16px/1.7 system-ui,-apple-system,"Segoe UI",Arial,sans-serif}
    a{color:var(--accent);text-underline-offset:.18em}
    a:focus-visible{outline:3px solid var(--focus);outline-offset:3px;border-radius:3px}
    .skip{position:absolute;left:-200vw;top:0;background:var(--accent);color:#082031;padding:8px 14px;z-index:99}
    .skip:focus{left:10px}
    .shell{width:min(1120px,calc(100% - 36px));margin:0 auto}
    .site-head{border-bottom:1px solid var(--line);background:#0b1b2b;padding:16px 0}
    .head-inner{display:flex;align-items:center;justify-content:space-between;gap:22px;flex-wrap:wrap}
    .brand{text-decoration:none;color:var(--txt);font-weight:800;letter-spacing:.02em;font-size:1.25rem}
    .brand span{color:var(--accent)}
    .head-nav{display:flex;flex-wrap:wrap;gap:18px}
    .head-nav a{color:#e2f0f6;text-decoration:none}
    .head-nav a:hover,.head-nav a[aria-current="page"]{color:var(--accent);text-decoration:underline}
    main{padding:42px 0 78px}
    .intro{max-width:790px;margin-bottom:30px}
    .eyebrow{text-transform:uppercase;letter-spacing:.15em;font-size:.8rem;font-weight:700;color:var(--accent)}
    h1{font-size:clamp(2.1rem,5vw,3.4rem);line-height:1.15;letter-spacing:-.035em;margin:.5rem 0 1.2rem}
    h2{font-size:1.35rem;line-height:1.3;margin:0 0 12px;color:#eaf7fd}
    h3{font-size:1.05rem;margin:24px 0 8px;color:#e9f7fc}
    p{margin:.65rem 0 1rem} ul{padding-left:1.35rem;margin:.5rem 0 1rem} li{margin:.2rem 0}
    .lead{font-size:1.06rem;color:var(--muted)}
    .content-layout{display:grid;grid-template-columns:250px minmax(0,1fr);gap:26px;align-items:start}
    .toc{position:sticky;top:18px;padding:20px;background:var(--pane2);border:1px solid var(--line);border-radius:16px}
    .toc-title{font-size:.93rem;font-weight:700;margin:0 0 10px}
    .toc a{display:block;padding:5px 0;color:var(--muted);text-decoration:none;font-size:.92rem}
    .toc a:hover{color:var(--accent);text-decoration:underline}
    .sections{display:grid;gap:18px;min-width:0}
    section.privacy-section{background:linear-gradient(145deg,var(--pane),var(--pane2));border:1px solid var(--line);border-radius:16px;padding:clamp(20px,3vw,30px);scroll-margin-top:22px}
    section.privacy-section p:last-child,section.privacy-section ul:last-child{margin-bottom:0}
    address{font-style:normal;border-left:3px solid var(--accent);padding-left:18px;line-height:1.8;margin:12px 0}
    .date{color:var(--muted);font-size:.9rem}
    .site-foot{border-top:1px solid var(--line);padding:25px 0;background:#0a1826;color:var(--muted);font-size:.93rem}
    .foot-inner{display:flex;justify-content:space-between;gap:18px;flex-wrap:wrap}
    .foot-links{display:flex;flex-wrap:wrap;gap:18px}
    @media(max-width:760px){.content-layout{grid-template-columns:1fr}.toc{position:static}.toc a{display:inline-block;margin-right:12px}}
    @media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}}
  </style>
</head>
<body>
<a class="skip" href="#inhalt">Zum Inhalt springen</a>
<header class="site-head"><div class="shell head-inner">
 <a class="brand" href="./"><span>easyIT</span> · Nachhilfe mit System</a>
 <nav class="head-nav" aria-label="Hauptnavigation">
  <a href="./">Startseite</a><a href="faecher.php">Fächer</a><a href="kontakt.php">Kontakt</a><a href="datenschutz.php" aria-current="page">Datenschutz</a>
 </nav>
</div></header>
<main id="inhalt" class="shell">
  <div class="intro"><div class="eyebrow">Rechtliche Informationen</div>
    <h1>Datenschutzerklärung</h1>
    <p class="lead">Hier erfahren Sie, welche personenbezogenen Daten beim Besuch dieser Webseite und bei einer Kontaktaufnahme oder Buchungsanfrage verarbeitet werden, zu welchem Zweck dies geschieht und welche Rechte Ihnen zustehen.</p>
    <p class="date">Stand: <?= $revision !== '' ? $revision : 'November 2026' ?></p>
  </div>
  <div class="content-layout">
    <nav class="toc" aria-label="Inhaltsverzeichnis"><p class="toc-title">Auf dieser Seite</p>
      <a href="#verantwortlich">1. Verantwortliche Stelle</a>
      <a href="#grundsaetze">2. Grundlagen und Zwecke</a>
      <a href="#server">3. Aufruf und Serverdaten</a>
      <a href="#kontakt">4. Kontaktaufnahme</a>
      <a href="#unterricht">5. Nachhilfe und Lernstand</a>
      <a href="#cookies">6. Sitzungen und Cookies</a>
      <a href="#empfaenger">7. Empfänger und Drittstaaten</a>
      <a href="#dauer">8. Speicherdauer</a>
      <a href="#rechte">9. Ihre Datenschutzrechte</a>
      <a href="#sicherheit">10. Sicherheit</a>
      <a href="#aktualisierung">11. Aktualisierung</a>
    </nav>
    <div class="sections">
      <section class="privacy-section" id="verantwortlich"><h2>1. Verantwortliche Stelle</h2>
        <p>Verantwortlich für die Verarbeitung personenbezogener Daten im Rahmen dieses Nachhilfeangebots ist:</p>
        <address>
          <?php if ($controller !== ''): ?><strong><?= $controller ?></strong><br><?php endif; ?>
          <?php if ($company !== ''): ?><?= $company ?><br><?php endif; ?>
          <?php if ($hasPostalAddress): ?><?= $street ?><br><?= $postalCity ?><br><?php else: ?>Die Postanschrift ist dem <a href="impressum.php">Impressum</a> zu entnehmen.<br><?php endif; ?>
          <?php if ($phone !== ''): ?>Telefon: <?= $phone ?><br><?php endif; ?>
          <?php if ($emailSafe !== ''): ?>E-Mail: <a href="mailto:<?= $emailSafe ?>"><?= $emailSafe ?></a><?php else: ?><a href="impressum.php">Kontakt laut Impressum</a><?php endif; ?>
        </address>
        <p>Die jeweils gültigen Angaben zum Diensteanbieter finden Sie ebenfalls im <a href="impressum.php">Impressum</a>.</p>
      </section>
      <section class="privacy-section" id="grundsaetze"><h2>2. Grundsätze und Zwecke der Verarbeitung</h2>
        <p>Wir verarbeiten personenbezogene Daten nur, soweit dies für den technischen Betrieb der Webseite, die Bearbeitung von Anfragen, die Durchführung und Organisation des Nachhilfeunterrichts oder die Erfüllung gesetzlicher Pflichten erforderlich ist.</p>
        <p>Je nach Vorgang stützt sich die Verarbeitung auf Art. 6 Abs. 1 lit. b DSGVO (Vertrag und vorvertragliche Maßnahmen), Art. 6 Abs. 1 lit. c DSGVO (gesetzliche Pflichten), Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an einem sicheren und funktionsfähigen Webangebot) oder Art. 6 Abs. 1 lit. a DSGVO (eine ausdrücklich erteilte Einwilligung).</p>
        <p>Soweit wir uns auf berechtigte Interessen stützen, gehören dazu die Betriebssicherheit, die Abwehr technischer Angriffe und die zuverlässige Beantwortung von Anfragen. Einwilligungen können mit Wirkung für die Zukunft widerrufen werden.</p>
      </section>
      <section class="privacy-section" id="server"><h2>3. Hosting und Serverdaten</h2>
        <h3>Hostingbetreiber und Serverstandort</h3>
        <?php if ($hosterHasContact): ?>
          <p>Für den technischen Betrieb dieser Webseite wird folgender Hosting-Dienstleister eingesetzt:</p>
          <address>
            <strong><?= privText($privacy, 'hoster_name') ?></strong><br>
            <?php if (privHas($privacy, 'hoster_street')): ?><?= privText($privacy, 'hoster_street') ?><br><?php endif; ?>
            <?php if ($hosterPostalCity !== ''): ?><?= htmlspecialchars($hosterPostalCity, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br><?php endif; ?>
            <?php if (privHas($privacy, 'hoster_country')): ?><?= privText($privacy, 'hoster_country') ?><br><?php endif; ?>
            <?php if (privHas($privacy, 'hoster_email') && filter_var($privacy['hoster_email'], FILTER_VALIDATE_EMAIL)): ?>
              E-Mail: <a href="mailto:<?= privText($privacy, 'hoster_email') ?>"><?= privText($privacy, 'hoster_email') ?></a><br>
            <?php endif; ?>
            <?php if ($hosterPrivacyUrl !== ''): ?><a href="<?= $hosterPrivacyUrl ?>" target="_blank" rel="noopener noreferrer">Datenschutzhinweise des Hosting-Dienstleisters</a><?php endif; ?>
          </address>
        <?php else: ?>
          <p>Die Webseite wird auf externer Hosting-Infrastruktur betrieben. Die hierdurch erforderliche Datenverarbeitung richtet sich nach der eingesetzten Hosting-Umgebung.</p>
        <?php endif; ?>
        <?php if (privHas($privacy, 'hoster_server_location')): ?>
          <p>Server-/Rechenzentrumsstandort: <?= privText($privacy, 'hoster_server_location') ?>.</p>
        <?php endif; ?>

        <h3>Beim Hosting-Dienstleister verarbeitete Daten</h3>
        <?php if ($hosterStoredData !== []): ?>
          <p>Im Zusammenhang mit dem Hosting werden nach den hinterlegten Betriebsangaben folgende Datenkategorien verarbeitet bzw. gespeichert:</p>
          <ul><?php foreach ($hosterStoredData as $item): ?><li><?= htmlspecialchars($item, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li><?php endforeach; ?></ul>
        <?php else: ?>
          <p>Für die Bereitstellung der Webseite können technische Verbindungsdaten anfallen. Welche Daten darüber hinaus beim Hosting-Dienstleister gespeichert werden, hängt von den tatsächlich verwendeten Hosting-Funktionen ab.</p>
        <?php endif; ?>

        <h3>Server-Protokolldaten</h3>
        <?php if ($hosterLogData !== []): ?>
          <p>In Server-Protokolldateien werden folgende Angaben erfasst:</p>
          <ul><?php foreach ($hosterLogData as $item): ?><li><?= htmlspecialchars($item, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li><?php endforeach; ?></ul>
        <?php else: ?>
          <p>Beim Aufruf dieser Webseite werden für die technische Verbindung Daten verarbeitet, beispielsweise die IP-Adresse und der Zeitpunkt des Zugriffs. Abhängig von der Serverkonfiguration können Protokolle zur Fehleranalyse und Angriffserkennung entstehen.</p>
        <?php endif; ?>
        <p>Die Verarbeitung technisch notwendiger Verbindungsdaten dient insbesondere der Bereitstellung, Stabilität und Sicherheit der Webseite. Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO.</p>

        <h3>Speicherdauer beim Hoster</h3>
        <?php if ($hosterRetentionDays !== null): ?>
          <p>Die in der Hosting-Konfiguration für die reguläre Speicherung erfassten Daten werden grundsätzlich nach <?= $hosterRetentionDays ?> Tagen gelöscht, soweit keine gesetzlichen Aufbewahrungspflichten oder konkreten Sicherheitsvorfälle entgegenstehen.</p>
        <?php elseif (privHas($privacy, 'hoster_retention_policy')): ?>
          <p>Für die reguläre Speicherung beim Hosting-Dienstleister gilt: <?= privText($privacy, 'hoster_retention_policy') ?></p>
        <?php else: ?>
          <p>Daten werden nur so lange aufbewahrt, wie sie für den jeweiligen Verarbeitungszweck erforderlich sind; gesetzliche Aufbewahrungsfristen bleiben unberührt.</p>
        <?php endif; ?>
        <?php if ($ipAnonymizationHours !== null): ?>
          <p>Alfahosting anonymisiert die in den Server-Logdateien enthaltenen IP-Adressen nach <?= $ipAnonymizationHours ?> Stunden durch Nullung des letzten Oktetts.</p>
        <?php endif; ?>
        <?php if ($logDays !== null): ?>
          <p>Die Server-Logdateien werden laut Alfahosting spätestens nach <?= $logDays ?> Tagen gelöscht.</p>
        <?php endif; ?>

        <?php if ($hosterBackupData !== [] || $hosterBackupDays !== null || privHas($privacy, 'hoster_backup_policy')): ?>
          <h3>Datensicherungen beim Hosting-Dienstleister</h3>
          <?php if ($hosterBackupData !== []): ?>
            <p>Folgende Datenkategorien können in Sicherungskopien enthalten sein:</p>
            <ul><?php foreach ($hosterBackupData as $item): ?><li><?= htmlspecialchars($item, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li><?php endforeach; ?></ul>
          <?php endif; ?>
          <?php if ($hosterBackupDays !== null): ?>
            <p>Sicherungskopien werden grundsätzlich nach <?= $hosterBackupDays ?> Tagen gelöscht bzw. überschrieben, soweit keine längere Aufbewahrung erforderlich ist.</p>
          <?php elseif (privHas($privacy, 'hoster_backup_policy')): ?>
            <p>Für Sicherungskopien gilt: <?= privText($privacy, 'hoster_backup_policy') ?></p>
          <?php endif; ?>
        <?php endif; ?>
        <?php if (($privacy['hoster_avv_concluded'] ?? null) === true): ?>
          <h3>Auftragsverarbeitung</h3>
          <p>Mit dem Hosting-Dienstleister besteht, soweit er für uns personenbezogene Daten im Auftrag verarbeitet, ein Vertrag zur Auftragsverarbeitung gemäß Art. 28 DSGVO.</p>
        <?php endif; ?>
      </section>
      <section class="privacy-section" id="kontakt"><h2>4. Kontaktaufnahme und Termin- bzw. Buchungsanfragen</h2>
        <p>Wenn Sie uns per Kontaktformular, E-Mail oder Telefon kontaktieren, verarbeiten wir die von Ihnen übermittelten Angaben, insbesondere Namen, Kontaktmöglichkeiten, Inhalt der Anfrage und gegebenenfalls Angaben zu gewünschten Unterrichtszeiten oder Fächern.</p>
        <p>Die Verarbeitung erfolgt zur Beantwortung und Vorbereitung von Anfragen (Art. 6 Abs. 1 lit. b DSGVO) sowie, soweit erforderlich, im Interesse einer sachgerechten Kommunikation (Art. 6 Abs. 1 lit. f DSGVO). Pflichtangaben in Formularen müssen sich auf die für die jeweilige Anfrage notwendigen Daten beschränken.</p>
        <p>Die Angaben werden nicht zu Werbezwecken verwendet, sofern hierfür keine gesonderte wirksame Rechtsgrundlage vorliegt. E-Mails können auch auf dem eingesetzten Mailserver und in Postfächern verarbeitet werden.</p>
        <?php if (is_int($contactDays) && $contactDays > 0): ?>
          <p>Unverbindliche Kontaktanfragen werden regelmäßig nach <?= (int)$contactDays ?> Tagen gelöscht, sofern kein Vertragsverhältnis entsteht und keine gesetzlichen Aufbewahrungsgründe bestehen.</p>
        <?php else: ?>
          <p>Die Daten verbleiben nur so lange, wie sie für die Bearbeitung und gegebenenfalls die weitere Geschäftsbeziehung erforderlich sind. Gesetzliche Aufbewahrungsfristen bleiben unberührt.</p>
        <?php endif; ?>
      </section>
      <section class="privacy-section" id="unterricht"><h2>5. Schülerdaten, Lernstand und Unterrichtsorganisation</h2>
        <p>Im Zusammenhang mit einer Unterrichtsanfrage oder einem bestehenden Nachhilfeverhältnis können Angaben zur Schülerin oder zum Schüler verarbeitet werden, beispielsweise Name, Klassenstufe, Schule, Unterrichtsfach, Terminwünsche, mitgeteilte Lernschwierigkeiten, Aufgabenbearbeitungen und Lernfortschritt.</p>
        <p>Diese Informationen werden verwendet, um Lernbedarf und Unterricht vorzubereiten, passende Übungen zu erstellen, Termine zu organisieren und die vereinbarte Leistung zu erbringen. Rechtsgrundlage ist in der Regel Art. 6 Abs. 1 lit. b DSGVO. Soweit darüber hinaus eine Einwilligung erforderlich ist, wird sie gesondert eingeholt.</p>
        <p>Die Daten von Minderjährigen werden mit besonderer Sorgfalt behandelt. Eine Veröffentlichung von Lernständen, Leistungsbewertungen, Fotos oder anderen personenbezogenen Informationen erfolgt nicht allein aufgrund eines Nachhilfevertrags.</p>
      </section>
      <section class="privacy-section" id="cookies"><h2>6. Technisch notwendige Sitzungen und Cookies</h2>
        <p>Funktionen mit Benutzeranmeldung können technisch erforderliche Sitzungskennungen verwenden, um Anmeldungen aufrechtzuerhalten und Zugriffe zu schützen. Solche Kennungen dienen nicht automatisch der Analyse von Nutzerverhalten oder der Werbung.</p>
        <p>Ein Zugriff auf Informationen im Endgerät ist nach § 25 Abs. 2 TDDDG ohne Einwilligung zulässig, soweit er für einen ausdrücklich gewünschten Dienst unbedingt erforderlich ist. Für nicht erforderliche Cookies oder ähnliche Technologien ist grundsätzlich vor ihrem Einsatz eine Einwilligung einzuholen (§ 25 Abs. 1 TDDDG).</p>
        <p>Welche technisch erforderlichen Kennungen konkret eingesetzt werden, richtet sich nach den aufgerufenen Funktionen der Installation.</p>
      </section>
      <section class="privacy-section" id="empfaenger"><h2>7. Empfänger und mögliche Drittlandübermittlungen</h2>
        <p>Personenbezogene Daten können, soweit erforderlich, an Dienstleister für Webhosting und E-Mail-Versand sowie an technische Wartungsdienstleister weitergegeben werden. Externe Dienstleister erhalten nur die Daten, die für die jeweilige Aufgabe erforderlich sind, und werden gegebenenfalls vertraglich zur Auftragsverarbeitung verpflichtet.</p>
        <p>Soweit personenbezogene Daten an Empfänger außerhalb des Europäischen Wirtschaftsraums übermittelt werden, geschieht dies nur unter Beachtung der hierfür geltenden gesetzlichen Voraussetzungen. Die Einbindung externer Inhalte oder Analysedienste bedarf, soweit gesetzlich vorgeschrieben, einer vorherigen Einwilligung.</p>
      </section>
      <section class="privacy-section" id="dauer"><h2>8. Speicherdauer und Löschung</h2>
        <p>Wir speichern Daten grundsätzlich nur so lange, wie es für den jeweiligen Zweck erforderlich ist. Daten aus Unterrichts- und Vertragsverhältnissen werden nach Ende der Zusammenarbeit gelöscht, soweit sie nicht noch für berechtigte Ansprüche oder gesetzliche Aufbewahrungspflichten benötigt werden. Buchungs- und Abrechnungsunterlagen können steuer- oder handelsrechtlichen Aufbewahrungsfristen unterliegen. Die gesonderten Fristen für Hosting, Server-Logs und Backups sind, soweit festgestellt, in <a href="#server">Abschnitt 3</a> aufgeführt.</p>
        <p>Eine Einwilligung kann jederzeit für die Zukunft widerrufen werden. Danach prüfen wir die weitere Verarbeitung und löschen die betroffenen Daten, soweit keine andere Rechtsgrundlage entgegensteht.</p>
      </section>
      <section class="privacy-section" id="rechte"><h2>9. Ihre Rechte</h2>
        <p>Sie haben unter den gesetzlichen Voraussetzungen das Recht auf Auskunft (Art. 15 DSGVO), Berichtigung (Art. 16), Löschung (Art. 17), Einschränkung der Verarbeitung (Art. 18), Datenübertragbarkeit (Art. 20) und Widerspruch gegen bestimmte Verarbeitungen (Art. 21). Erteilte Einwilligungen können Sie mit Wirkung für die Zukunft widerrufen (Art. 7 Abs. 3 DSGVO).</p>
        <p>Wenden Sie sich hierfür an die oben genannte verantwortliche Stelle. Außerdem können Sie sich bei einer Datenschutzaufsichtsbehörde beschweren (Art. 77 DSGVO). Für eine verantwortliche Stelle mit Sitz in Sachsen ist insbesondere die <a href="https://www.datenschutz.sachsen.de/" target="_blank" rel="noopener noreferrer">Sächsische Datenschutz- und Transparenzbeauftragte</a> zuständig.</p>
        <h3>Widerspruch nach Art. 21 DSGVO</h3>
        <p>Soweit wir personenbezogene Daten aufgrund von Art. 6 Abs. 1 lit. f DSGVO verarbeiten, können Sie aus Gründen, die sich aus Ihrer besonderen Situation ergeben, Widerspruch einlegen.</p>
      </section>
      <section class="privacy-section" id="sicherheit"><h2>10. Schutz Ihrer Daten</h2>
        <p>Wir treffen angemessene technische und organisatorische Maßnahmen, um personenbezogene Daten vor Verlust, Missbrauch und unberechtigtem Zugriff zu schützen. Für die Übertragung über das Internet wird nach Möglichkeit eine verschlüsselte HTTPS-Verbindung verwendet. Bei unverschlüsselter E-Mail-Kommunikation kann ein vollständiger Schutz während der Übertragung nicht garantiert werden.</p>
      </section>
      <section class="privacy-section" id="aktualisierung"><h2>11. Aktualisierung dieser Erklärung</h2>
        <p>Änderungen an der Webseite, ihren Funktionen oder den eingesetzten Dienstleistern können eine Anpassung dieser Datenschutzerklärung erforderlich machen. Maßgeblich ist die hier veröffentlichte Fassung.</p>
      </section>
    </div>
  </div>
</main>
<footer class="site-foot"><div class="shell foot-inner">
  <span>© <?= (int)date('Y') ?> easyIT Leipzig · Nachhilfe</span>
  <nav class="foot-links" aria-label="Rechtliches"><a href="./">Startseite</a><a href="impressum.php">Impressum</a><a href="datenschutz.php" aria-current="page">Datenschutz</a></nav>
</div></footer>
</body>
</html>
