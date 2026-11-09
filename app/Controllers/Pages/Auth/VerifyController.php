<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;
use App\Services\AuthService;

final class VerifyController
{
    public static function handle(): void
    {
        $email = isset($_GET['email']) && is_string($_GET['email']) ? strtolower(trim($_GET['email'])) : '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = '';
        }
        $token = isset($_GET['token']) && is_string($_GET['token']) ? trim($_GET['token']) : '';
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            $token = '';
        }
        $sent = ($_GET['sent'] ?? '') === '1';
        $user = AuthService::currentUser();

        View::render('auth/verify', compact('email', 'token', 'sent', 'user'));
    }
}
