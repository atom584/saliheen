<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;
use Throwable;

final class AuditLog
{
    public static function record(string $event, ?string $subjectType = null, ?int $subjectId = null, array $meta = []): void
    {
        try {
            $stmt = Database::connection()->prepare(
                'INSERT INTO audit_logs (actor_user_id, event, subject_type, subject_id, meta, ip)
                 VALUES (:actor, :event, :type, :subject, :meta, :ip)'
            );
            $stmt->execute([
                ':actor' => $_SESSION['user_id'] ?? null,
                ':event' => $event,
                ':type' => $subjectType,
                ':subject' => $subjectId,
                ':meta' => $meta === [] ? null : json_encode($meta, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                ':ip' => client_ip(),
            ]);
        } catch (Throwable $error) {
            error_log('Audit log failed: ' . $error->getMessage());
        }
    }
}
