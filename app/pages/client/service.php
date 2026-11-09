<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  <title><?= e($title) ?> | Client Services | Saliheen &amp; Associates</title>
  <link rel="icon" type="image/svg+xml" href="<?= url('/images/saliheen-favicon.svg') ?>">
  <link rel="icon" type="image/png" sizes="64x64" href="<?= url('/images/saliheen-favicon.png') ?>">
  <link rel="stylesheet" href="<?= url('/portal/portal.css') ?>">
  <link rel="stylesheet" href="<?= url('/portal/services.css') ?>">
  <script src="<?= url('/portal/portal.js') ?>" defer></script>
  <script src="<?= url('/portal/account.js') ?>" defer></script>
  <script src="<?= url('/portal/profile.js') ?>" defer></script>
</head>
<body class="portal client">
  <a class="skip" href="#main">Skip to content</a>

  <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden">
    <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
    <symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2"/><path d="M10 21v-3h4v3"/></symbol>
    <symbol id="i-scale" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M5 7h14"/><path d="M8 21h8"/><path d="M5 7l-3 6h6L5 7Z"/><path d="M19 7l-3 6h6l-3-6Z"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12.2l3 3 5.2-6"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.4-3 7.9-7 9-4-1.1-7-4.6-7-9V6l7-3Z"/><path d="M9 12l2 2 4-4"/></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></symbol>
    <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7"/><path d="M3 12h18"/></symbol>
    <symbol id="i-gavel" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h8"/><path d="M14.5 3.5l6 6-2.8 2.8-6-6 2.8-2.8Z"/><path d="M12 6l-6 6 3 3 6-6"/><path d="M9 15l-4 4"/></symbol>
    <symbol id="i-bank" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6"/><path d="M5 10v9M9 10v9M15 10v9M19 10v9"/><path d="M3 21h18"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></symbol>
    <symbol id="i-folder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 3h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V10M10 20V4M16 20v-7"/><path d="M2 20h20"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h8l4 4v14H6V3Z"/><path d="M14 3v4h4"/><path d="M9 13h6M9 17h4"/></symbol>
    <symbol id="i-msg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16v11H8l-4 4V5Z"/><path d="M8 9h8M8 12h5"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9a6 6 0 0 1 12 0c0 4 1.4 5.6 2 6H4c.6-.4 2-2 2-6Z"/><path d="M10 19a2 2 0 0 0 4 0"/></symbol>
    <symbol id="i-package" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3Z"/><path d="M4 7.5l8 4.5 8-4.5M12 12v9"/></symbol>
    <symbol id="i-cog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
    <symbol id="i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></symbol>
    <symbol id="i-back" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-upload" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M4 15v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4"/></symbol>
  </svg>

  <aside class="sidebar" id="sidebar" aria-label="Client navigation">
    <div class="sb-brand">
      <a href="<?= url('/') ?>" aria-label="Saliheen &amp; Associates home"><img src="<?= url('/images/saliheen-brand.svg') ?>" alt="Saliheen &amp; Associates" /></a>
      <button class="sb-close" id="sbClose" aria-label="Close navigation"><svg viewBox="0 0 24 24"><use href="#i-close"/></svg></button>
    </div>
    <nav class="sb-nav">
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>"><svg viewBox="0 0 24 24"><use href="#i-grid"/></svg><span>My Dashboard</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#company"><svg viewBox="0 0 24 24"><use href="#i-building"/></svg><span>My Company Profile</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#matters"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>My Legal Matters</span></a>
      <p class="sb-label">Governance</p>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#governance"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg><span>Corporate Governance</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#calendar"><svg viewBox="0 0 24 24"><use href="#i-calendar"/></svg><span>Compliance Calendar</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#cyber"><svg viewBox="0 0 24 24"><use href="#i-shield"/></svg><span>Cyber Risk Management</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#labour"><svg viewBox="0 0 24 24"><use href="#i-briefcase"/></svg><span>Labor &amp; HR Compliance</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#board"><svg viewBox="0 0 24 24"><use href="#i-bank"/></svg><span>Board Decision Support</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#adr"><svg viewBox="0 0 24 24"><use href="#i-gavel"/></svg><span>Disputes &amp; ADR</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#advisory"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>Legal &amp; Financial Advisory</span></a>
      <p class="sb-label">Records &amp; support</p>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#documents"><svg viewBox="0 0 24 24"><use href="#i-folder"/></svg><span>Documents &amp; Downloads</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#reports"><svg viewBox="0 0 24 24"><use href="#i-chart"/></svg><span>Reports</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#requests"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg><span>Advisory Requests</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#messages"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg><span>Messages &amp; Support</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#notifications"><svg viewBox="0 0 24 24"><use href="#i-bell"/></svg><span>Notifications</span></a>
      <p class="sb-label">Account</p>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#subscription"><svg viewBox="0 0 24 24"><use href="#i-package"/></svg><span>My Subscription</span></a>
      <a class="sb-link" href="<?= url('/client/dashboard/') ?>#settings"><svg viewBox="0 0 24 24"><use href="#i-cog"/></svg><span>Account Settings</span></a>
      <p class="sb-label">Services</p>
      <a class="sb-link" href="<?= url('/explore-services/') ?>"><svg viewBox="0 0 24 24"><use href="#i-plus"/></svg><span>Explore More Services</span></a>
    </nav>
    <div class="sb-foot">
      <a class="sb-back" href="<?= url('/') ?>"><svg viewBox="0 0 24 24"><use href="#i-back"/></svg>Public website</a>
      <a class="sb-back" href="<?= url('/login/') ?>" data-logout="<?= url('/api/auth/logout.php') ?>" data-login="<?= url('/login/') ?>" data-csrf="<?= e(csrf_token()) ?>">Sign out</a>
    </div>
  </aside>
  <div class="sb-scrim" id="scrim"></div>

  <div class="main">
    <header class="topbar">
      <button class="icon-btn menu" id="menuBtn" aria-label="Open navigation" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24"><use href="#i-menu"/></svg></button>
      <div class="crumbs"><span>Client</span><span>/</span><strong><?= e($title) ?></strong></div>
      <div class="top-actions">
        <span class="chip ok">Service workspace</span>
        <?php require __DIR__ . '/../../views/partials/client-avatar.php'; ?>
      </div>
    </header>

    <main class="workspace service-workspace" id="main">
      <?php if ($service === null): ?>
        <section class="sw-hero">
          <div class="sw-top">
            <div>
              <p class="eyebrow">Access restricted</p>
              <h1><?= e($blocked['name'] ?? 'Service access restricted') ?></h1>
              <p>This service is not active for your company. Current status: <strong><?= e($statusLabel) ?></strong>. An active assignment is required to open the service workspace.</p>
            </div>
          </div>
        </section>
        <div class="sw-help" style="margin-top: 16px">
          <?php if ($blocked): ?>
            Your request is recorded. Saliheen &amp; Associates will activate the service after review and assign a staff member. Return to <a href="<?= url('/client/dashboard/') ?>">your dashboard</a> to follow its status.
          <?php else: ?>
            This service is not assigned to your company. Visit <a href="<?= url('/explore-services/') ?>">Explore Our Legal Services</a> to submit a request.
          <?php endif; ?>
        </div>
      <?php else: ?>
        <section class="sw-hero">
          <div class="sw-top">
            <div>
              <p class="eyebrow">Active service</p>
              <h1><?= e($service['name']) ?></h1>
              <p><?= e($service['description']) ?></p>
            </div>
            <a class="btn-service" href="<?= url('/client/dashboard/') ?>"><svg viewBox="0 0 24 24"><use href="#i-back"/></svg>Back to dashboard</a>
          </div>
        </section>

        <div class="sw-meta-grid">
          <div class="sw-meta"><span>Status</span><strong><?= e(service_status_label($service['status'])) ?></strong></div>
          <div class="sw-meta"><span>Assigned staff</span><strong><?= e($service['assigned_staff'] ?: 'To be assigned') ?></strong></div>
          <div class="sw-meta"><span>Start date</span><strong><?= e(format_date($service['activation_date'] ?? null)) ?></strong></div>
          <div class="sw-meta"><span>Progress</span><strong><?= (int) ($service['progress'] ?? 0) ?>%</strong></div>
        </div>

        <?php if ((int) ($service['progress'] ?? 0) > 0): ?>
          <div class="progress-track" style="margin-bottom: 16px" role="img" aria-label="<?= (int) $service['progress'] ?> percent complete"><span style="width: <?= (int) $service['progress'] ?>%"></span></div>
        <?php endif; ?>

        <div class="grid-main">
          <section class="card">
            <div class="card-head">
              <div><h2>Service tools</h2><p>Functions included in this service area.</p></div>
            </div>
            <ul class="sw-tools">
              <?php foreach ($service['features'] as $feature): ?>
                <li><svg viewBox="0 0 24 24"><use href="#i-check"/></svg><?= e($feature) ?></li>
              <?php endforeach; ?>
            </ul>
          </section>

          <div class="stack">
            <section class="card">
              <div class="card-head"><div><h2>Matter records</h2><p>Your company's records for this service.</p></div></div>
              <div class="sw-case"><span><b>No matter records yet</b><br><small>New matters appear here when your legal team opens them.</small></span><span class="status-pill neutral">No records</span></div>
              <div class="sw-case"><span><b>Documents</b><br><small>Documents shared for this service will be listed here.</small></span><span class="status-pill neutral">None</span></div>
              <div class="sw-case"><span><b>Advisory requests</b><br><small>Submit requests through Messages &amp; Support.</small></span><span class="status-pill info">Available</span></div>
            </section>

            <section class="card">
              <div class="card-head"><div><h2>Service notes</h2><p>Information from your legal team.</p></div></div>
              <?php if (!empty($service['admin_notes'])): ?>
                <p class="sub" style="font-size: 12px"><?= nl2br(e($service['admin_notes'])) ?></p>
              <?php else: ?>
                <p class="sub" style="font-size: 12px">No service notes have been added. Your assigned staff member will share updates through the portal.</p>
              <?php endif; ?>
            </section>
          </div>
        </div>

        <div class="sw-help" style="margin-top: 16px">
          Need help with <?= e($service['name']) ?>? Email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a> quoting your company name and service. Only records belonging to your company are available in this workspace.
        </div>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
