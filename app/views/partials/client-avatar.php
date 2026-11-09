<?php
declare(strict_types=1);

$avatarUrl = isset($avatarUrl) && is_string($avatarUrl) ? $avatarUrl : '';
$avatarInitials = trim((string) ($initials ?? '')) !== '' ? (string) $initials : 'SA';
$avatarName = (string) ($user['full_name'] ?? 'Account');
$avatarEmail = (string) ($user['email'] ?? '');
?>
<div class="avatar-menu" data-avatar-menu data-avatar-api="<?= e(url('/api/account/avatar.php')) ?>" data-avatar-name="<?= e($avatarName) ?>">
  <button type="button" class="avatar avatar-trigger" data-avatar-trigger aria-haspopup="true" aria-expanded="false" aria-controls="avatarPop" aria-label="Profile photo" title="Profile photo">
    <img alt="" data-avatar-img<?= $avatarUrl !== '' ? ' src="' . e($avatarUrl) . '"' : ' hidden' ?>>
    <span data-avatar-initials<?= $avatarUrl !== '' ? ' hidden' : '' ?>><?= e($avatarInitials) ?></span>
  </button>
  <div class="avatar-pop" id="avatarPop" data-avatar-pop hidden>
    <p class="avatar-pop-title">Profile photo</p>
    <div class="avatar-pop-head">
      <span class="avatar avatar-preview">
        <img alt="" data-avatar-img<?= $avatarUrl !== '' ? ' src="' . e($avatarUrl) . '"' : ' hidden' ?>>
        <span data-avatar-initials<?= $avatarUrl !== '' ? ' hidden' : '' ?>><?= e($avatarInitials) ?></span>
      </span>
      <span class="avatar-who">
        <strong><?= e($avatarName) ?></strong>
        <?php if ($avatarEmail !== ''): ?><small><?= e($avatarEmail) ?></small><?php endif; ?>
      </span>
    </div>
    <div class="avatar-actions">
      <button type="button" class="avatar-action" data-avatar-upload>
        <svg viewBox="0 0 24 24"><use href="#i-upload"/></svg>Upload photo
      </button>
      <button type="button" class="avatar-action" data-avatar-remove <?= $avatarUrl === '' ? 'hidden' : '' ?>>
        <svg viewBox="0 0 24 24"><use href="#i-close"/></svg><span data-avatar-remove-label>Remove</span>
      </button>
    </div>
    <p class="avatar-hint">JPG, PNG or WebP &middot; up to 2&nbsp;MB</p>
    <p class="avatar-status" data-avatar-status role="status" aria-live="polite"></p>
    <input type="file" accept="image/jpeg,image/png,image/webp" data-avatar-input hidden>
  </div>
</div>
