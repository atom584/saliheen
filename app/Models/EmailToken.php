<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class EmailToken
{
    public static function create(int $userId, string $type, int $ttlSeconds): string
    {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', time() + $ttlSeconds);

        $stmt = Database::connection()->prepare(
            'UPDATE email_tokens SET used_at = NOW() WHERE user_id = :user AND type = :type AND used_at IS NULL'
        );
        $stmt->execute([':user' => $userId, ':type' => $type]);

        $stmt = Database::connection()->prepare(
            'INSERT INTO email_tokens (user_id, type, token_hash, expires_at) VALUES (:user, :type, :hash, :expires)'
        );
        $stmt->execute([':user' => $userId, ':type' => $type, ':hash' => $hash, ':expires' => $expires]);
        return $token;
    }

    public static function consume(string $token, string $type): ?int
    {
        if ($token === '' || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }
        $hash = hash('sha256', $token);
        $stmt = Database::connection()->prepare(
            'SELECT id, user_id FROM email_tokens
             WHERE token_hash = :hash AND type = :type AND used_at IS NULL AND expires_at > NOW()
             LIMIT 1'
        );
        $stmt->execute([':hash' => $hash, ':type' => $type]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $stmt = Database::connection()->prepare('UPDATE email_tokens SET used_at = NOW() WHERE id = :id AND used_at IS NULL');
        $stmt->execute([':id' => $row['id']]);
        return $stmt->rowCount() === 1 ? (int) $row['user_id'] : null;
    }
}
