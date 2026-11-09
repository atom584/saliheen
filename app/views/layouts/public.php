<?php
declare(strict_types=1);

$title = isset($title) ? (string) $title : 'Saliheen & Associates';
$description = isset($description) ? (string) $description : '';
$bodyClass = isset($bodyClass) ? (string) $bodyClass : '';
$canonical = $canonical ?? null;
$themeColor = isset($themeColor) ? (string) $themeColor : '#171717';
$styles = isset($styles) && is_array($styles) ? $styles : ['/css/styles.css'];
$scripts = isset($scripts) && is_array($scripts) ? $scripts : ['/js/site.js'];
$headerCta = isset($headerCta) ? (string) $headerCta : 'BOOK A CONSULTATION';
$headerCtaHref = isset($headerCtaHref) ? (string) $headerCtaHref : '/contact#contact';
$footerVariant = isset($footerVariant) ? (string) $footerVariant : 'compact';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= e($description) ?>" />
    <meta name="theme-color" content="<?= e($themeColor) ?>" />
    <title><?= e($title) ?></title>
    <?php if (is_string($canonical)): ?>
    <link rel="canonical" href="<?= e(absolute_url($canonical)) ?>" />
    <?php endif; ?>
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Saliheen &amp; Associates" />
    <meta property="og:title" content="<?= e($title) ?>" />
    <meta property="og:description" content="<?= e($description) ?>" />
    <?php if (is_string($canonical)): ?>
    <meta property="og:url" content="<?= e(absolute_url($canonical)) ?>" />
    <?php endif; ?>
    <meta property="og:image" content="<?= e(absolute_url('/images/saliheen-share.png')) ?>" />
    <meta property="og:image:alt" content="Saliheen &amp; Associates logo" />
    <meta property="og:image:type" content="image/png" />
    <meta name="twitter:card" content="summary" />
    <link rel="icon" type="image/svg+xml" href="<?= e(url('/images/saliheen-favicon.svg')) ?>" />
    <link rel="icon" type="image/png" sizes="64x64" href="<?= e(url('/images/saliheen-favicon.png')) ?>" />
    <?php foreach ($styles as $href): ?>
    <link rel="stylesheet" href="<?= e(url((string) $href)) ?>" />
    <?php endforeach; ?>
    <?php foreach ($scripts as $src): ?>
    <script src="<?= e(url((string) $src)) ?>" defer></script>
    <?php endforeach; ?>
  </head>
  <body class="<?= e($bodyClass) ?>">
    <?php require APP_PATH . '/views/partials/site-header.php'; ?>

    <?= $content ?>
    <?php require APP_PATH . '/views/partials/site-footer.php'; ?>

    <button class="back-top" id="backTop" aria-label="Scroll to top">↑</button>
  </body>
</html>
