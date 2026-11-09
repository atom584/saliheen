<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  <title>Admin Dashboard | Saliheen &amp; Associates</title>
  <link rel="icon" type="image/svg+xml" href="<?= url('/images/saliheen-favicon.svg') ?>">
  <link rel="icon" type="image/png" sizes="64x64" href="<?= url('/images/saliheen-favicon.png') ?>">
  <link rel="stylesheet" href="<?= url('/portal/portal.css') ?>">
  <link rel="stylesheet" href="<?= url('/portal/services.css') ?>">
  <script src="<?= url('/portal/portal.js') ?>" defer></script>
  <script src="<?= url('/portal/account.js') ?>" defer></script>
  <script src="<?= url('/portal/views.js') ?>" defer></script>
</head>
<body class="portal admin">
  <a class="skip" href="#main">Skip to content</a>

  <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden">
    <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M2.8 20c0-3.5 2.8-6.3 6.2-6.3s6.2 2.8 6.2 6.3"/><path d="M16.6 5.4a3.2 3.2 0 0 1 0 6"/><path d="M18.4 20c0-2.6-.9-4.7-2.5-5.9"/></symbol>
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
    <symbol id="i-package" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3Z"/><path d="M4 7.5l8 4.5 8-4.5M12 12v9"/></symbol>
    <symbol id="i-card" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h3"/></symbol>
    <symbol id="i-id" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2.3"/><path d="M6.3 16.2c.4-1.5 1.4-2.3 2.7-2.3s2.3.8 2.7 2.3"/><path d="M15 10h3M15 14h3"/></symbol>
    <symbol id="i-msg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16v11H8l-4 4V5Z"/><path d="M8 9h8M8 12h5"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9a6 6 0 0 1 12 0c0 4 1.4 5.6 2 6H4c.6-.4 2-2 2-6Z"/><path d="M10 19a2 2 0 0 0 4 0"/></symbol>
    <symbol id="i-history" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3.2 12a8.8 8.8 0 1 0 2.6-6.2"/><path d="M3 4v5h5"/><path d="M12 8v4l3 2"/></symbol>
    <symbol id="i-cog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
    <symbol id="i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h8l4 4v14H6V3Z"/><path d="M14 3v4h4"/><path d="M9 13h6M9 17h4"/></symbol>
    <symbol id="i-back" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
  </svg>

  <aside class="sidebar" id="sidebar" aria-label="Admin navigation">
    <div class="sb-brand">
      <a href="<?= url('/') ?>" aria-label="Saliheen &amp; Associates home"><img src="<?= url('/images/saliheen-brand-light.svg') ?>" alt="Saliheen &amp; Associates" /></a>
      <button class="sb-close" id="sbClose" aria-label="Close navigation"><svg viewBox="0 0 24 24"><use href="#i-close"/></svg></button>
    </div>
    <nav class="sb-nav">
      <p class="sb-label">Overview</p>
      <a class="sb-link active" href="#" aria-current="page"><svg viewBox="0 0 24 24"><use href="#i-grid"/></svg><span>Dashboard</span></a>
      <a class="sb-link" href="<?= url('/admin/services/') ?>"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>Service Management</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-users"/></svg><span>Client Management</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-building"/></svg><span>Company Management</span></a>

      <p class="sb-label">Legal operations</p>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>Legal Matters</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg><span>Corporate Governance</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-shield"/></svg><span>Compliance Management</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-lock"/></svg><span>Cyber Governance</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-briefcase"/></svg><span>Labor &amp; HR Governance</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-bank"/></svg><span>Board Decision Support</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-gavel"/></svg><span>Disciplinary &amp; ADR</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-bank"/></svg><span>Legal &amp; Financial Advisory</span></a>

      <p class="sb-label">Workflow</p>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg><span>Task Management</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-calendar"/></svg><span>Compliance Calendar</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-folder"/></svg><span>Document Management</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-chart"/></svg><span>Reports &amp; Analytics</span></a>

      <p class="sb-label">Commercial</p>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-package"/></svg><span>Service Packages</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-card"/></svg><span>Billing &amp; Payments</span></a>

      <p class="sb-label">Administration</p>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-id"/></svg><span>User Management</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg><span>Messages &amp; Support</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-bell"/></svg><span>Notifications</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-history"/></svg><span>Audit Logs</span></a>
      <a class="sb-link" href="#"><svg viewBox="0 0 24 24"><use href="#i-cog"/></svg><span>Settings</span></a>
    </nav>
    <div class="sb-foot">
      <a class="sb-back" href="<?= url('/') ?>"><svg viewBox="0 0 24 24"><use href="#i-back"/></svg>Public website</a>
      <a class="sb-back" href="<?= url('/admin-login/') ?>" data-logout="<?= url('/api/auth/logout.php') ?>" data-login="<?= url('/admin-login/') ?>" data-csrf="<?= e(csrf_token()) ?>">Sign out</a>
    </div>
  </aside>
  <div class="sb-scrim" id="scrim"></div>

  <div class="main">
    <header class="topbar">
      <button class="icon-btn menu" id="menuBtn" aria-label="Open navigation" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24"><use href="#i-menu"/></svg></button>
      <div class="crumbs"><span>Admin</span><span>/</span><strong>Dashboard</strong></div>
      <div class="top-actions">
        <label class="search"><svg viewBox="0 0 24 24"><use href="#i-search"/></svg><input type="search" placeholder="Search clients, matters, documents" aria-label="Search" /></label>
        <span class="chip ok">Portal connected</span>
        <button class="icon-btn bell" aria-label="Notifications"><svg viewBox="0 0 24 24"><use href="#i-bell"/></svg><span class="dot"></span></button>
        <div class="avatar" title="<?= e($admin['full_name']) ?>"><?= e($initials ?: 'SA') ?></div>
      </div>
    </header>

    <main class="workspace" id="main">
      <div class="view" id="view-home">
      <?= $flashHtml ?>
      <div class="page-head">
        <div>
          <p class="eyebrow">Legal governance operations</p>
          <h1>Welcome back, <?= e(strtok($admin['full_name'], ' ')) ?></h1>
          <p class="sub">Here's the live service overview for Saliheen &amp; Associates.</p>
        </div>
        <div class="head-actions">
          <a class="btn ghost" href="<?= url('/admin/services/') ?>#service-requests">Service requests</a>
          <a class="btn solid" href="<?= url('/admin/services/') ?>"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg>Service Management</a>
        </div>
      </div>

      <section class="stat-grid" aria-label="Key statistics">
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Registered clients</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-users"/></svg></span></div>
          <strong><?= (int) $stats['clients'] ?></strong>
          <small><span class="delta up"><?= (int) $stats['recent_clients'] ?></span> joined in 30 days</small>
        </article>
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Companies</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-building"/></svg></span></div>
          <strong><?= (int) $stats['companies'] ?></strong>
          <small>one company per account</small>
        </article>
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Active service assignments</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg></span></div>
          <strong><?= (int) $stats['active_services'] ?></strong>
          <small>across all client companies</small>
        </article>
        <article class="stat">
          <div class="stat-top"><span class="stat-label">Pending service requests</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#i-calendar"/></svg></span></div>
          <strong><?= (int) $stats['pending_requests'] ?></strong>
          <small><a href="<?= url('/admin/services/') ?>#service-requests">Review requests</a></small>
        </article>
      </section>

      <div class="grid-charts">
        <section class="card">
          <div class="card-head">
            <div>
              <h2>Client registrations</h2>
              <p>New client accounts over the last six months.</p>
            </div>
            <a class="link" href="<?= url('/admin/services/') ?>#analytics">Service analytics</a>
          </div>
          <div class="trend-bars" role="img" aria-label="Client registrations for the last six months">
            <?php foreach ($analytics['trend'] as $month): ?>
              <?php $height = max(3, (int) round(((int) $month['registrations'] / $maxRegistrations) * 100)); ?>
              <div class="trend-bar" title="<?= e($month['month']) ?>: <?= (int) $month['registrations'] ?> registrations">
                <b><?= (int) $month['registrations'] ?></b>
                <i style="height: <?= $height ?>%"></i>
                <small><?= e(date('M', strtotime($month['month'] . '-01'))) ?></small>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="card">
          <div class="card-head">
            <div>
              <h2>Service catalogue</h2>
              <p>Client assignments by service.</p>
            </div>
            <a class="link" href="<?= url('/admin/services/') ?>#catalogue">Manage</a>
          </div>
          <ul class="list">
            <?php foreach (array_slice($analytics['services'], 0, 4) as $service): ?>
              <li>
                <span class="stat-icon" style="flex: none"><svg viewBox="0 0 24 24"><use href="#<?= e($service['icon']) ?>"/></svg></span>
                <span class="meta"><b><?= e($service['name']) ?></b><span><?= (int) $service['active_clients'] ?> active · <?= (int) $service['pending_clients'] ?> pending</span></span>
                <span class="pill <?= (int) $service['total_clients'] > 0 ? 'info' : 'neutral' ?>"><?= (int) $service['total_clients'] ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>
      </div>

      <div class="grid-main">
        <section class="card">
          <div class="card-head">
            <div>
              <h2>Recent service requests</h2>
              <p>Latest client requests for additional services.</p>
            </div>
            <a class="link" href="<?= url('/admin/services/') ?>#service-requests">All requests</a>
          </div>
          <?php if ($recentRequests): ?>
            <div class="table-wrap">
              <table class="t">
                <thead><tr><th>Request ID</th><th>Client</th><th>Service</th><th>Requested</th><th>Status</th></tr></thead>
                <tbody>
                  <?php foreach ($recentRequests as $request): ?>
                    <tr>
                      <td><?= e(service_request_ref((int) $request['id'])) ?></td>
                      <td><b><?= e($request['client_name']) ?></b><span class="meta"><?= e($request['company_name']) ?></span></td>
                      <td><?= e($request['service_name']) ?></td>
                      <td><?= e(format_date($request['created_at'])) ?></td>
                      <td><span class="pill <?= e(match ($request['status']) {
                          'approved' => 'ok',
                          'rejected' => 'high',
                          'under_review' => 'info',
                          default => 'med',
                      }) ?>"><?= e(request_status_label($request['status'])) ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="sub" style="font-size: 12px">No service requests have been submitted yet. New requests appear here for review and approval.</p>
          <?php endif; ?>
        </section>

        <div class="stack">
          <section class="card">
            <div class="card-head">
              <div><h2>Pending approvals</h2><p>Awaiting firm review.</p></div>
              <span class="pill info"><?= count($pending) ?></span>
            </div>
            <?php if ($pending): ?>
              <ul class="feed">
                <?php foreach (array_slice($pending, 0, 5) as $row): ?>
                  <li><b><?= e($row['client_name']) ?> — <?= e($row['service_name']) ?></b><span><?= e($row['company_name']) ?> · <?= e(format_date($row['created_at'])) ?></span></li>
                <?php endforeach; ?>
              </ul>
              <a class="btn ghost" style="margin-top: 12px; width: 100%" href="<?= url('/admin/services/') ?>#service-requests">Review requests</a>
            <?php else: ?>
              <p class="sub" style="font-size: 12px">No requests are awaiting review.</p>
            <?php endif; ?>
          </section>

          <section class="card">
            <div class="card-head">
              <div><h2>Recent registrations</h2><p>Newest client accounts.</p></div>
            </div>
            <?php if ($analytics['recent']): ?>
              <ul class="feed">
                <?php foreach (array_slice($analytics['recent'], 0, 4) as $row): ?>
                  <li><b><?= e($row['company_name']) ?></b><span><?= e($row['full_name']) ?> · <?= e(format_date($row['created_at'])) ?><?= !empty($row['services']) ? ' · ' . e($row['services']) : '' ?></span></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="sub" style="font-size: 12px">No client accounts have been registered yet.</p>
            <?php endif; ?>
          </section>
        </div>
      </div>

      <section class="card">
        <div class="card-head"><div><h2>Service management</h2><p>Activate services, assign staff and track progress.</p></div></div>
        <div class="quick">
          <a href="<?= url('/admin/services/') ?>#service-requests"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg></span>Review service requests<span>&rarr;</span></a>
          <a href="<?= url('/admin/services/') ?>#assignments"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-users"/></svg></span>Manage assignments<span>&rarr;</span></a>
          <a href="<?= url('/admin/services/') ?>#catalogue"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg></span>Service catalogue<span>&rarr;</span></a>
          <a href="<?= url('/admin/services/') ?>#analytics"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-chart"/></svg></span>Service analytics<span>&rarr;</span></a>
        </div>
      </section>

      <p class="note">Figures on this page are read from the portal database. Other workspace modules remain available from the sidebar.</p>
      </div>
      <div class="view" id="view-module" hidden></div>
    </main>
  </div>
</body>
</html>
