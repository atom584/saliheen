<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#702632">
  <title><?= $token !== '' ? 'Verifying your email' : 'Verify your email' ?> | Saliheen &amp; Associates</title>
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
      <h1>One secure account for your company.</h1>
      <p>Verifying your business email protects your company's legal records and confirms that you are authorised to manage its portal access.</p>
      <ul class="auth-points">
        <li>Verification links expire after 24 hours.</li>
        <li>Your selected legal service activates only after verification.</li>
        <li>Additional services are reviewed by the firm before activation.</li>
      </ul>
      <p class="auth-legal">Client information is confidential. Each account is linked to a single company. Saliheen &amp; Associates advises on Bangladesh law only.</p>
    </aside>

    <section class="auth-main">
      <div class="auth-top">
        <a href="<?= url('/') ?>">&larr; Public website</a>
        <a class="swap" href="<?= url('/login/') ?>">Client sign in &rarr;</a>
      </div>

      <div class="auth-card">
        <?php if ($token !== '' && $user === null): ?>
          <span class="secure-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.4-3 7.9-7 9-4-1.1-7-4.6-7-9V6l7-3Z"/><path d="M9 12l2 2 4-4"/></svg>Email verification</span>
          <p class="eyebrow">Secure verification</p>
          <h2>Verifying your business email</h2>
          <p class="lead">Please wait while we confirm your address and activate your selected legal service. You will be taken to your dashboard.</p>

          <form class="auth-form" data-api="<?= url('/api/auth/verify.php') ?>" data-csrf="<?= e(csrf_token()) ?>" data-autoverify="1" novalidate>
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="token" value="<?= e($token) ?>">
            <button class="btn solid auth-submit" type="submit">Continue verification</button>
            <p class="form-status show" tabindex="-1" role="status">Checking your verification link&hellip;</p>
          </form>
        <?php elseif ($user !== null): ?>
          <p class="eyebrow">Already signed in</p>
          <h2>Your email is verified</h2>
          <p class="lead">You are already signed in to your workspace as <b><?= e($user['email']) ?></b>.</p>
          <p style="margin-top: 18px"><a class="btn solid auth-submit" href="<?= url($user['role'] === 'admin' ? '/admin/dashboard/' : '/client/dashboard/') ?>">Open dashboard</a></p>
        <?php else: ?>
          <span class="secure-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16v11H8l-4 4V5Z"/><path d="M8 9h8M8 12h5"/></svg>Email verification</span>
          <p class="eyebrow">Check your inbox</p>
          <h2>Verify your business email</h2>
          <p class="lead">
            <?php if ($email !== ''): ?>
              We sent a verification link to <b><?= e($email) ?></b>. Open it to verify your address and activate your selected legal service.
            <?php else: ?>
              Open the verification link in the email we sent you. It expires after 24 hours. Need a new link? Enter your business email below.
            <?php endif; ?>
          </p>

          <?php if ($sent): ?>
            <div class="auth-alert show ok" role="status">Your account has been created. Check your inbox (and spam folder) for the verification email.</div>
          <?php endif; ?>

          <div class="divider">resend verification email</div>

          <form class="auth-form" data-api="<?= url('/api/auth/resend-verification.php') ?>" data-csrf="<?= e(csrf_token()) ?>" novalidate>
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <div class="field">
              <label for="verifyEmail">Business email <em>*</em></label>
              <input id="verifyEmail" name="email" type="email" autocomplete="email" value="<?= e($email) ?>" placeholder="name@company.com" maxlength="190" required>
            </div>
            <button class="btn solid auth-submit" type="submit">Resend verification link</button>
            <p class="form-status" tabindex="-1" role="status"></p>
          </form>

          <div class="auth-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
            <span><b>Already verified?</b> <a href="<?= url('/login/') ?>">Sign in</a>. If the link has expired, resend it above.</span>
          </div>
        <?php endif; ?>
      </div>

      <p class="auth-foot">Didn't receive the email? Check your spam folder, then resend. Still stuck? Email <a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a>.</p>
    </section>
  </main>
</body>
</html>
