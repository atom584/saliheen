<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <title>Client Sign In | Saliheen &amp; Associates</title>
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
      <p>Sign in to follow your company's legal matters, compliance deadlines, board records and documents shared by your legal team.</p>
      <ul class="auth-points">
        <li>Compliance calendar, board papers and legal updates in one place.</li>
        <li>Documents and reports shared securely with your company.</li>
        <li>Direct communication with your assigned legal team.</li>
      </ul>
      <p class="auth-legal">Client information is confidential. Each account is linked to a single company, and access is limited to the users your company authorises. Saliheen &amp; Associates advises on Bangladesh law only.</p>
    </aside>

    <section class="auth-main">
      <div class="auth-top">
        <a href="<?= url('/') ?>">&larr; Public website</a>
        <a class="swap" href="<?= e($registerUrl) ?>">New client? Register &rarr;</a>
      </div>

      <div class="auth-card">
        <span class="secure-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>Client access</span>
        <p class="eyebrow">Client portal</p>
        <h2>Sign in to your workspace</h2>
        <p class="lead">Access your company's legal matters, compliance calendar, documents and reports.</p>

        <?= $flashHtml ?>

        <?php if ($selection): ?>
          <div class="service-summary">
            <p class="summary-label">Selected service</p>
            <h3><?= e($selection['name']) ?></h3>
            <p class="summary-desc">This selection is kept safe while you sign in. Existing companies submit it as an additional-service request for firm approval.</p>
            <div class="summary-actions"><a href="<?= url('/explore-services') ?>">Change Service</a></div>
          </div>
        <?php endif; ?>

        <div class="sso" style="margin-top: 18px">
          <?php if ($googleReady): ?>
            <a href="<?= url('/api/oauth/google/start.php') . $suffix ?>"><span class="g">G</span>Continue with Google</a>
          <?php else: ?>
            <button type="button" disabled><span class="g">G</span>Continue with Google</button>
          <?php endif; ?>
          <?php if ($appleReady): ?>
            <a href="<?= url('/api/oauth/apple/start.php') . $suffix ?>"><svg class="apple" viewBox="0 0 24 24" aria-hidden="true"><path d="M16.4 12.7c0-2.1 1.7-3.1 1.8-3.2-1-1.4-2.5-1.6-3-1.6-1.3-.1-2.5.7-3.1.7-.6 0-1.6-.7-2.7-.7-1.4 0-2.7.8-3.4 2.1-1.5 2.5-.4 6.2 1 8.2.7 1 1.5 2.1 2.6 2 1-.1 1.4-.7 2.7-.7s1.6.7 2.7.7c1.1 0 1.8-1 2.5-2 .8-1.1 1.1-2.2 1.1-2.3-.1 0-2.2-.9-2.2-3.2ZM14.4 6.6c.6-.7 1-1.6.9-2.6-.9 0-1.9.6-2.5 1.3-.5.6-1 1.6-.9 2.5 1 .1 2-.5 2.5-1.2Z"/></svg>Continue with Apple</a>
          <?php else: ?>
            <button type="button" disabled><svg class="apple" viewBox="0 0 24 24" aria-hidden="true"><path d="M16.4 12.7c0-2.1 1.7-3.1 1.8-3.2-1-1.4-2.5-1.6-3-1.6-1.3-.1-2.5.7-3.1.7-.6 0-1.6-.7-2.7-.7-1.4 0-2.7.8-3.4 2.1-1.5 2.5-.4 6.2 1 8.2.7 1 1.5 2.1 2.6 2 1-.1 1.4-.7 2.7-.7s1.6.7 2.7.7c1.1 0 1.8-1 2.5-2 .8-1.1 1.1-2.2 1.1-2.3-.1 0-2.2-.9-2.2-3.2ZM14.4 6.6c.6-.7 1-1.6.9-2.6-.9 0-1.9.6-2.5 1.3-.5.6-1 1.6-.9 2.5 1 .1 2-.5 2.5-1.2Z"/></svg>Continue with Apple</button>
          <?php endif; ?>
        </div>
        <?php if (!$googleReady || !$appleReady): ?>
          <p class="provider-note">Provider sign-in is shown once Google or Apple credentials are configured for this deployment. Email sign-in is fully available.</p>
        <?php endif; ?>

        <div class="divider">or sign in with email</div>

        <form class="auth-form" data-api="<?= url('/api/auth/login.php') ?>" data-csrf="<?= e(csrf_token()) ?>" novalidate>
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="scope" value="client">
          <?php if ($selectionToken !== ''): ?><input type="hidden" name="selection" value="<?= e($selectionToken) ?>"><?php endif; ?>
          <?php if ($next !== ''): ?><input type="hidden" name="next" value="<?= e($next) ?>"><?php endif; ?>

          <div class="field">
            <label for="clientEmail">Business email <em>*</em></label>
            <input id="clientEmail" name="email" type="email" autocomplete="username" placeholder="name@company.com" maxlength="190" required>
          </div>

          <div class="field">
            <label for="clientPassword">Password <em>*</em></label>
            <div class="password-field">
              <input id="clientPassword" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" maxlength="72" required data-password>
              <button class="reveal" type="button" data-target="clientPassword" aria-label="Show password" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <div class="auth-row">
            <label class="check"><input type="checkbox" name="remember" /> Remember me on this device</label>
            <a href="<?= url('/forgot-password') ?>">Forgot password?</a>
          </div>

          <button class="btn solid auth-submit" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
            Sign in
          </button>

          <p class="form-status" tabindex="-1" role="status"></p>
          <noscript><p class="form-status show error">JavaScript is required to submit this secure form. Please enable JavaScript or contact info@nsaliheen.com.</p></noscript>
        </form>

        <div class="auth-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <span><b>One account, one company.</b> Portal access is limited to the users your company authorises. Need a verification email? <a href="<?= url('/verify') ?>">Resend verification</a>.</span>
        </div>
      </div>

      <p class="auth-foot">Sessions expire automatically. Registration does not create a lawyer&ndash;client relationship. Questions? Email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a>.</p>
    </section>
  </main>
</body>
</html>
