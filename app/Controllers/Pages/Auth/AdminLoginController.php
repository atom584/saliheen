<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;

final class AdminLoginController
{
    public static function handle(): void
    {
        View::render('auth/admin-login');
    }
}
