  <svg aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden">
    <symbol id="i-scale" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M5 7h14"/><path d="M8 21h8"/><path d="M5 7l-3 6h6L5 7Z"/><path d="M19 7l-3 6h6l-3-6Z"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12.2l3 3 5.2-6"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.4-3 7.9-7 9-4-1.1-7-4.6-7-9V6l7-3Z"/><path d="M9 12l2 2 4-4"/></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></symbol>
    <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7"/><path d="M3 12h18"/></symbol>
    <symbol id="i-bank" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6"/><path d="M5 10v9M9 10v9M15 10v9M19 10v9"/><path d="M3 21h18"/></symbol>
    <symbol id="i-gavel" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h8"/><path d="M14.5 3.5l6 6-2.8 2.8-6-6 2.8-2.8Z"/><path d="M12 6l-6 6 3 3 6-6"/><path d="M9 15l-4 4"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V10M10 20V4M16 20v-7"/><path d="M2 20h20"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.4"/><path d="M5 20c0-3.6 3.1-6.5 7-6.5s7 2.9 7 6.5"/></symbol>
  </svg>

  <main>
    <section class="explore-hero">
      <div class="container">
        <div class="explore-crumbs"><a href="<?= url('/') ?>">Home</a><span>/</span><span>Explore Our Services</span></div>
        <p class="eyebrow">Client services</p>
        <h1>Explore Our Legal Services</h1>
        <p>Discover professional legal governance, corporate compliance and advisory services tailored to your business requirements.</p>
      </div>
    </section>

    <section class="explore-body">
      <div class="container">
        <?php if ($isClient): ?>
          <p class="selected-flash">You are signed in as <strong><?= e($user['full_name']) ?></strong><?= !empty($user['company_name']) ? ' · ' . e($user['company_name']) : '' ?>. Selecting a service here submits an additional service request for review.</p>
        <?php elseif ($user !== null): ?>
          <p class="selected-flash">Administrator accounts do not request client services. Manage client services from <a href="<?= url('/admin/services') ?>" style="color:#1d4f83;font-weight:700">Service Management</a>.</p>
        <?php endif; ?>

        <div class="explore-shell">
          <section class="selector-panel" aria-labelledby="selectHeading">
            <span class="step-label"><span>1</span>Choose a service</span>
            <h2 id="selectHeading">Select a Legal Service</h2>
            <p class="lead">Choose one of the eight service areas below. You can review its description and included features before continuing, and change your selection at any time.</p>

            <div class="select-field">
              <label for="serviceSelect">Legal service</label>
              <div class="select-wrap">
                <select class="service-select" id="serviceSelect" aria-describedby="selectHint">
                  <option value="">Select a Legal Service</option>
                  <?php foreach ($services as $service): ?>
                    <option value="<?= (int) $service['id'] ?>"<?= $activeId === (int) $service['id'] ? ' selected' : '' ?>><?= e($service['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <p class="service-select-hint" id="selectHint">Selecting a service does not create an account or activate the service.</p>
            </div>

            <div class="selector-points">
              <span><svg viewBox="0 0 24 24"><use href="#i-check"/></svg>Review the service description, features and intended scope.</span>
              <span><svg viewBox="0 0 24 24"><use href="#i-user"/></svg>Register or sign in to keep the selected service with your company.</span>
              <span><svg viewBox="0 0 24 24"><use href="#i-shield"/></svg>Additional contracted services are activated only after firm review.</span>
            </div>

            <p class="selector-note">Service descriptions are general information about the firm's services. They are not guarantees of outcome or a substitute for advice on your specific circumstances.</p>

            <p class="form-status auth-alert" id="exploreStatus" role="status" tabindex="-1"></p>
          </section>

          <section class="service-detail" id="serviceDetail" aria-live="polite" data-active="<?= $activeId ?>">
            <?php if ($selected): ?>
              <div class="service-detail-head">
                <div class="service-detail-icon"><svg viewBox="0 0 24 24"><use href="#<?= e($selected['icon']) ?>"/></svg></div>
                <div>
                  <p class="kicker">Selected service</p>
                  <h2><?= e($selected['name']) ?></h2>
                </div>
              </div>
              <p class="description"><?= e($selected['description']) ?></p>
              <div class="detail-divider"></div>
              <p class="detail-features-title">Included service features</p>
              <ul class="detail-features">
                <?php foreach ($selected['features'] as $feature): ?><li><?= e($feature) ?></li><?php endforeach; ?>
              </ul>
              <div class="detail-actions">
                <button class="btn-service" type="button" data-intent="<?= $isClient ? 'request' : 'register' ?>"<?= $user !== null && !$isClient ? ' disabled' : '' ?>>
                  <?= $isClient ? 'Request This Service' : 'Continue to Registration' ?>
                  <svg viewBox="0 0 24 24"><use href="#i-arrow"/></svg>
                </button>
                <a href="#" class="change-link" data-change>Change service</a>
              </div>
            <?php else: ?>
              <div class="detail-empty">
                <div>
                  <div class="empty-mark"><svg viewBox="0 0 24 24"><use href="#i-scale"/></svg></div>
                  <h3>Your service details will appear here</h3>
                  <p>Select a legal service from the list to see its description, included features and the next step.</p>
                </div>
              </div>
            <?php endif; ?>
          </section>
        </div>

        <p class="explore-disclaimer"><strong>Important:</strong> Service descriptions present the firm's professional service offerings. They are not guarantees of results and do not constitute unrestricted financial, accounting or investment advice. Each engagement is subject to the firm's review, conflict checks and written terms.</p>
      </div>
    </section>
  </main>
  <script type="application/json" id="serviceData"><?= json_encode(array_map(static fn (array $s): array => [
      'id' => (int) $s['id'],
      'slug' => $s['slug'],
      'name' => $s['name'],
      'description' => $s['description'],
      'features' => $s['features'],
      'icon' => $s['icon'],
  ], $services), $jsonFlags) ?></script>
  <div id="exploreConfig"
       data-api="<?= url('/api/services/select.php') ?>"
       data-login="<?= url('/login') ?>"
       data-dashboard="<?= url('/client/dashboard') ?>"
       data-csrf="<?= e(csrf_token()) ?>"
       data-mode="<?= $isClient ? 'client' : ($user !== null ? 'admin' : 'guest') ?>"
       hidden></div>
