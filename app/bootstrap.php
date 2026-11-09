<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap_core.php';

$secureCookie = (bool) config('security.secure_cookies', true) && is_https();

session_name((string) config('session_name', 'SA_SESSION'));
session_set_cookie_params([
    'lifetime' => 0,
    'path' => base_path() === '' ? '/' : base_path(),
    'domain' => '',
    'secure' => $secureCookie,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    App\Database\Database::connection();
} catch (PDOException $error) {
    error_log('Database connection failed: ' . $error->getMessage());
    if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/') || wants_json()) {
        http_response_code(503);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'error' => 'The portal database is temporarily unavailable.']);
        exit;
    }
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><meta charset="utf-8"><title>Portal unavailable</title>'
        . '<div style="max-width:640px;margin:12vh auto;font:16px/1.6 system-ui,sans-serif;color:#292326;padding:0 24px">'
        . '<h1 style="font:400 32px Georgia,serif;color:#702632">Portal temporarily unavailable</h1>'
        . '<p>The portal database could not be reached. Please try again shortly or email '
        . '<a href="mailto:info@nsaliheen.com" style="color:#702632">info@nsaliheen.com</a>.</p></div>';
    exit;
}

if (!empty($_SESSION['user_id'])) {
    $lifetime = (int) config('security.session_lifetime', 7200);
    $last = (int) ($_SESSION['last_activity'] ?? 0);
    if ($last > 0 && time() - $last > $lifetime) {
        $_SESSION = [];
        session_regenerate_id(true);
        flash('error', 'Your session expired. Please sign in again.');
    } else {
        $_SESSION['last_activity'] = time();
    }
}
