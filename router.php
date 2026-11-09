<?php
declare(strict_types=1);

/**
 * Router for PHP's built-in development server only.
 * Usage: php -S 127.0.0.1:8080 router.php
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$root = __DIR__;

if (preg_match('#^/(app|db|routes|setup)(/|$)#', $path)) {
    http_response_code(403);
    echo 'Forbidden';
    return true;
}

$file = realpath($root . $path);
if ($file !== false && is_file($file) && str_starts_with($file, realpath($root))) {
    return false;
}

require $root . '/index.php';
return true;
