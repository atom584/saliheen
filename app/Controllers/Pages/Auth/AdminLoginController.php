<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;
use App\Middleware\GuestMiddleware;

final class AdminLoginController
{
    public static function handle(): void
    {
        GuestMiddleware::redirectIfSignedIn();
        View::render('auth/admin-login');
    }
}
