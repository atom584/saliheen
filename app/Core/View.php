<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    /**
     * @param array<string, mixed> $variables
     */
    public static function render(string $template, array $variables = []): void
    {
        if (str_contains($template, '..') || preg_match('#[^a-z0-9/_-]#i', $template)) {
            throw new RuntimeException('Invalid view name.');
        }
        $path = dirname(__DIR__) . '/pages/' . $template . '.php';
        if (!is_file($path)) {
            throw new RuntimeException('View not found: ' . $template);
        }
        extract($variables, EXTR_SKIP);
        require $path;
    }
}
