<?php
declare(strict_types=1);

if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__);
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_PATH . '/Core/Autoloader.php';
App\Core\Autoloader::register();

App\Core\Config::load(APP_PATH . '/config');

require_once APP_PATH . '/Helpers/functions.php';
require_once APP_PATH . '/Helpers/layout.php';

date_default_timezone_set((string) config('timezone', 'Asia/Dhaka'));

if (config('env') === 'development') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL);
}

App\Middleware\SecurityHeaders::apply();

set_exception_handler(static function (Throwable $error): void {
    error_log('Unhandled portal error: ' . $error->getMessage() . ' in ' . $error->getFile() . ':' . $error->getLine());
    if (!headers_sent()) {
        http_response_code(500);
    }
    if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/') || wants_json()) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(['ok' => false, 'error' => 'Something went wrong. Please try again.'], JSON_UNESCAPED_SLASHES);
        exit;
    }
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    echo '<!doctype html><meta charset="utf-8"><title>Portal error</title>'
        . '<div style="max-width:640px;margin:12vh auto;font:16px/1.6 system-ui,sans-serif;color:#292326;padding:0 24px">'
        . '<h1 style="font:400 32px Georgia,serif;color:#702632">Something went wrong</h1>'
        . '<p>Please refresh the page and try again.</p></div>';
    exit;
});
