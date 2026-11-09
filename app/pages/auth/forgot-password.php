<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <title>Forgot Password | Saliheen &amp; Associates</title>
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
      <h1>Regain access to your workspace.</h1>
      <p>Password reset links are sent only to verified business email addresses and expire after 60 minutes.</p>
      <ul class="auth-points">
        <li>Reset links are single-use.</li>
        <li>Your account remains locked until the email is verified.</li>
        <li>Contact the firm if your company email has changed.</li>
      </ul>
      <p class="auth-legal">For security, we do not confirm whether an email address is registered. Saliheen &amp; Associates advises on Bangladesh law only.</p>
    </aside>

    <section class="auth-main">
      <div class="auth-top">
        <a href="<?= url('/') ?>">&larr; Public website</a>
        <a class="swap" href="<?= url('/register') ?>">New client? Register &rarr;</a>
      </div>

      <div class="auth-card">
        <span class="secure-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>Account recovery</span>
        <p class="eyebrow">Password reset</p>
        <h2>Forgot your password?</h2>
        <p class="lead">Enter the business email linked to your account. If a verified account exists, we will send a reset link.</p>

        <form class="auth-form" data-api="<?= url('/api/auth/forgot-password.php') ?>" data-csrf="<?= e(csrf_token()) ?>" novalidate>
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <div class="field">
            <label for="resetEmail">Business email <em>*</em></label>
            <input id="resetEmail" name="email" type="email" autocomplete="email" placeholder="name@company.com" maxlength="190" required>
          </div>
          <button class="btn solid auth-submit" type="submit">Send reset link</button>
          <p class="form-status" tabindex="-1" role="status"></p>
        </form>

        <div class="auth-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <span><b>Remembered your password?</b> <a href="<?= url('/login') ?>">Return to sign in</a>. Unverified account? <a href="<?= url('/verify') ?>">Resend verification</a>.</span>
        </div>
      </div>

      <p class="auth-foot">The reset link expires after 60 minutes. If it expires, request a new one. Questions? Email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a>.</p>
    </section>
  </main>
</body>
</html>
