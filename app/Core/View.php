<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

final class View
{
    /**
     * @param array<string, mixed> $variables
     */
    public static function render(string $template, array $variables = []): void
    {
        $path = self::templatePath($template);
        extract($variables, EXTR_SKIP);
        require $path;
    }

    /**
     * Renders a page view inside a layout. The layout receives every page
     * variable plus `$content`.
     *
     * @param array<string, mixed> $variables
     */
    public static function page(string $template, array $variables = [], string $layout = 'public'): void
    {
        $path = self::templatePath($template);
        $content = self::capture($path, $variables);

        $layoutPath = self::layoutPath($layout);
        $variables['content'] = $content;
        extract($variables, EXTR_SKIP);
        require $layoutPath;
    }

    /**
     * @param array<string, mixed> $variables
     */
    public static function partial(string $name, array $variables = []): void
    {
        if (str_contains($name, '..') || preg_match('#[^a-z0-9/_-]#i', $name) === 1) {
            throw new RuntimeException('Invalid partial name.');
        }
        $path = dirname(__DIR__) . '/views/partials/' . $name . '.php';
        if (!is_file($path)) {
            throw new RuntimeException('Partial not found: ' . $name);
        }
        extract($variables, EXTR_SKIP);
        require $path;
    }

    /**
     * @param array<string, mixed> $variables
     */
    private static function capture(string $path, array $variables): string
    {
        ob_start();
        try {
            extract($variables, EXTR_SKIP);
            require $path;
            return (string) ob_get_clean();
        } catch (Throwable $error) {
            ob_end_clean();
            throw $error;
        }
    }

    private static function templatePath(string $template): string
    {
        if (str_contains($template, '..') || preg_match('#[^a-z0-9/_-]#i', $template) === 1) {
            throw new RuntimeException('Invalid view name.');
        }
        $path = dirname(__DIR__) . '/pages/' . $template . '.php';
        if (!is_file($path)) {
            throw new RuntimeException('View not found: ' . $template);
        }
        return $path;
    }

    private static function layoutPath(string $layout): string
    {
        if (preg_match('#^[a-z0-9_-]+$#i', $layout) !== 1) {
            throw new RuntimeException('Invalid layout name.');
        }
        $path = dirname(__DIR__) . '/views/layouts/' . $layout . '.php';
        if (!is_file($path)) {
            throw new RuntimeException('Layout not found: ' . $layout);
        }
        return $path;
    }
}
