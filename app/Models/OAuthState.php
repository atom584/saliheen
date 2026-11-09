<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class OAuthState
{
    public static function create(string $provider, string $state, string $nonce, string $verifier, int $serviceId): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO oauth_states (state_hash, provider, nonce, verifier, service_id, expires_at)
             VALUES (:hash, :provider, :nonce, :verifier, :service, :expires)'
        );
        $stmt->execute([
            ':hash' => hash('sha256', $state),
            ':provider' => $provider,
            ':nonce' => $nonce,
            ':verifier' => $verifier,
            ':service' => $serviceId > 0 ? $serviceId : null,
            ':expires' => date('Y-m-d H:i:s', time() + 900),
        ]);

        if (random_int(1, 20) === 1) {
            Database::connection()->exec('DELETE FROM oauth_states WHERE expires_at < (NOW() - INTERVAL 1 DAY)');
        }
    }

    /**
     * @return array{nonce: string, verifier: string, service_id: int}|null
     */
    public static function take(string $provider, string $state): ?array
    {
        if ($state === '' || !preg_match('/^[a-f0-9]{48}$/', $state)) {
            return null;
        }
        $hash = hash('sha256', $state);
        $stmt = Database::connection()->prepare(
            'SELECT nonce, verifier, service_id FROM oauth_states
             WHERE state_hash = :hash AND provider = :provider AND expires_at > NOW()
             LIMIT 1'
        );
        $stmt->execute([':hash' => $hash, ':provider' => $provider]);
        $row = $stmt->fetch();
        Database::connection()->prepare('DELETE FROM oauth_states WHERE state_hash = :hash')->execute([':hash' => $hash]);
        if (!$row) {
            return null;
        }
        return [
            'nonce' => (string) $row['nonce'],
            'verifier' => (string) $row['verifier'],
            'service_id' => (int) ($row['service_id'] ?? 0),
        ];
    }
}
