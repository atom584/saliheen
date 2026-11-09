<?php
declare(strict_types=1);

namespace App\Core;

final class Request
{
    public static function method(): string
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    }

    public static function isPost(): bool
    {
        return self::method() === 'POST';
    }

    /**
     * @return array<string, mixed>
     */
    public static function input(): array
    {
        return json_input();
    }

    /**
     * @return array<string, mixed>
     */
    public static function query(): array
    {
        return $_GET;
    }

    public static function string(string $key, string $source = 'query'): string
    {
        $bag = $source === 'post' ? $_POST : $_GET;
        return isset($bag[$key]) && is_string($bag[$key]) ? trim($bag[$key]) : '';
    }
}
