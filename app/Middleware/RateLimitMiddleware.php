<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Models\RateLimit;

final class RateLimitMiddleware
{
    public static function allow(string $action, string $identifier, int $max, int $windowSeconds): bool
    {
        return RateLimit::hit($action, $identifier, $max, $windowSeconds);
    }

    public static function clear(string $action, string $identifier): void
    {
        RateLimit::clear($action, $identifier);
    }
}
