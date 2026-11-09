<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;

final class ResetPasswordController
{
    public static function handle(): void
    {
        $token = isset($_GET['token']) && is_string($_GET['token']) ? trim($_GET['token']) : '';
        $valid = (bool) preg_match('/^[a-f0-9]{64}$/', $token);

        View::render('auth/reset-password', compact('token', 'valid'));
    }
}
