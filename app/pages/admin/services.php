<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  <title>Service Management | Admin | Saliheen &amp; Associates</title>
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
    <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
    <symbol id="i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></symbol>
    <symbol id="i-back" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h8l4 4v14H6V3Z"/><path d="M14 3v4h4"/><path d="M9 13h6M9 17h4"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></symbol>
  </svg>

  <aside class="sidebar" id="sidebar" aria-label="Admin navigation">
    <div class="sb-brand">
      <a href="<?= url('/') ?>" aria-label="Saliheen &amp; Associates home"><img src="<?= url('/images/saliheen-brand-light.svg') ?>" alt="Saliheen &amp; Associates" /></a>
      <button class="sb-close" id="sbClose" aria-label="Close navigation"><svg viewBox="0 0 24 24"><use href="#i-close"/></svg></button>
    </div>
    <nav class="sb-nav">
      <p class="sb-label">Overview</p>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>"><svg viewBox="0 0 24 24"><use href="#i-grid"/></svg><span>Dashboard</span></a>
      <a class="sb-link active" href="<?= url('/admin/services/') ?>" aria-current="page"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>Service Management</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#clients"><svg viewBox="0 0 24 24"><use href="#i-users"/></svg><span>Client Management</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#companies"><svg viewBox="0 0 24 24"><use href="#i-building"/></svg><span>Company Management</span></a>

      <p class="sb-label">Legal operations</p>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#matters"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg><span>Legal Matters</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#governance"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg><span>Corporate Governance</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#compliance"><svg viewBox="0 0 24 24"><use href="#i-shield"/></svg><span>Compliance Management</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#cyber"><svg viewBox="0 0 24 24"><use href="#i-lock"/></svg><span>Cyber Governance</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#labour"><svg viewBox="0 0 24 24"><use href="#i-briefcase"/></svg><span>Labor &amp; HR Governance</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#board"><svg viewBox="0 0 24 24"><use href="#i-bank"/></svg><span>Board Decision Support</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#adr"><svg viewBox="0 0 24 24"><use href="#i-gavel"/></svg><span>Disciplinary &amp; ADR</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#advisory"><svg viewBox="0 0 24 24"><use href="#i-bank"/></svg><span>Legal &amp; Financial Advisory</span></a>

      <p class="sb-label">Workflow</p>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#tasks"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg><span>Task Management</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#calendar"><svg viewBox="0 0 24 24"><use href="#i-calendar"/></svg><span>Compliance Calendar</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#documents"><svg viewBox="0 0 24 24"><use href="#i-folder"/></svg><span>Document Management</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#reports"><svg viewBox="0 0 24 24"><use href="#i-chart"/></svg><span>Reports &amp; Analytics</span></a>

      <p class="sb-label">Commercial</p>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#packages"><svg viewBox="0 0 24 24"><use href="#i-package"/></svg><span>Service Packages</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#billing"><svg viewBox="0 0 24 24"><use href="#i-card"/></svg><span>Billing &amp; Payments</span></a>

      <p class="sb-label">Administration</p>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#users"><svg viewBox="0 0 24 24"><use href="#i-id"/></svg><span>User Management</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#messages"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg><span>Messages &amp; Support</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#notifications"><svg viewBox="0 0 24 24"><use href="#i-bell"/></svg><span>Notifications</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#audit"><svg viewBox="0 0 24 24"><use href="#i-history"/></svg><span>Audit Logs</span></a>
      <a class="sb-link" href="<?= url('/admin/dashboard/') ?>#settings"><svg viewBox="0 0 24 24"><use href="#i-cog"/></svg><span>Settings</span></a>
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
      <div class="crumbs"><span>Admin</span><span>/</span><strong>Service Management</strong></div>
      <div class="top-actions">
        <span class="chip ok"><?= count($requests) ?> requests shown</span>
        <div class="avatar" title="<?= e($admin['full_name']) ?>"><?= e($initials ?: 'SA') ?></div>
      </div>
    </header>

    <main class="workspace" id="main">
      <?= $flashHtml ?>

      <div class="page-head">
        <div>
          <p class="eyebrow">Client services</p>
          <h1>Service Management</h1>
          <p class="sub">Review requests, activate assignments and manage the eight legal service areas.</p>
        </div>
        <div class="head-actions">
          <a class="btn ghost" href="<?= url('/admin/dashboard/') ?>"><svg viewBox="0 0 24 24"><use href="#i-back"/></svg>Dashboard</a>
        </div>
      </div>

      <section class="analytics-grid" id="analytics" aria-label="Service analytics">
        <article class="analytics-card"><span>Registered clients</span><strong><?= (int) $stats['clients'] ?></strong><small><?= (int) $stats['recent_clients'] ?> joined in the last 30 days</small></article>
        <article class="analytics-card"><span>Active assignments</span><strong><?= (int) $stats['active_services'] ?></strong><small>across <?= (int) $stats['companies'] ?> companies</small></article>
        <article class="analytics-card"><span>Pending requests</span><strong><?= (int) $stats['pending_requests'] ?></strong><small>awaiting review or approval</small></article>
        <article class="analytics-card"><span>Service catalogue</span><strong><?= count($allServices) ?></strong><small><?= count(array_filter($allServices, static fn (array $s): bool => $s['status'] === 'active')) ?> published services</small></article>
      </section>

      <section id="catalogue" aria-labelledby="catalogueHeading">
        <div class="card-head" style="margin-bottom: 10px">
          <div><h2 id="catalogueHeading">Service catalogue</h2><p>All eight legal service areas and their client assignments.</p></div>
        </div>
        <div class="service-catalog-grid">
          <?php foreach ($analytics['services'] as $service): ?>
            <?php $catalogue = null; foreach ($allServices as $candidate) { if ((int) $candidate['id'] === (int) $service['id']) { $catalogue = $candidate; break; } } ?>
            <article class="catalog-card">
              <span class="catalog-icon"><svg viewBox="0 0 24 24"><use href="#<?= e($service['icon']) ?>"/></svg></span>
              <h3><?= e($service['name']) ?></h3>
              <p><?= e($catalogue['status'] ?? 'active') === 'active' ? 'Published' : 'Withdrawn' ?><?= !empty($catalogue['slug']) ? ' · ' . e($catalogue['slug']) : '' ?></p>
              <div class="catalog-counts">
                <span>Clients <b><?= (int) $service['total_clients'] ?></b></span>
                <span>Active <b><?= (int) $service['active_clients'] ?></b></span>
                <span>Pending <b><?= (int) $service['pending_clients'] ?></b></span>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="card" style="margin-bottom: 16px" aria-labelledby="trendHeading">
        <div class="card-head">
          <div><h2 id="trendHeading">Recent registrations and requests</h2><p>Client registrations and additional-service requests over six months.</p></div>
        </div>
        <div class="trend-bars" role="img" aria-label="Registration and service request trends for the last six months">
          <?php foreach ($analytics['trend'] as $month): ?>
            <?php $registrationHeight = max(3, (int) round(((int) $month['registrations'] / $maxTrend) * 100)); ?>
            <?php $requestHeight = max(3, (int) round(((int) $month['requests'] / $maxTrend) * 100)); ?>
            <div class="trend-bar" title="<?= e($month['month']) ?>: <?= (int) $month['registrations'] ?> registrations, <?= (int) $month['requests'] ?> requests">
              <b><?= (int) $month['registrations'] ?> / <?= (int) $month['requests'] ?></b>
              <i style="height: <?= $registrationHeight ?>%"></i>
              <i style="height: <?= $requestHeight ?>%; background: #c6a15b"></i>
              <small><?= e(date('M', strtotime($month['month'] . '-01'))) ?></small>
            </div>
          <?php endforeach; ?>
        </div>
        <p class="admin-note" style="margin-top: 10px"><strong>Burgundy</strong> client registrations · <strong>Gold</strong> service requests.</p>
      </section>

      <section id="service-requests" aria-labelledby="requestsHeading">
        <div class="card-head" style="margin-bottom: 10px">
          <div><h2 id="requestsHeading">Service requests</h2><p>Filter by service, client, date or status. Only administrators can approve and activate additional services.</p></div>
        </div>

        <form class="filter-bar" method="get" action="<?= url('/admin/services/') ?>">
          <div class="field">
            <label for="filterQ">Client or company</label>
            <input id="filterQ" name="q" type="search" value="<?= e($filters['q']) ?>" placeholder="Name, email or company">
          </div>
          <div class="field">
            <label for="filterService">Service</label>
            <select id="filterService" name="service">
              <option value="">All services</option>
              <?php foreach ($allServices as $service): ?>
                <option value="<?= e($service['slug']) ?>"<?= $filters['service'] === $service['slug'] ? ' selected' : '' ?>><?= e($service['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="filterStatus">Status</label>
            <select id="filterStatus" name="status">
              <option value="">All statuses</option>
              <?php foreach (['pending' => 'Pending Approval', 'under_review' => 'Under Review', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'] as $value => $label): ?>
                <option value="<?= e($value) ?>"<?= $filters['status'] === $value ? ' selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="filterFrom">From</label>
            <input id="filterFrom" name="from" type="date" value="<?= e($filters['from']) ?>">
          </div>
          <div class="field">
            <label for="filterTo">To</label>
            <input id="filterTo" name="to" type="date" value="<?= e($filters['to']) ?>">
          </div>
          <div class="field filter-actions" style="display: flex; gap: 6px">
            <button class="btn solid" type="submit">Filter</button>
            <a class="btn ghost" href="<?= url('/admin/services/') ?>">Reset</a>
          </div>
        </form>

        <?php if ($requests): ?>
          <div class="table-wrap">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Request ID</th>
                  <th>Client Name</th>
                  <th>Company Name</th>
                  <th>Selected Service</th>
                  <th>Registration Date</th>
                  <th>Assigned Staff</th>
                  <th>Request Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($requests as $request): ?>
                  <tr>
                    <td><b><?= e(service_request_ref((int) $request['id'])) ?></b></td>
                    <td><b><?= e($request['client_name']) ?></b><span class="meta"><?= e($request['client_email']) ?></span></td>
                    <td><b><?= e($request['company_name']) ?></b><span class="meta"><?= e($request['country'] ?? $request['company_country'] ?? '') ?></span></td>
                    <td><b><?= e($request['service_name']) ?></b></td>
                    <td><?= e(format_date($request['created_at'])) ?></td>
                    <td><?= e($request['assigned_staff'] ?: '—') ?></td>
                    <td><span class="status-pill <?= e(match ($request['status']) {
                        'approved' => 'ok',
                        'rejected' => 'neutral',
                        'under_review' => 'info',
                        default => 'med',
                    }) ?>"><?= e(request_status_label($request['status'])) ?></span></td>
                    <td>
                      <details class="req-actions">
                        <summary aria-label="Manage request <?= e(service_request_ref((int) $request['id'])) ?>">Manage</summary>
                        <form class="admin-request-form" method="post" action="<?= url('/admin/services/') ?>">
                          <?= csrf_field() ?>
                          <input type="hidden" name="request_id" value="<?= (int) $request['id'] ?>">
                          <div class="field">
                            <label for="staff-<?= (int) $request['id'] ?>">Assigned staff</label>
                            <input id="staff-<?= (int) $request['id'] ?>" name="assigned_staff" type="text" maxlength="160" value="<?= e($request['assigned_staff'] ?? '') ?>" placeholder="Staff member">
                          </div>
                          <div class="field">
                            <label for="start-<?= (int) $request['id'] ?>">Start date</label>
                            <input id="start-<?= (int) $request['id'] ?>" name="start_date" type="date" value="<?= e($request['activation_date'] ? substr((string) $request['activation_date'], 0, 10) : '') ?>">
                          </div>
                          <div class="field" style="grid-column: span 2">
                            <label for="notes-<?= (int) $request['id'] ?>">Internal notes</label>
                            <textarea id="notes-<?= (int) $request['id'] ?>" name="admin_notes" rows="2" maxlength="2000" placeholder="Internal note visible to the client's service workspace"><?= e($request['admin_notes'] ?? '') ?></textarea>
                          </div>
                          <?php if (!empty($request['request_description'])): ?><p class="admin-note" style="grid-column: 1 / -1">Client note: <?= e($request['request_description']) ?></p><?php endif; ?>
                          <div class="form-actions">
                            <?php if ($request['status'] !== 'under_review' && $request['status'] !== 'approved'): ?><button class="btn review" type="submit" name="action" value="review">Mark under review</button><?php endif; ?>
                            <?php if ($request['status'] !== 'approved'): ?><button class="btn approve" type="submit" name="action" value="approve">Approve &amp; activate</button><?php endif; ?>
                            <?php if ($request['status'] !== 'rejected'): ?><button class="btn reject" type="submit" name="action" value="reject">Reject</button><?php endif; ?>
                            <button class="btn ghost" type="submit" name="action" value="update">Save assignment details</button>
                          </div>
                        </form>
                      </details>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="admin-note">No service requests match these filters. Client selections made while signed in appear here for review.</p>
        <?php endif; ?>
      </section>

      <section id="assignments" aria-labelledby="assignmentsHeading" style="margin-top: 24px">
        <div class="card-head" style="margin-bottom: 10px">
          <div><h2 id="assignmentsHeading">Client service assignments</h2><p>Activate or deactivate services, assign staff, set start dates and track progress.</p></div>
        </div>
        <?php if ($assignments): ?>
          <div class="table-wrap">
            <table class="admin-table">
              <thead>
                <tr><th>Client</th><th>Company</th><th>Service</th><th>Status</th><th>Staff / start</th><th>Progress</th><th>Actions</th></tr>
              </thead>
              <tbody>
                <?php foreach ($assignments as $assignment): ?>
                  <tr>
                    <td><b><?= e($assignment['client_name']) ?></b><span class="meta"><?= e($assignment['client_email']) ?></span></td>
                    <td><?= e($assignment['company_name']) ?></td>
                    <td><b><?= e($assignment['service_name']) ?></b><?php if (!empty($assignment['admin_notes'])): ?><span class="meta"><?= e(mb_substr((string) $assignment['admin_notes'], 0, 90)) ?></span><?php endif; ?></td>
                    <td><span class="status-pill <?= e(service_status_tone($assignment['status'])) ?>"><?= e(service_status_label($assignment['status'])) ?></span></td>
                    <td><?= e($assignment['assigned_staff'] ?: '—') ?><span class="meta"><?= e(format_date($assignment['activation_date'] ?? null)) ?></span></td>
                    <td>
                      <div class="progress-track" style="min-width: 90px" role="img" aria-label="<?= (int) $assignment['progress'] ?> percent complete"><span style="width: <?= (int) $assignment['progress'] ?>%"></span></div>
                      <span class="meta"><?= (int) $assignment['progress'] ?>%</span>
                    </td>
                    <td>
                      <details class="req-actions">
                        <summary aria-label="Manage assignment">Manage</summary>
                        <form class="admin-request-form" method="post" action="<?= url('/admin/services/') ?>">
                          <?= csrf_field() ?>
                          <input type="hidden" name="assignment_id" value="<?= (int) $assignment['id'] ?>">
                          <div class="field">
                            <label for="astaff-<?= (int) $assignment['id'] ?>">Assigned staff</label>
                            <input id="astaff-<?= (int) $assignment['id'] ?>" name="assigned_staff" type="text" maxlength="160" value="<?= e($assignment['assigned_staff'] ?? '') ?>">
                          </div>
                          <div class="field">
                            <label for="astart-<?= (int) $assignment['id'] ?>">Start date</label>
                            <input id="astart-<?= (int) $assignment['id'] ?>" name="start_date" type="date" value="<?= e($assignment['activation_date'] ? substr((string) $assignment['activation_date'], 0, 10) : '') ?>">
                          </div>
                          <div class="field">
                            <label for="aprogress-<?= (int) $assignment['id'] ?>">Progress %</label>
                            <input id="aprogress-<?= (int) $assignment['id'] ?>" name="progress" type="number" min="0" max="100" value="<?= (int) $assignment['progress'] ?>">
                          </div>
                          <div class="field">
                            <label for="anotes-<?= (int) $assignment['id'] ?>">Internal notes</label>
                            <input id="anotes-<?= (int) $assignment['id'] ?>" name="admin_notes" type="text" maxlength="2000" value="<?= e($assignment['admin_notes'] ?? '') ?>">
                          </div>
                          <div class="form-actions">
                            <button class="btn ghost" type="submit" name="action" value="update">Save details</button>
                            <?php if ($assignment['status'] !== 'active'): ?>
                              <button class="btn approve" type="submit" name="action" value="activate">Activate service</button>
                            <?php else: ?>
                              <button class="btn reject" type="submit" name="action" value="deactivate">Deactivate service</button>
                            <?php endif; ?>
                          </div>
                        </form>
                      </details>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <p class="admin-note">No client service assignments exist yet.</p>
        <?php endif; ?>
      </section>

      <section class="admin-split" style="margin-top: 24px">
        <section class="card">
          <div class="card-head"><div><h2>Service operations</h2><p>Documents, communication and service tasks.</p></div></div>
          <div class="quick">
            <a href="<?= url('/admin/dashboard/') ?>#"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-folder"/></svg></span>Document management<span>&rarr;</span></a>
            <a href="<?= url('/admin/dashboard/') ?>#"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-msg"/></svg></span>Client communication<span>&rarr;</span></a>
            <a href="<?= url('/admin/dashboard/') ?>#"><span class="qi"><svg viewBox="0 0 24 24"><use href="#i-check"/></svg></span>Service tasks<span>&rarr;</span></a>
          </div>
          <p class="admin-note" style="margin-top: 12px">Assignment notes entered above appear in the client's dedicated service workspace. Documents, messages and tasks stay within their existing modules and are scoped to the selected company.</p>
        </section>

        <section class="admin-panel">
          <h2 class="panel-title">Approval rules</h2>
          <p class="admin-note"><strong>Initial service:</strong> the service chosen at registration is reserved and activated automatically after email verification.</p>
          <p class="admin-note" style="margin-top: 8px"><strong>Additional services:</strong> requests stay pending until an administrator approves them. Approval activates the assignment; rejection records the decision without activating the service.</p>
          <p class="admin-note" style="margin-top: 8px"><strong>Isolation:</strong> each account belongs to one company, and client workspaces only expose that company's assignments, notes and records.</p>
        </section>
      </section>
    </main>
  </div>
</body>
</html>
