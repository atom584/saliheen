<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <title>Complete Registration | Saliheen &amp; Associates</title>
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
      <h1>Your email is verified with <?= e($provider) ?>.</h1>
      <p>Complete your company details to finish setting up your client workspace. Your <?= e($provider) ?> account has already confirmed your business email.</p>
      <ul class="auth-points">
        <li>One company per client account.</li>
        <li>Your selected service is attached to your company.</li>
        <li>Additional services require firm approval.</li>
      </ul>
      <p class="auth-legal">Client information is confidential. Access is limited to the users your company authorises. Saliheen &amp; Associates advises on Bangladesh law only.</p>
    </aside>

    <section class="auth-main">
      <div class="auth-top">
        <a href="<?= url('/') ?>">&larr; Public website</a>
        <a class="swap" href="<?= url('/login') ?>">Client sign in &rarr;</a>
      </div>

      <div class="auth-card wide">
        <p class="eyebrow">Almost there</p>
        <h2>Complete your company profile</h2>
        <p class="lead">Signed in with <b><?= e($provider) ?></b> as <b><?= e($email) ?></b>. This verified email will be used for your account.</p>

        <?php if ($service): ?>
          <div class="service-summary">
            <p class="summary-label">You're registering for:</p>
            <h3><?= e($service['name']) ?></h3>
            <p class="summary-desc"><?= e($service['description']) ?></p>
            <ul>
              <?php foreach (array_slice($service['features'], 0, 4) as $feature): ?><li><?= e($feature) ?></li><?php endforeach; ?>
            </ul>
            <div class="summary-actions">
              <a href="<?= url('/explore-services') ?>">Change Service</a>
              <a href="<?= url('/explore-services') ?>">View service details</a>
            </div>
          </div>
        <?php endif; ?>

        <form class="auth-form" data-api="<?= url('/api/auth/complete-oauth.php') ?>" data-csrf="<?= e(csrf_token()) ?>" data-register-url="<?= url('/register') ?>" novalidate>
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <div class="form-grid">
            <div class="field span-2">
              <label for="fullName">Full name <em>*</em></label>
              <input id="fullName" name="full_name" type="text" autocomplete="name" value="<?= e($name) ?>" placeholder="Your full name" maxlength="160" required>
            </div>

            <div class="field span-2">
              <label for="businessEmail">Business email</label>
              <input id="businessEmail" name="email" type="email" value="<?= e($email) ?>" readonly aria-readonly="true">
              <span class="hint">Verified by <?= e($provider) ?>. Contact the firm if this is not your company email.</span>
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

          <button class="btn solid auth-submit" type="submit">Finish registration</button>

          <p class="form-status" tabindex="-1" role="status"></p>
          <noscript><p class="form-status show error">JavaScript is required to submit this secure form. Please enable JavaScript or contact info@nsaliheen.com.</p></noscript>
        </form>

        <div class="auth-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <span><b>One account, one company.</b> Your <?= e($provider) ?> sign-in confirms your email address. The selected service is attached to your company and activated immediately; additional services require firm approval.</span>
        </div>
      </div>

      <p class="auth-foot">Registration does not create a lawyer&ndash;client relationship. Questions? Email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a>.</p>
    </section>
  </main>
</body>
</html>
