<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;

final class ForgotPasswordController
{
    public static function handle(): void
    {
        View::render('auth/forgot-password');
    }
}
