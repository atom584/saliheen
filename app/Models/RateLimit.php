<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;
use Throwable;

final class RateLimit
{
    /**
     * Returns true when the action is still allowed and records the attempt.
     */
    public static function hit(string $action, string $identifier, int $max, int $windowSeconds): bool
    {
        $windowStart = date('Y-m-d H:i:s', intdiv(time(), $windowSeconds) * $windowSeconds);
        try {
            $stmt = Database::connection()->prepare(
                'INSERT INTO rate_limits (action, identifier, window_start, count)
                 VALUES (:action, :identifier, :window, 1)
                 ON DUPLICATE KEY UPDATE count = count + 1'
            );
            $stmt->execute([
                ':action' => $action,
                ':identifier' => substr($identifier, 0, 190),
                ':window' => $windowStart,
            ]);

            $stmt = Database::connection()->prepare(
                'SELECT count FROM rate_limits WHERE action = :action AND identifier = :identifier AND window_start = :window'
            );
            $stmt->execute([
                ':action' => $action,
                ':identifier' => substr($identifier, 0, 190),
                ':window' => $windowStart,
            ]);
            $count = (int) $stmt->fetchColumn();

            if (random_int(1, 100) === 1) {
                Database::connection()->exec("DELETE FROM rate_limits WHERE window_start < (NOW() - INTERVAL 2 DAY)");
            }

            return $count <= $max;
        } catch (Throwable $error) {
            error_log('Rate limit failed: ' . $error->getMessage());
            return true;
        }
    }

    public static function clear(string $action, string $identifier): void
    {
        try {
            $stmt = Database::connection()->prepare(
                'DELETE FROM rate_limits WHERE action = :action AND identifier = :identifier'
            );
            $stmt->execute([':action' => $action, ':identifier' => substr($identifier, 0, 190)]);
        } catch (Throwable $error) {
            error_log('Rate limit clear failed: ' . $error->getMessage());
        }
    }
}
