<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  <title>Client Dashboard | Saliheen &amp; Associates</title>
  <link rel="icon" type="image/svg+xml" href="<?= url('/images/saliheen-favicon.svg') ?>">
  <link rel="icon" type="image/png" sizes="64x64" href="<?= url('/images/saliheen-favicon.png') ?>">
  <link rel="stylesheet" href="<?= url('/portal/portal.css') ?>">
  <link rel="stylesheet" href="<?= url('/portal/services.css') ?>">
  <script src="<?= url('/portal/portal.js') ?>" defer></script>
  <script src="<?= url('/portal/account.js') ?>" defer></script>
  <script src="<?= url('/portal/views.js') ?>" defer></script>
  <script src="<?= url('/portal/profile.js') ?>" defer></script>
  <script src="<?= url('/portal/paging.js') ?>" defer></script>
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
      <a class="sb-link active" href="#" aria-current="page"><svg viewBox="0 0 24 24"><use href="#i-grid"/></svg><span>My Dashboard</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-building"/></svg><span>My Company Profile</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>My Legal Matters</span></a>
      <p class="sb-label">Governance</p>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg><span>Corporate Governance</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-calendar"/></svg><span>Compliance Calendar</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-shield"/></svg><span>Cyber Risk Management</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-briefcase"/></svg><span>Labor &amp; HR Compliance</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-bank"/></svg><span>Board Decision Support</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-gavel"/></svg><span>Disputes &amp; ADR</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>Legal &amp; Financial Advisory</span></a>
      <p class="sb-label">Records &amp; support</p>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-folder"/></svg><span>Documents &amp; Downloads</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-chart"/></svg><span>Reports</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg><span>Advisory Requests</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg><span>Messages &amp; Support</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-bell"/></svg><span>Notifications</span></a>
      <p class="sb-label">Account</p>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-package"/></svg><span>My Subscription</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-cog"/></svg><span>Account Settings</span></a>
      <p class="sb-label">Services</p>
      <a class="sb-link" href="<?= url('/explore-services') ?>"><svg viewBox="0 0 24 24"><use href="#i-plus"/></svg><span>Explore More Services</span></a>
    </nav>
    <div class="sb-foot">
      <a class="sb-back" href="<?= url('/') ?>"><svg viewBox="0 0 24 24"><use href="#i-back"/></svg>Public website</a>
      <a class="sb-back" href="<?= url('/login') ?>" data-logout="<?= url('/api/auth/logout.php') ?>" data-login="<?= url('/login') ?>" data-csrf="<?= e(csrf_token()) ?>">Sign out</a>
    </div>
  </aside>
  <div class="sb-scrim" id="scrim"></div>

  <div class="main">
    <header class="topbar">
      <button class="icon-btn menu" id="menuBtn" aria-label="Open navigation" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24"><use href="#i-menu"/></svg></button>
      <div class="crumbs"><span>Client</span><span>/</span><strong>Dashboard</strong></div>
      <div class="top-actions">
        <label class="search"><svg viewBox="0 0 24 24"><use href="#i-search"/></svg><input type="search" placeholder="Search matters, documents, requests" aria-label="Search" /></label>
        <span class="chip ok">Portal connected</span>
        <button class="icon-btn bell" aria-label="Notifications"><svg viewBox="0 0 24 24"><use href="#i-bell"/></svg><span class="dot"></span></button>
        <?php require __DIR__ . '/../../views/partials/client-avatar.php'; ?>
      </div>
    </header>

    <main class="workspace" id="main">
      <div class="view" id="view-home">
      <?= $flashHtml ?>

      <section class="client-hero" aria-labelledby="clientWelcome">
        <div>
          <p class="eyebrow"><?= e($company['name'] ?? $user['company_name'] ?? 'Client company') ?><?= !empty($company['business_type']) ? ' · ' . e($company['business_type']) : '' ?></p>
          <h1 id="clientWelcome">Welcome, <?= e($user['full_name']) ?></h1>
          <p>Your legal governance workspace is ready.</p>
          <span class="company-chip"><?= e(ucfirst((string) ($user['company_role'] ?? 'admin'))) ?> · Company verified</span>
        </div>
        <div class="hero-actions">
          <a class="btn gold" href="<?= url('/explore-services') ?>"><svg viewBox="0 0 24 24"><use href="#i-plus"/></svg>Explore More Services</a>
          <a class="btn outline-light" href="#selected-services"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg>View my services</a>
        </div>
      </section>

      <section class="selected-services-section" id="selected-services" aria-labelledby="selectedServicesHeading">
        <div class="selected-services-head">
          <div>
            <h2 id="selectedServicesHeading">MY SELECTED SERVICES</h2>
            <p>Services assigned to <?= e($company['name'] ?? 'your company') ?>. Only active services open a dedicated workspace.</p>
          </div>
          <a class="text-link" href="<?= url('/explore-services') ?>">Explore More Services &rarr;</a>
        </div>

        <?php if ($services): ?>
          <div class="selected-services-grid">
            <?php foreach ($services as $service): ?>
              <?php $active = $service['status'] === 'active'; ?>
              <article class="svc-card">
                <div class="svc-card-top">
                  <span class="svc-card-icon"><svg viewBox="0 0 24 24"><use href="#<?= e($service['icon']) ?>"/></svg></span>
                  <span class="status-pill <?= e(service_status_tone($service['status'])) ?>"><?= e(service_status_label($service['status'])) ?></span>
                </div>
                <h3><?= e($service['name']) ?></h3>
                <p><?= e($service['description']) ?></p>
                <div class="svc-meta">
                  <?php if (!empty($service['assigned_staff'])): ?><span>Assigned: <strong><?= e($service['assigned_staff']) ?></strong></span><?php endif; ?>
                  <?php if (!empty($service['activation_date'])): ?><span>Started: <strong><?= e(format_date($service['activation_date'])) ?></strong></span><?php endif; ?>
                  <?php if (!empty($service['request_date']) && !$active): ?><span>Requested: <strong><?= e(format_date($service['request_date'])) ?></strong></span><?php endif; ?>
                </div>
                <?php if ((int) ($service['progress'] ?? 0) > 0): ?>
                  <div class="progress-track" style="margin: 0 0 12px" role="img" aria-label="<?= (int) $service['progress'] ?> percent complete"><span style="width: <?= (int) $service['progress'] ?>%"></span></div>
                <?php endif; ?>
                <?php if ($active): ?>
                  <a class="btn-service" href="<?= e(service_dashboard_url($service)) ?>">Open Service Dashboard <svg viewBox="0 0 24 24"><use href="#i-arrow"/></svg></a>
                <?php else: ?>
                  <span class="svc-waiting"><?= $service['status'] === 'inactive' ? 'Awaiting email verification' : 'Awaiting firm approval' ?></span>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="svc-empty">
            <h3>No services assigned yet</h3>
            <p>Choose a legal service to attach it to your company workspace.</p>
            <a class="btn gold" href="<?= url('/explore-services') ?>">Explore Our Legal Services</a>
          </div>
        <?php endif; ?>

        <div class="explore-more-bar">
          <p>Need another service? Additional services are submitted to the firm for approval before activation.</p>
          <a class="btn gold" href="<?= url('/explore-services') ?>">Explore More Services <svg viewBox="0 0 24 24"><use href="#i-arrow"/></svg></a>
        </div>
      </section>

      <section class="stat-grid" aria-label="Your key figures">
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Active services</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg></span></div>
          <strong><?= (int) $counts['active'] ?></strong>
          <small>available in your workspace</small>
        </article>
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Pending requests</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-calendar"/></svg></span></div>
          <strong><?= (int) $pendingTotal ?></strong>
          <small>awaiting firm review</small>
        </article>
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Reserved services</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-lock"/></svg></span></div>
          <strong><?= (int) $counts['inactive'] ?></strong>
          <small>activate after email verification</small>
        </article>
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Available services</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg></span></div>
          <strong><?= (int) $data['available'] ?></strong>
          <small><?= count($availableServices) ?> not yet added to your company</small>
        </article>
      </section>

      <div class="grid-charts">
        <section class="card">
          <div class="card-head">
            <div>
              <h2>Service status overview</h2>
              <p>Assignment status across your company's legal services.</p>
            </div>
            <a class="link" href="#selected-services">View services</a>
          </div>
          <ul class="legend" style="margin-top: 4px">
            <li><span class="sw burgundy"></span>Active<b><?= (int) $counts['active'] ?></b></li>
            <li><span class="sw gold"></span>Pending approval<b><?= (int) $counts['pending'] ?></b></li>
            <li><span class="sw softgold"></span>Under review<b><?= (int) $counts['review'] ?></b></li>
            <li><span class="sw grey"></span>Reserved / inactive<b><?= (int) $counts['inactive'] ?></b></li>
          </ul>
          <p class="sub" style="margin-top: 18px; font-size: 12px">Only the firm can activate additional services. Active services open a dedicated dashboard with tools and records for that service area.</p>
        </section>

        <section class="card">
          <div class="card-head">
            <div>
              <h2>Your company</h2>
              <p>Registered client details.</p>
            </div>
            <span class="pill info">One company per account</span>
          </div>
          <dl class="profile-list">
            <div><dt>Company</dt><dd><?= e($company['name'] ?? $user['company_name'] ?? '—') ?></dd></div>
            <div><dt>Business type</dt><dd><?= e($company['business_type'] ?? '—') ?></dd></div>
            <div><dt>Country</dt><dd><?= e($company['country'] ?? $user['country'] ?? '—') ?></dd></div>
            <div><dt>Primary contact</dt><dd><?= e($user['email']) ?></dd></div>
            <div><dt>Client since</dt><dd><?= e(format_date($company['created_at'] ?? null, 'F Y')) ?></dd></div>
            <div><dt>Your role</dt><dd><?= e(ucfirst((string) ($user['company_role'] ?? 'admin'))) ?></dd></div>
          </dl>
          <p class="sub" style="margin-top: 16px; font-size: 11.5px">Contact your legal team to update registered company details.</p>
        </section>
      </div>

      <div class="grid-main">
        <section class="card">
          <div class="card-head">
            <div>
              <h2>Service request history</h2>
              <p>Additional services requested for your company.</p>
            </div>
            <a class="link" href="<?= url('/explore-services') ?>">Request a service</a>
          </div>
          <div id="request-results" data-pager-region>
          <?php if ($requests): ?>
            <div class="table-wrap">
              <table class="t">
                <thead>
                  <tr><th>Request ID</th><th>Service</th><th>Submitted</th><th>Status</th></tr>
                </thead>
                <tbody>
                  <?php foreach ($requests as $request): ?>
                    <tr>
                      <td><?= e(service_request_ref((int) $request['id'])) ?></td>
                      <td><b><?= e($request['service_name']) ?></b><?php if (!empty($request['request_description'])): ?><span class="meta"><?= e($request['request_description']) ?></span><?php endif; ?></td>
                      <td><?= e(format_date($request['created_at'])) ?></td>
                      <td><span class="pill <?= e(match ($request['status']) {
                          'approved' => 'ok',
                          'rejected' => 'high',
                          'cancelled' => 'neutral',
                          'under_review' => 'info',
                          default => 'med',
                      }) ?>"><?= e(request_status_label($request['status'])) ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="sub" style="font-size: 12px">No additional service requests have been submitted. Use <a class="text-link" href="<?= url('/explore-services') ?>">Explore More Services</a> if your company needs another service area.</p>
          <?php endif; ?>
          <?php App\Core\View::partial('pager', ['paginator' => $paginator, 'label' => 'Service request pages']); ?>
          </div>
        </section>

        <div class="stack">
          <section class="card">
            <div class="card-head">
              <div><h2>Available services</h2><p>Not yet added to your company.</p></div>
              <a class="link" href="<?= url('/explore-services') ?>">Explore</a>
            </div>
            <?php if ($availableServices): ?>
              <ul class="list">
                <?php foreach (array_slice($availableServices, 0, 4) as $service): ?>
                  <li>
                    <span class="stat-icon" style="flex: none"><svg viewBox="0 0 24 24"><use href="#<?= e($service['icon']) ?>"/></svg></span>
                    <span class="meta"><b><?= e($service['name']) ?></b><span><?= e($service['description']) ?></span></span>
                  </li>
                <?php endforeach; ?>
              </ul>
              <a class="btn ghost" style="margin-top: 14px; width: 100%" href="<?= url('/explore-services') ?>">Explore More Services</a>
            <?php else: ?>
              <p class="sub" style="font-size: 12px">Your company already has every published service area assigned or requested.</p>
            <?php endif; ?>
          </section>

          <section class="card">
            <div class="card-head">
              <div><h2>Account &amp; support</h2><p>Your access and assistance.</p></div>
            </div>
            <ul class="feed">
              <li><b>Account email verified</b><span><?= e($user['email']) ?> · <?= e(format_date($user['email_verified_at'] ?? null)) ?></span></li>
              <li><b>Selected service activates on verification</b><span>Reserved services are activated automatically after email verification.</span></li>
              <li><b>Additional services require firm approval</b><span>Requests are reviewed before activation, and staff assignments follow approval.</span></li>
              <li><b>Need help?</b><span>Email <a class="text-link" href="mailto:info@nsaliheen.com">info@nsaliheen.com</a> with your company name.</span></li>
            </ul>
          </section>
        </div>
      </div>

      <section class="card">
        <div class="card-head"><div><h2>Quick actions</h2><p>Common tasks in your workspace.</p></div></div>
        <div class="quick">
          <a href="<?= url('/explore-services') ?>"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-plus"/></svg></span>Explore more services<span>&rarr;</span></a>
          <a href="#selected-services"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg></span>My selected services<span>&rarr;</span></a>
          <a href="#documents"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-upload"/></svg></span>Upload documents<span>&rarr;</span></a>
          <a href="#calendar"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-calendar"/></svg></span>View compliance calendar<span>&rarr;</span></a>
          <a href="#reports"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-chart"/></svg></span>Download reports<span>&rarr;</span></a>
          <a href="#messages"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg></span>Contact legal team<span>&rarr;</span></a>
        </div>
      </section>

      <p class="note">Service assignments, statuses and requests shown here are read from the portal database. Other workspace modules remain available from the sidebar.</p>
      </div>
      <div class="view" id="view-module" hidden></div>
    </main>
  </div>
</body>
</html>
