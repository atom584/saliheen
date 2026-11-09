<?php
declare(strict_types=1);

namespace App\Middleware;

final class CsrfMiddleware
{
    public static function require(?string $token = null): void
    {
        $token ??= $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $expected = $_SESSION['_csrf'] ?? '';
        if (!is_string($token) || $expected === '' || !hash_equals($expected, $token)) {
            if (wants_json()) {
                json_response(['ok' => false, 'error' => 'Your session expired. Refresh the page and try again.'], 419);
            }
            http_response_code(419);
            exit('Your session expired. Refresh the page and try again.');
        }
    }
}
