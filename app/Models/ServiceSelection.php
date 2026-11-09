<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class ServiceSelection
{
    public static function create(int $serviceId, ?int $userId = null): string
    {
        $token = bin2hex(random_bytes(32));
        $stmt = Database::connection()->prepare(
            'INSERT INTO service_selections (token_hash, service_id, user_id, expires_at)
             VALUES (:hash, :service, :user, :expires)'
        );
        $stmt->execute([
            ':hash' => hash('sha256', $token),
            ':service' => $serviceId,
            ':user' => $userId,
            ':expires' => date('Y-m-d H:i:s', time() + 604800),
        ]);
        return $token;
    }

    public static function resolve(string $token): ?array
    {
        if ($token === '' || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }
        $stmt = Database::connection()->prepare(
            "SELECT ss.id AS selection_id, ss.user_id AS selection_user_id, s.*
             FROM service_selections ss
             JOIN services s ON s.id = ss.service_id
             WHERE ss.token_hash = :hash
               AND ss.used_at IS NULL
               AND ss.expires_at > NOW()
               AND s.status = 'active'
             LIMIT 1"
        );
        $stmt->execute([':hash' => hash('sha256', $token)]);
        $service = $stmt->fetch();
        if (!$service) {
            return null;
        }
        $featureStmt = Database::connection()->prepare(
            'SELECT feature FROM service_features WHERE service_id = :id ORDER BY sort_order, id'
        );
        $featureStmt->execute([':id' => $service['id']]);
        $service['features'] = $featureStmt->fetchAll(\PDO::FETCH_COLUMN);
        $service['selection_token'] = $token;
        return $service;
    }

    public static function consume(string $token): void
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            return;
        }
        $stmt = Database::connection()->prepare(
            'UPDATE service_selections SET used_at = NOW() WHERE token_hash = :hash AND used_at IS NULL'
        );
        $stmt->execute([':hash' => hash('sha256', $token)]);
    }
}
