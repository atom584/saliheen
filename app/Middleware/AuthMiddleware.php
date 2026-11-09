<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Services\AuthService;

final class AuthMiddleware
{
    public static function requireLogin(bool $json = false): array
    {
        $user = AuthService::currentUser();
        if (!$user) {
            if ($json || wants_json()) {
                json_response(['ok' => false, 'error' => 'Please sign in to continue.'], 401);
            }
            flash('error', 'Please sign in to continue.');
            redirect('/login');
        }
        $_SESSION['last_activity'] = time();
        return $user;
    }

    public static function requireClient(bool $json = false): array
    {
        $user = self::requireLogin($json);
        if ($user['role'] !== 'client') {
            if ($json || wants_json()) {
                json_response(['ok' => false, 'error' => 'Client access only.'], 403);
            }
            http_response_code(403);
            exit('Client access only.');
        }
        if (empty($user['company_id'])) {
            if ($json || wants_json()) {
                json_response(['ok' => false, 'error' => 'Company profile incomplete.', 'code' => 'profile_incomplete'], 409);
            }
            redirect('/register/complete');
        }
        return $user;
    }

    public static function requireAdmin(bool $json = false): array
    {
        $user = self::requireLogin($json);
        if ($user['role'] !== 'admin') {
            if ($json || wants_json()) {
                json_response(['ok' => false, 'error' => 'Administrator access only.'], 403);
            }
            http_response_code(403);
            exit('Administrator access only.');
        }
        return $user;
    }
}
