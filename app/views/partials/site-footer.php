<?php
declare(strict_types=1);

$variant = in_array($footerVariant ?? '', ['home', 'explore'], true) ? (string) $footerVariant : 'compact';
$isCompact = $variant === 'compact';

if ($isCompact) {
    $links = [
        ['About the Firm', '/about'],
        ['Legal Services', '/services'],
        ['Our Team', '/team'],
        ['Contact', '/contact#contact'],
    ];
    $intro = 'Professional legal consultancy with a commitment to clarity, discretion, and client-focused service.';
    $emails = ['nazmus.nazlee@gmail.com', 'info@nsaliheen.com'];
    $enquiryLabel = 'Send an enquiry';
} else {
    $links = [
        ['About the Firm', '/about'],
        ['Practice Areas', '/services'],
    ];
    if ($variant === 'explore') {
        $links[] = ['Explore Legal Services', '/explore-services'];
    }
    $links[] = ['Our Team', '/team'];
    $links[] = ['Contact', '/contact#contact'];
    $intro = 'A remote legal governance office in Bangladesh, supporting boards and management teams with clarity, discretion and client-focused service.';
    $emails = ['info@nsaliheen.com', 'nazmus.nazlee@gmail.com'];
    $enquiryLabel = 'Enquire with the firm';
}
?>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <img
          class="footer-logo"
          src="<?= e(url('/images/saliheen-brand-light.svg')) ?>"
          alt="Saliheen &amp; Associates logo"
        />
        <p><?= e($intro) ?></p>
        <?php if (!$isCompact): ?>
        <p>B176, Lane 23, DOHS, Mohakhali, Dhaka, Bangladesh</p>
        <?php endif; ?>
      </div>
      <div>
        <h4>Explore</h4>
        <div class="footer-links">
          <?php foreach ($links as [$label, $path]): ?>
          <a href="<?= e(url($path)) ?>"><?= e($label) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <h4>Contact</h4>
        <p>
          <?php foreach ($emails as $index => $email): ?>
          <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a><?= $index < count($emails) - 1 ? '<br />' : '' ?>

          <?php endforeach; ?>
          <a href="tel:+8801713079046">+880 1713-079046</a>
        </p>
        <a class="footer-enquiry" href="<?= e(url('/contact#contact')) ?>"><?= e($enquiryLabel) ?> ↗</a>
      </div>
    </div>
  </div>
  <div class="copyright-band">
    <div class="container">
      <div class="copyright">
        <span>© <?= date('Y') ?> Saliheen &amp; Associates.</span>
        <span><?= $variant === 'home'
          ? 'General information only; not legal advice. Services are provided under Bangladesh law and subject to agreed engagement terms.'
          : 'General info only; not legal advice.' ?></span>
      </div>
    </div>
  </div>
</footer>
