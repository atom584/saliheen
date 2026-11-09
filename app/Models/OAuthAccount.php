<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class OAuthAccount
{
    public static function link(int $userId, string $provider, string $providerUserId): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO oauth_accounts (user_id, provider, provider_user_id)
             VALUES (:user, :provider, :provider_user)
             ON DUPLICATE KEY UPDATE user_id = VALUES(user_id)'
        );
        $stmt->execute([':user' => $userId, ':provider' => $provider, ':provider_user' => $providerUserId]);
    }

    public static function find(string $provider, string $providerUserId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT u.*, c.name AS company_name
             FROM oauth_accounts oa
             JOIN users u ON u.id = oa.user_id
             LEFT JOIN companies c ON c.id = u.company_id
             WHERE oa.provider = :provider AND oa.provider_user_id = :provider_user
             LIMIT 1'
        );
        $stmt->execute([':provider' => $provider, ':provider_user' => $providerUserId]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
