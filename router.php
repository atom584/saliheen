<?php
declare(strict_types=1);

/**
 * Router for PHP's built-in development server only.
 * Usage: php -S 127.0.0.1:8080 router.php
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$root = __DIR__;

if (preg_match('#^/(app|db|setup)(/|$)#', $path)) {
    http_response_code(403);
    echo 'Forbidden';
    return true;
}

$file = realpath($root . $path);
if ($file !== false && is_file($file) && str_starts_with($file, realpath($root))) {
    return false;
}

if (preg_match('#^/client/services/([a-z0-9-]{1,80})/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    require $root . '/client/services/index.php';
    return true;
}

if ($path === '/client/services' || $path === '/client/services/') {
    header('Location: /client/dashboard/', true, 302);
    return true;
}

if (preg_match('#^/(about|services|team|contact)/?$#', $path, $matches)) {
    require $root . '/pages/' . $matches[1] . '.php';
    return true;
}

if (preg_match('#^/(admin|client)/dashboard/?$#', $path)) {
    $target = $root . $path;
    $target = rtrim($target, '/') . '/index.php';
    require $target;
    return true;
}

$candidate = rtrim($root . $path, '/');
foreach (['/index.php', '/index.html'] as $suffix) {
    if (is_file($candidate . $suffix)) {
        if (str_ends_with($suffix, '.php')) {
            require $candidate . $suffix;
            return true;
        }
        readfile($candidate . $suffix);
        return true;
    }
}

if ($path === '/') {
    require $root . '/index.php';
    return true;
}

http_response_code(404);
echo 'Not found';
return true;
