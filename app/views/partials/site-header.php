<?php
declare(strict_types=1);

$navUrl = static function (string $path): string {
    return function_exists('url') ? url($path) : $path;
};
$headerCta = isset($headerCta) ? (string) $headerCta : 'BOOK A CONSULTATION';
$headerCtaHref = isset($headerCtaHref) ? (string) $headerCtaHref : '/contact#contact';
?>
    <div class="topbar">
      <div class="container">
        <span>Independent Legal Guidance. Professional Service.</span>
        <span>Appointments By Prior Arrangement</span>
      </div>
    </div>
    <header class="navbar">
      <div class="container navinner">
        <a class="brand" href="<?= htmlspecialchars($navUrl('/'), ENT_QUOTES, 'UTF-8') ?>" aria-label="Saliheen &amp; Associates home">
          <img src="<?= htmlspecialchars($navUrl('/images/saliheen-brand.svg'), ENT_QUOTES, 'UTF-8') ?>" alt="Saliheen &amp; Associates logo">
        </a>
        <button class="menu-toggle" id="menuToggle" aria-label="Open navigation" aria-expanded="false" aria-controls="navLinks">&#9776;</button>
        <nav class="navlinks" id="navLinks" aria-label="Main navigation">
          <a href="<?= htmlspecialchars($navUrl('/'), ENT_QUOTES, 'UTF-8') ?>" data-page="/">Home</a>
          <a href="<?= htmlspecialchars($navUrl('/about'), ENT_QUOTES, 'UTF-8') ?>" data-page="/about">About</a>
          <a href="<?= htmlspecialchars($navUrl('/services'), ENT_QUOTES, 'UTF-8') ?>" data-page="/services">Services</a>
          <a href="<?= htmlspecialchars($navUrl('/team'), ENT_QUOTES, 'UTF-8') ?>" data-page="/team">Our Team</a>
          <a href="<?= htmlspecialchars($navUrl('/contact'), ENT_QUOTES, 'UTF-8') ?>" data-page="/contact">Contact</a>
          <a class="portal-link" href="<?= htmlspecialchars($navUrl('/login/'), ENT_QUOTES, 'UTF-8') ?>">Client Portal</a>
        </nav>
        <a class="navcta" href="<?= htmlspecialchars($navUrl($headerCtaHref), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($headerCta, ENT_QUOTES, 'UTF-8') ?> &#8599;</a>
      </div>
    </header>
