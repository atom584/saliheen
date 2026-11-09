<?php
declare(strict_types=1);

namespace App\Controllers\Api\Account;

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Services\AvatarService;

final class AvatarController
{
    public static function handle(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            json_response(['ok' => false, 'error' => 'Method not allowed.'], 405);
        }

        $user = AuthMiddleware::requireClient(true);
        CsrfMiddleware::require();

        if (!RateLimitMiddleware::allow('avatar.change', (string) $user['id'], 30, 3600)) {
            json_response(['ok' => false, 'error' => 'Too many photo changes. Please wait a while and try again.'], 429);
        }

        $action = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : 'upload';

        if ($action === 'remove') {
            $result = AvatarService::remove((int) $user['id'], $user['avatar_path'] ?? null);
        } else {
            $file = $_FILES['avatar'] ?? [];
            $result = AvatarService::update(
                (int) $user['id'],
                is_array($file) ? $file : [],
                $user['avatar_path'] ?? null
            );
        }

        json_response($result, $result['ok'] ? 200 : 422);
    }
}
