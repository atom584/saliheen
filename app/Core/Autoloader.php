<?php
declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    private const PREFIX = 'App\\';

    public static function register(): void
    {
        spl_autoload_register(static function (string $class): void {
            if (!str_starts_with($class, self::PREFIX)) {
                return;
            }
            $relative = substr($class, strlen(self::PREFIX));
            $file = dirname(__DIR__) . '/' . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require $file;
            }
        });
    }
}
