<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

final class Router
{
    /**
     * @var array<int, array{
     *     method: string,
     *     regex: string,
     *     params: array<int, string>,
     *     handler: array{0: class-string, 1: string},
     *     middleware: array<int, mixed>,
     *     app: bool
     * }>
     */
    private static array $routes = [];

    /** @var array<string, string> */
    private static array $names = [];

    private static string $prefix = '';

    /** @var array<int, mixed> */
    private static array $groupMiddleware = [];

    /**
     * @param array{0: class-string, 1: string} $handler
     * @param array<int, mixed> $middleware
     */
    public static function get(
        string $pattern,
        array $handler,
        array $middleware = [],
        string $name = '',
        bool $app = false
    ): void {
        self::add('GET', $pattern, $handler, $middleware, $name, $app);
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     * @param array<int, mixed> $middleware
     */
    public static function post(
        string $pattern,
        array $handler,
        array $middleware = [],
        string $name = '',
        bool $app = false
    ): void {
        self::add('POST', $pattern, $handler, $middleware, $name, $app);
    }

    /**
     * @param array<int, mixed> $middleware
     */
    public static function group(string $prefix, array $middleware, callable $define): void
    {
        $previousPrefix = self::$prefix;
        $previousMiddleware = self::$groupMiddleware;
        self::$prefix = self::join($previousPrefix, $prefix);
        self::$groupMiddleware = array_merge($previousMiddleware, $middleware);

        try {
            $define();
        } finally {
            self::$prefix = $previousPrefix;
            self::$groupMiddleware = $previousMiddleware;
        }
    }

    /**
     * @param array<string, scalar> $params
     */
    public static function url(string $name, array $params = []): string
    {
        if (!isset(self::$names[$name])) {
            throw new RuntimeException('Unknown route name: ' . $name);
        }

        $pattern = self::$names[$name];
        foreach ($params as $key => $value) {
            $pattern = str_replace('{' . $key . '}', rawurlencode((string) $value), $pattern);
        }
        return $pattern;
    }

    public static function dispatch(): void
    {
        $path = self::requestPath();
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        // OAuth providers and older clients still call /api/...php directly.
        if (str_starts_with($path, '/api/') && str_ends_with($path, '.php')) {
            $path = substr($path, 0, -4);
        }

        if ($path !== '/' && str_ends_with($path, '/')) {
            $query = (string) ($_SERVER['QUERY_STRING'] ?? '');
            header('Location: ' . url(rtrim($path, '/')) . ($query !== '' ? '?' . $query : ''), true, 301);
            exit;
        }

        $allowed = [];
        foreach (self::$routes as $route) {
            if (preg_match($route['regex'], $path, $matches) !== 1) {
                continue;
            }
            if ($route['method'] !== $method) {
                $allowed[$route['method']] = true;
                continue;
            }

            if ($route['app']) {
                require_once APP_PATH . '/bootstrap.php';
            }
            self::runMiddleware($route['middleware']);

            $arguments = [];
            foreach ($route['params'] as $param) {
                $arguments[] = rawurldecode((string) $matches[$param]);
            }

            [$class, $action] = $route['handler'];
            $class::$action(...$arguments);
            return;
        }

        if ($allowed !== []) {
            self::renderError(405, implode(', ', array_keys($allowed)));
        }
        self::renderError(404);
    }

    /**
     * @param array{0: class-string, 1: string} $handler
     * @param array<int, mixed> $middleware
     */
    private static function add(
        string $method,
        string $pattern,
        array $handler,
        array $middleware,
        string $name,
        bool $app
    ): void {
        $fullPattern = self::join(self::$prefix, $pattern);
        [$regex, $params] = self::compile($fullPattern);

        self::$routes[] = [
            'method' => $method,
            'regex' => $regex,
            'params' => $params,
            'handler' => $handler,
            'middleware' => array_merge(self::$groupMiddleware, $middleware),
            'app' => $app,
        ];

        if ($name !== '') {
            self::$names[$name] = $fullPattern;
        }
    }

    /**
     * @return array{0: string, 1: array<int, string>}
     */
    private static function compile(string $pattern): array
    {
        $params = [];
        $regex = '';
        $offset = 0;

        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $pattern, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[1] as $index => $match) {
            $name = (string) $match[0];
            $position = (int) $matches[0][$index][1];
            $regex .= preg_quote(substr($pattern, $offset, $position - $offset), '#');
            $regex .= '(?P<' . $name . '>[^/]+)';
            $offset = $position + strlen((string) $matches[0][$index][0]);
            $params[] = $name;
        }

        $regex .= preg_quote(substr($pattern, $offset), '#');
        return ['#^' . $regex . '$#', $params];
    }

    private static function join(string $prefix, string $path): string
    {
        $prefix = trim($prefix, '/');
        $path = trim($path, '/');
        if ($prefix === '' && $path === '') {
            return '/';
        }
        return '/' . trim($prefix . '/' . $path, '/');
    }

    private static function requestPath(): string
    {
        $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        $base = base_path();
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
            if ($path === '') {
                $path = '/';
            }
        }

        return $path;
    }

    /**
     * @param array<int, mixed> $middleware
     */
    private static function runMiddleware(array $middleware): void
    {
        foreach ($middleware as $entry) {
            if (is_string($entry) && str_contains($entry, '::')) {
                [$class, $action] = explode('::', $entry, 2);
                $class::$action();
                continue;
            }
            if (is_array($entry) && count($entry) === 2) {
                [$class, $action] = $entry;
                $class::$action();
                continue;
            }
            if (is_callable($entry)) {
                $entry();
                continue;
            }
            throw new RuntimeException('Invalid route middleware.');
        }
    }

    private static function renderError(int $status, string $allow = ''): void
    {
        http_response_code($status);
        if ($allow !== '') {
            header('Allow: ' . $allow);
        }

        $isNotFound = $status === 404;
        if (wants_json()) {
            json_response([
                'ok' => false,
                'error' => $isNotFound ? 'Not found.' : 'Method not allowed.',
            ], $status);
        }

        try {
            View::page('public/404', [
                'title' => ($isNotFound ? 'Page not found' : 'Method not allowed') . ' | Saliheen & Associates',
                'description' => 'The page you requested could not be found.',
                'bodyClass' => 'error-page',
                'canonical' => null,
                'footerVariant' => 'compact',
            ], 'public');
        } catch (Throwable $error) {
            error_log('Error view failed: ' . $error->getMessage());
            echo '<!doctype html><meta charset="utf-8"><title>' . ($isNotFound ? 'Page not found' : 'Method not allowed') . '</title>'
                . '<h1>' . ($isNotFound ? 'Page not found' : 'Method not allowed') . '</h1>'
                . '<p><a href="/">Return home</a></p>';
        }
    }
}
