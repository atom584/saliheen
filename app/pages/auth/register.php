<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <title>Client Registration | Saliheen &amp; Associates</title>
  <link rel="icon" type="image/svg+xml" href="<?= url('/images/saliheen-favicon.svg') ?>">
  <link rel="icon" type="image/png" sizes="64x64" href="<?= url('/images/saliheen-favicon.png') ?>">
  <link rel="stylesheet" href="<?= url('/portal/portal.css') ?>">
  <link rel="stylesheet" href="<?= url('/portal/auth.css') ?>">
  <link rel="stylesheet" href="<?= url('/portal/services.css') ?>">
  <script src="<?= url('/portal/account.js') ?>" defer></script>
</head>
<body class="portal auth-page">
  <main class="auth">
    <aside class="auth-aside">
      <a class="auth-brand" href="<?= url('/') ?>" aria-label="Saliheen &amp; Associates home"><img src="<?= url('/images/saliheen-brand-light.svg') ?>" alt="Saliheen &amp; Associates" /></a>
      <h1>Your legal governance workspace.</h1>
      <p>Create a client account to follow legal matters, compliance deadlines, board records and documents shared with your company.</p>
      <ul class="auth-points">
        <li>One secure account for your company's governance records.</li>
        <li>Your selected legal service is attached to your company account.</li>
        <li>Direct communication with your assigned legal team.</li>
      </ul>
      <p class="auth-legal">Client information is confidential. Accounts are linked to a single company, and access is limited to the users your company authorises. Saliheen &amp; Associates advises on Bangladesh law only.</p>
    </aside>

    <section class="auth-main">
      <div class="auth-top">
        <a href="<?= url('/') ?>">&larr; Public website</a>
        <a class="swap" href="<?= e($loginUrl) ?>">Client sign in &rarr;</a>
      </div>

      <div class="auth-card wide">
        <p class="eyebrow">Client registration</p>
        <h2>Create your client account</h2>
        <p class="lead">For companies, directors, business owners and international clients. Each account manages one company. Already registered? <a href="<?= e($loginUrl) ?>">Sign in &rarr;</a></p>

        <?= $flashHtml ?>

        <?php if ($selected): ?>
          <div class="service-summary">
            <p class="summary-label">You're registering for:</p>
            <h3><?= e($selected['name']) ?></h3>
            <p class="summary-desc"><?= e($selected['description']) ?></p>
            <ul>
              <?php foreach (array_slice($selected['features'], 0, 4) as $feature): ?><li><?= e($feature) ?></li><?php endforeach; ?>
            </ul>
            <div class="summary-actions">
              <a href="<?= e($exploreUrl) ?>">Change Service</a>
              <a href="<?= e($exploreUrl) ?>">View service details</a>
            </div>
          </div>
        <?php else: ?>
          <div class="service-summary">
            <p class="summary-label">No service selected</p>
            <h3>Choose a legal service</h3>
            <p class="summary-desc">You can create your account now, or select a service first so it is attached to your company when your email is verified.</p>
            <div class="summary-actions"><a href="<?= e($exploreUrl) ?>">Explore Legal Services</a></div>
          </div>
        <?php endif; ?>

        <div class="sso" style="margin-top: 18px">
          <?php if ($googleReady): ?>
            <a href="<?= url('/api/oauth/google/start.php') ?>"><span class="g">G</span>Continue with Google</a>
          <?php else: ?>
            <button type="button" disabled><span class="g">G</span>Continue with Google</button>
          <?php endif; ?>
          <?php if ($appleReady): ?>
            <a href="<?= url('/api/oauth/apple/start.php') ?>"><svg class="apple" viewBox="0 0 24 24" aria-hidden="true"><path d="M16.4 12.7c0-2.1 1.7-3.1 1.8-3.2-1-1.4-2.5-1.6-3-1.6-1.3-.1-2.5.7-3.1.7-.6 0-1.6-.7-2.7-.7-1.4 0-2.7.8-3.4 2.1-1.5 2.5-.4 6.2 1 8.2.7 1 1.5 2.1 2.6 2 1-.1 1.4-.7 2.7-.7s1.6.7 2.7.7c1.1 0 1.8-1 2.5-2 .8-1.1 1.1-2.2 1.1-2.3-.1 0-2.2-.9-2.2-3.2ZM14.4 6.6c.6-.7 1-1.6.9-2.6-.9 0-1.9.6-2.5 1.3-.5.6-1 1.6-.9 2.5 1 .1 2-.5 2.5-1.2Z"/></svg>Continue with Apple</a>
          <?php else: ?>
            <button type="button" disabled><svg class="apple" viewBox="0 0 24 24" aria-hidden="true"><path d="M16.4 12.7c0-2.1 1.7-3.1 1.8-3.2-1-1.4-2.5-1.6-3-1.6-1.3-.1-2.5.7-3.1.7-.6 0-1.6-.7-2.7-.7-1.4 0-2.7.8-3.4 2.1-1.5 2.5-.4 6.2 1 8.2.7 1 1.5 2.1 2.6 2 1-.1 1.4-.7 2.7-.7s1.6.7 2.7.7c1.1 0 1.8-1 2.5-2 .8-1.1 1.1-2.2 1.1-2.3-.1 0-2.2-.9-2.2-3.2ZM14.4 6.6c.6-.7 1-1.6.9-2.6-.9 0-1.9.6-2.5 1.3-.5.6-1 1.6-.9 2.5 1 .1 2-.5 2.5-1.2Z"/></svg>Continue with Apple</button>
          <?php endif; ?>
        </div>
        <?php if (!$googleReady || !$appleReady): ?>
          <p class="provider-note">Provider sign-in is shown once Google or Apple credentials are configured for this deployment. Email registration is fully available.</p>
        <?php endif; ?>

        <div class="divider">or register with email</div>

        <form class="auth-form" data-api="<?= url('/api/auth/register.php') ?>" data-csrf="<?= e(csrf_token()) ?>" novalidate>
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <?php if ($selectionToken !== ''): ?><input type="hidden" name="selection" value="<?= e($selectionToken) ?>"><?php endif; ?>
          <div class="form-grid">
            <div class="field span-2">
              <label for="fullName">Full name <em>*</em></label>
              <input id="fullName" name="full_name" type="text" autocomplete="name" placeholder="Your full name" maxlength="160" required>
            </div>

            <div class="field span-2">
              <label for="businessEmail">Business email <em>*</em></label>
              <input id="businessEmail" name="email" type="email" autocomplete="email" placeholder="name@company.com" maxlength="190" required>
              <span class="hint">A verification link will be sent to this address.</span>
            </div>

            <div class="field">
              <label for="newPassword">Password <em>*</em></label>
              <div class="password-field">
                <input id="newPassword" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="72" placeholder="8+ characters, letter and number" required data-password>
                <button class="reveal" type="button" data-target="newPassword" aria-label="Show password" aria-pressed="false">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>

            <div class="field">
              <label for="confirmPassword">Confirm password <em>*</em></label>
              <div class="password-field">
                <input id="confirmPassword" name="password_confirm" type="password" autocomplete="new-password" minlength="8" maxlength="72" placeholder="Repeat your password" required data-confirm>
                <button class="reveal" type="button" data-target="confirmPassword" aria-label="Show password" aria-pressed="false">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>

            <div class="field span-2">
              <label for="company">Company name <em>*</em></label>
              <input id="company" name="company_name" type="text" autocomplete="organization" placeholder="Registered company name" maxlength="190" required>
            </div>

            <div class="field">
              <label for="designation">Designation <em>*</em></label>
              <input id="designation" name="designation" type="text" autocomplete="organization-title" placeholder="Director, Company Secretary…" maxlength="120" required>
            </div>

            <div class="field">
              <label for="country">Country <em>*</em></label>
              <select id="country" name="country" autocomplete="country-name" required>
                <option value="Bangladesh" selected>Bangladesh</option>
                <option>India</option>
                <option>Pakistan</option>
                <option>Sri Lanka</option>
                <option>Nepal</option>
                <option>Singapore</option>
                <option>Malaysia</option>
                <option>United Arab Emirates</option>
                <option>Saudi Arabia</option>
                <option>Qatar</option>
                <option>Kuwait</option>
                <option>Oman</option>
                <option>Bahrain</option>
                <option>United Kingdom</option>
                <option>United States</option>
                <option>Canada</option>
                <option>Australia</option>
                <option>Germany</option>
                <option>Netherlands</option>
                <option>Japan</option>
                <option>China</option>
                <option>Hong Kong</option>
                <option>Other</option>
              </select>
            </div>

            <div class="field">
              <label for="phone">Phone number <em>*</em></label>
              <input id="phone" name="phone" type="tel" autocomplete="tel" placeholder="+880 1XXX XXXXXX" maxlength="40" required>
            </div>

            <div class="field">
              <label for="whatsapp">WhatsApp number <span class="opt">(optional)</span></label>
              <input id="whatsapp" name="whatsapp" type="tel" autocomplete="tel" placeholder="+880 1XXX XXXXXX" maxlength="40">
            </div>

            <div class="field span-2">
              <label for="businessType">Business type <em>*</em></label>
              <select id="businessType" name="business_type" required>
                <option value="" selected disabled>Select your business type</option>
                <option>Private limited company</option>
                <option>Public limited company</option>
                <option>Partnership</option>
                <option>Sole proprietorship</option>
                <option>Branch or liaison office</option>
                <option>Non-profit organisation</option>
                <option>Other</option>
              </select>
            </div>

            <div class="field span-2">
              <label class="check"><input type="checkbox" name="terms" required> <span>I accept the <b>Terms of Service</b> and <b>Privacy Policy</b>, and confirm that I am authorised to register this company.</span></label>
            </div>
          </div>

          <button class="btn solid auth-submit" type="submit">Create client account</button>

          <p class="form-status" tabindex="-1" role="status"></p>
          <noscript><p class="form-status show error">JavaScript is required to submit this secure form. Please enable JavaScript or contact info@nsaliheen.com.</p></noscript>
        </form>

        <div class="auth-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <span><b>One account, one company.</b> Your account is linked to a single company. Your selected service activates after email verification, and additional services remain pending until the firm approves them.</span>
        </div>
      </div>

      <p class="auth-foot">Registration does not create a lawyer&ndash;client relationship. Portal information is general and does not replace professional legal advice. Questions? Email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a>.</p>
    </section>
  </main>
</body>
</html>
