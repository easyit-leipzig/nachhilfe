<?php
/**
 * Globale, installationsverzeichnis-unabhängige Design-Assets für easyIT Nachhilfe.
 * Auf allen HTML-Seiten höchstens einmal ausgeben.
 */
if (!defined('EASYIT_NACHHILFE_DARK_HEAD_LOADED')) {
    define('EASYIT_NACHHILFE_DARK_HEAD_LOADED', true);

    $__themeRoot = realpath(dirname(__DIR__));
    $__themeDocroot = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $__themeDir = '';
    if ($__themeRoot && $__themeDocroot) {
        $__themeRootPath = str_replace('\\', '/', $__themeRoot);
        $__themeDocPath = rtrim(str_replace('\\', '/', $__themeDocroot), '/');
        // Windows-Dateisysteme sind üblicherweise nicht case-sensitiv.
        $__themeFold = DIRECTORY_SEPARATOR === '\\' ? 'strtolower' : 'strval';
        if ($__themeFold($__themeRootPath) === $__themeFold($__themeDocPath)) {
            $__themeDir = '';
        } elseif (str_starts_with($__themeFold($__themeRootPath), $__themeFold($__themeDocPath . '/'))) {
            $__themeDir = substr($__themeRootPath, strlen($__themeDocPath));
        } else {
            // Fallback für Aliase und abweichende Apache-Dokumentenwurzeln.
            $__themeDir = '/' . basename($__themeRootPath);
        }
    } else {
        $__themeDir = '/' . basename((string) $__themeRoot);
    }
    $__themeSegments = array_filter(explode('/', $__themeDir), 'strlen');
    $__themeBase = '/' . implode('/', array_map('rawurlencode', $__themeSegments));
    $__themeBase = rtrim($__themeBase, '/');
    $__themeCss = $__themeBase . '/assets/css/easyit-dark-unify.css?v=1.0.0';
    $__themeJs  = $__themeBase . '/assets/js/easyit-dark-unify.js?v=1.0.0';
    echo "\n<link rel=\"stylesheet\" href=\"" . htmlspecialchars($__themeCss, ENT_QUOTES, 'UTF-8') . "\" data-easyit-design=\"global-dark\">\n";
    echo '<script defer src="' . htmlspecialchars($__themeJs, ENT_QUOTES, 'UTF-8') . '" data-easyit-design="global-dark"></script>' . "\n";
    unset($__themeRoot, $__themeDocroot, $__themeDir, $__themeRootPath, $__themeDocPath, $__themeFold, $__themeSegments, $__themeBase, $__themeCss, $__themeJs);
}
