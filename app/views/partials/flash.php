<?php foreach (pull_flash() as $message): ?>
  <div class="auth-alert show<?= ($message['type'] ?? '') === 'success' ? ' ok' : '' ?>" role="status"><?= e((string) ($message['message'] ?? '')) ?></div>
<?php endforeach; ?>
