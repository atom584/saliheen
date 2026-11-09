<?php
declare(strict_types=1);

use App\Core\Config;
use App\Database\Database;
use App\Middleware\CsrfMiddleware;
use App\Models\AuditLog;
use App\Models\RateLimit;
use App\Services\OAuthService;

function config(?string $key = null, mixed $default = null): mixed
{
    return Config::get($key, $default);
}

function db(): PDO
{
    return Database::connection();
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_path(): string
{
    $path = (string) config('base_path', '');
    if ($path === '' || $path === '/') {
        return '';
    }
    return '/' . trim($path, '/');
}

function url(string $path = ''): string
{
    return base_path() . '/' . ltrim($path, '/');
}

function absolute_url(string $path = ''): string
{
    return rtrim((string) config('app_url'), '/') . url($path);
}

function redirect(string $path, int $status = 302): never
{
    header('Location: ' . (preg_match('#^https?://#i', $path) ? $path : url($path)), true, $status);
    exit;
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function json_input(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return $_POST;
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $_POST;
}

function wants_json(): bool
{
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    return str_contains($accept, 'application/json')
        || strtolower($requestedWith) === 'xmlhttprequest'
        || str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/');
}

function client_ip(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'), 0, 45);
}

function is_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
        return true;
    }
    if (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https') {
        return true;
    }
    return (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function require_csrf(?string $token = null): void
{
    CsrfMiddleware::require($token);
}

function flash(string $type, string $message): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}

function pull_flash(): array
{
    $messages = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return is_array($messages) ? $messages : [];
}

function audit_log(string $event, ?string $subjectType = null, ?int $subjectId = null, array $meta = []): void
{
    AuditLog::record($event, $subjectType, $subjectId, $meta);
}

function rate_limit(string $action, string $identifier, int $max, int $windowSeconds): bool
{
    return RateLimit::hit($action, $identifier, $max, $windowSeconds);
}

function clear_rate_limit(string $action, string $identifier): void
{
    RateLimit::clear($action, $identifier);
}

function format_date(?string $value, string $format = 'd M Y'): string
{
    if (!$value) {
        return '—';
    }
    $timestamp = strtotime($value . ' UTC');
    return $timestamp ? date($format, $timestamp) : '—';
}

function service_status_label(string $status): string
{
    return match ($status) {
        'active' => 'Active',
        'pending_approval' => 'Pending Approval',
        'under_review' => 'Under Review',
        'inactive' => 'Inactive',
        default => ucfirst(str_replace('_', ' ', $status)),
    };
}

function service_status_tone(string $status): string
{
    return match ($status) {
        'active' => 'ok',
        'pending_approval' => 'med',
        'under_review' => 'info',
        'inactive' => 'neutral',
        default => 'neutral',
    };
}

function request_status_label(string $status): string
{
    return match ($status) {
        'pending' => 'Pending Approval',
        'under_review' => 'Under Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'cancelled' => 'Cancelled',
        default => ucfirst(str_replace('_', ' ', $status)),
    };
}

function service_dashboard_url(array $service): string
{
    return url('/client/services/' . $service['slug']);
}

function service_request_ref(int $id): string
{
    return 'SA-SR-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
}

function initials_from_name(string $name): string
{
    $initials = '';
    foreach (preg_split('/\s+/', trim($name)) as $part) {
        if ($part !== '') {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }
        if (strlen($initials) >= 2) {
            break;
        }
    }
    return $initials;
}

function avatar_url(?string $path): ?string
{
    $path = is_string($path) ? trim($path) : '';
    return $path === '' ? null : url('/' . ltrim($path, '/'));
}

function oauth_configured(string $provider): bool
{
    return OAuthService::configured($provider);
}
