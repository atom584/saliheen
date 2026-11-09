<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceSelection;

final class ServiceCatalogService
{
    public static function all(bool $onlyActive = true): array
    {
        return Service::all($onlyActive);
    }

    public static function byId(int $id): ?array
    {
        return Service::byId($id);
    }

    public static function bySlug(string $slug, bool $onlyActive = true): ?array
    {
        return Service::bySlug($slug, $onlyActive);
    }

    public static function setSelected(array $service): void
    {
        $_SESSION['selected_service'] = [
            'id' => (int) $service['id'],
            'slug' => $service['slug'],
            'name' => $service['name'],
            'at' => time(),
        ];
    }

    public static function selected(): ?array
    {
        $selected = $_SESSION['selected_service'] ?? null;
        if (!is_array($selected) || empty($selected['id'])) {
            return null;
        }
        if ((int) $selected['at'] < time() - 86400) {
            unset($_SESSION['selected_service']);
            return null;
        }
        $service = Service::byId((int) $selected['id']);
        return $service && $service['status'] === 'active' ? $service : null;
    }

    public static function clearSelected(): void
    {
        unset($_SESSION['selected_service']);
    }

    public static function cookieName(): string
    {
        return 'sa_selection';
    }

    public static function cookieOptions(int $expires): array
    {
        return [
            'expires' => $expires,
            'path' => base_path() === '' ? '/' : base_path(),
            'secure' => is_https() && (bool) config('security.secure_cookies', true),
            'httponly' => true,
            'samesite' => 'Lax',
        ];
    }

    /**
     * Stores an opaque, single-purpose service selection token in the database.
     * The browser only ever holds a random token, never an editable service id.
     */
    public static function createToken(int $serviceId, ?int $userId = null): string
    {
        return ServiceSelection::create($serviceId, $userId);
    }

    public static function tokenFromRequest(): string
    {
        $token = $_GET['sel'] ?? $_POST['selection'] ?? $_COOKIE[self::cookieName()] ?? '';
        return is_string($token) && preg_match('/^[a-f0-9]{64}$/', $token) ? $token : '';
    }

    public static function resolveToken(string $token): ?array
    {
        return ServiceSelection::resolve($token);
    }

    public static function consumeToken(string $token): void
    {
        ServiceSelection::consume($token);
    }

    public static function rememberCookie(string $token): void
    {
        setcookie(self::cookieName(), $token, self::cookieOptions(time() + 604800));
        $_COOKIE[self::cookieName()] = $token;
    }

    public static function clearCookie(): void
    {
        setcookie(self::cookieName(), '', self::cookieOptions(time() - 3600));
        unset($_COOKIE[self::cookieName()]);
    }

    /**
     * Resolves ?sel=, posted selection, or the selection cookie and restores it
     * into the server session. Returns the validated service row.
     */
    public static function adoptFromRequest(): ?array
    {
        $token = self::tokenFromRequest();
        if ($token === '') {
            return self::selected();
        }
        $service = self::resolveToken($token);
        if (!$service) {
            self::clearCookie();
            self::clearSelected();
            return null;
        }
        self::setSelected($service);
        self::rememberCookie($token);
        return $service;
    }

    public static function currentToken(): string
    {
        return self::tokenFromRequest();
    }
}
