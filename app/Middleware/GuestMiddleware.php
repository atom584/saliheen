<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Services\AuthService;

final class GuestMiddleware
{
    public static function redirectIfSignedIn(): void
    {
        $user = AuthService::currentUser();
        if ($user !== null) {
            redirect($user['role'] === 'admin' ? '/admin/dashboard/' : '/client/dashboard/');
        }
    }
}
