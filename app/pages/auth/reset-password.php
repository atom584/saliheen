<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <title>Reset Password | Saliheen &amp; Associates</title>
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
      <h1>Choose a strong new password.</h1>
      <p>Your new password is stored using one-way hashing. Existing sessions on other devices are not affected by this reset.</p>
      <ul class="auth-points">
        <li>Use at least 8 characters.</li>
        <li>Include at least one letter and one number.</li>
        <li>Do not reuse a password from another service.</li>
      </ul>
      <p class="auth-legal">If you did not request this reset, do not use the link. Contact info@nsaliheen.com if you are concerned about your account.</p>
    </aside>

    <section class="auth-main">
      <div class="auth-top">
        <a href="<?= url('/') ?>">&larr; Public website</a>
        <a class="swap" href="<?= url('/login/') ?>">Client sign in &rarr;</a>
      </div>

      <div class="auth-card">
        <span class="secure-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>Account recovery</span>
        <p class="eyebrow">Password reset</p>
        <h2>Set a new password</h2>

        <?php if (!$valid): ?>
          <p class="lead">This password reset link is incomplete or invalid. Request a new link to continue.</p>
          <p style="margin-top: 18px"><a class="btn solid auth-submit" href="<?= url('/forgot-password/') ?>">Request a new reset link</a></p>
        <?php else: ?>
          <p class="lead">Enter a new password for your client account. You will be returned to the sign-in page when it is updated.</p>

          <form class="auth-form" data-api="<?= url('/api/auth/reset-password.php') ?>" data-csrf="<?= e(csrf_token()) ?>" novalidate>
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="token" value="<?= e($token) ?>">

            <div class="field">
              <label for="resetPassword">New password <em>*</em></label>
              <div class="password-field">
                <input id="resetPassword" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="72" placeholder="8+ characters, letter and number" required data-password>
                <button class="reveal" type="button" data-target="resetPassword" aria-label="Show password" aria-pressed="false">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>

            <div class="field">
              <label for="resetPasswordConfirm">Confirm new password <em>*</em></label>
              <div class="password-field">
                <input id="resetPasswordConfirm" name="password_confirm" type="password" autocomplete="new-password" minlength="8" maxlength="72" placeholder="Repeat your password" required data-confirm>
                <button class="reveal" type="button" data-target="resetPasswordConfirm" aria-label="Show password" aria-pressed="false">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>

            <button class="btn solid auth-submit" type="submit">Update password</button>
            <p class="form-status" tabindex="-1" role="status"></p>
          </form>
        <?php endif; ?>

        <div class="auth-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <span>Reset links are single-use and expire after 60 minutes. <a href="<?= url('/forgot-password/') ?>">Request another link</a>.</span>
        </div>
      </div>

      <p class="auth-foot">Saliheen &amp; Associates will never ask you for your password by email or phone. Questions? Email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a>.</p>
    </section>
  </main>
</body>
</html>
