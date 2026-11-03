<?php
/**
 * Google Search–friendly favicon tags.
 * Google requires a square icon that is a multiple of 48px (e.g. 48, 96, 192).
 * @see https://developers.google.com/search/docs/appearance/favicon-in-search
 */
$faviconVer = '3';
$faviconBase = SITE_URL . 'assets/images/favicons/';
?>
<link rel="icon" type="image/png" sizes="48x48" href="<?= $faviconBase; ?>favicon-48x48.png?v=<?= $faviconVer; ?>">
<link rel="icon" type="image/png" sizes="96x96" href="<?= $faviconBase; ?>favicon-96x96.png?v=<?= $faviconVer; ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?= $faviconBase; ?>android-chrome-192x192.png?v=<?= $faviconVer; ?>">
<link rel="icon" type="image/x-icon" sizes="any" href="<?= $faviconBase; ?>favicon.ico?v=<?= $faviconVer; ?>">
<link rel="shortcut icon" href="<?= $faviconBase; ?>favicon.ico?v=<?= $faviconVer; ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= $faviconBase; ?>apple-touch-icon.png?v=<?= $faviconVer; ?>">
<link rel="manifest" href="<?= $faviconBase; ?>site.webmanifest" />
