<?php
declare(strict_types=1);

namespace App\Core;

final class Config
{
    /** @var array<string, mixed> */
    private static array $items = [];

    public static function load(string $directory): void
    {
        $config = [];
        foreach (['app', 'database', 'mail', 'oauth', 'security'] as $name) {
            $path = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $name . '.php';
            if (is_file($path)) {
                $values = require $path;
                if (is_array($values)) {
                    $config = self::merge($config, $values);
                }
            }
        }

        $local = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . 'config.local.php';
        if (is_file($local)) {
            $values = require $local;
            if (is_array($values)) {
                $config = self::merge($config, $values);
            }
        }

        self::$items = self::applyEnvironment($config);
    }

    public static function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return self::$items;
        }
        $value = self::$items;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        return self::$items;
    }

    /**
     * @param array<string, mixed> $base
     * @param array<string, mixed> $override
     * @return array<string, mixed>
     */
    private static function merge(array $base, array $override): array
    {
        foreach ($override as $key => $value) {
            if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                $base[$key] = self::merge($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }
        return $base;
    }

    /**
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    private static function applyEnvironment(array $config): array
    {
        $map = [
            'SA_ENV' => ['env'],
            'SA_APP_URL' => ['app_url'],
            'SA_BASE_PATH' => ['base_path'],
            'SA_DB_HOST' => ['db', 'host'],
            'SA_DB_PORT' => ['db', 'port'],
            'SA_DB_NAME' => ['db', 'name'],
            'SA_DB_USER' => ['db', 'user'],
            'SA_DB_PASS' => ['db', 'pass'],
            'SA_MAIL_TRANSPORT' => ['mail', 'transport'],
            'SA_MAIL_HOST' => ['mail', 'host'],
            'SA_MAIL_PORT' => ['mail', 'port'],
            'SA_MAIL_ENCRYPTION' => ['mail', 'encryption'],
            'SA_MAIL_USER' => ['mail', 'username'],
            'SA_MAIL_PASS' => ['mail', 'password'],
            'SA_MAIL_FROM' => ['mail', 'from_email'],
            'SA_ADMIN_EMAIL' => ['admin_email'],
            'SA_SECURE_COOKIES' => ['security', 'secure_cookies'],
            'SA_GOOGLE_CLIENT_ID' => ['oauth', 'google', 'client_id'],
            'SA_GOOGLE_CLIENT_SECRET' => ['oauth', 'google', 'client_secret'],
            'SA_APPLE_CLIENT_ID' => ['oauth', 'apple', 'client_id'],
            'SA_APPLE_TEAM_ID' => ['oauth', 'apple', 'team_id'],
            'SA_APPLE_KEY_ID' => ['oauth', 'apple', 'key_id'],
            'SA_APPLE_PRIVATE_KEY_PATH' => ['oauth', 'apple', 'private_key_path'],
            'SA_APPLE_PRIVATE_KEY' => ['oauth', 'apple', 'private_key'],
        ];

        foreach ($map as $envKey => $path) {
            $value = getenv($envKey);
            if ($value === false || $value === '') {
                continue;
            }
            $ref = &$config;
            foreach ($path as $segment) {
                if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                    $ref[$segment] = [];
                }
                $ref = &$ref[$segment];
            }
            if (in_array($envKey, ['SA_DB_PORT', 'SA_MAIL_PORT'], true)) {
                $value = (int) $value;
            }
            if ($envKey === 'SA_SECURE_COOKIES') {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
            $ref = $value;
            unset($ref);
        }

        return $config;
    }
}
