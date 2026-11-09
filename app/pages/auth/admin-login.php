<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <title>Admin Sign In | Saliheen &amp; Associates</title>
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
      <h1>Secure access to your governance workspace.</h1>
      <p>Sign in to manage clients, legal matters, compliance obligations and confidential records for Saliheen &amp; Associates.</p>
      <ul class="auth-points">
        <li>Role-based access for partners, lawyers, compliance and accounts staff.</li>
        <li>Company-level isolation for every client record and document.</li>
        <li>Login activity and audit history for administrative actions.</li>
      </ul>
      <p class="auth-legal">Authorised personnel only. Access to client information is confidential and monitored. Saliheen &amp; Associates advises on Bangladesh law and works with local counsel in other jurisdictions.</p>
    </aside>

    <section class="auth-main">
      <div class="auth-top">
        <a href="<?= url('/') ?>">&larr; Public website</a>
        <a class="swap" href="<?= url('/register') ?>">Client registration &rarr;</a>
      </div>

      <div class="auth-card">
        <span class="secure-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>Secure staff access</span>
        <p class="eyebrow">Saliheen &amp; Associates &middot; Administration</p>
        <h2>Admin sign in</h2>
        <p class="lead">Enter your firm email address to access the legal governance portal.</p>

        <?= render_flash_alerts() ?>

        <form class="auth-form" data-api="<?= url('/api/auth/login.php') ?>" data-csrf="<?= e(csrf_token()) ?>" novalidate>
          <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="scope" value="admin">

          <div class="field">
            <label for="adminEmail">Email address <em>*</em></label>
            <input id="adminEmail" name="email" type="email" autocomplete="username" placeholder="name@nsaliheen.com" maxlength="190" required>
          </div>

          <div class="field">
            <label for="adminPassword">Password <em>*</em></label>
            <div class="password-field">
              <input id="adminPassword" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" maxlength="72" required data-password>
              <button class="reveal" type="button" data-target="adminPassword" aria-label="Show password" aria-pressed="false">
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
            Secure sign in
          </button>

          <p class="form-status" tabindex="-1" role="status"></p>
          <noscript><p class="form-status show error">JavaScript is required to submit this secure form. Please enable JavaScript or contact info@nsaliheen.com.</p></noscript>
        </form>

        <div class="auth-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <span><b>Admin accounts are created by the Super Admin.</b> Public admin registration is not available. Contact the firm administrator if your access has not been provisioned.</span>
        </div>
      </div>

      <p class="auth-foot">Sessions expire automatically. Never share your portal credentials. For access issues, email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a>.</p>
    </section>
  </main>
</body>
</html>
