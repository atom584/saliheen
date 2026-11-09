<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use App\Models\ClientService;
use App\Models\EmailToken;
use App\Models\User;

final class AuthService
{
    private static ?array $cachedUser = null;
    private static bool $resolved = false;

    public static function currentUser(bool $refresh = false): ?array
    {
        if ($refresh) {
            self::$cachedUser = null;
            self::$resolved = false;
        }
        if (self::$resolved) {
            return self::$cachedUser;
        }

        $id = (int) ($_SESSION['user_id'] ?? 0);
        if ($id <= 0) {
            return null;
        }
        $user = User::find($id);
        if (!$user || $user['status'] !== 'active') {
            return null;
        }

        self::$resolved = true;
        self::$cachedUser = $user;
        return self::$cachedUser;
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['authenticated_at'] = time();
        $_SESSION['last_activity'] = time();
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();

        self::$cachedUser = null;
        self::$resolved = false;
    }

    /**
     * @return array{ok: bool, user?: array, error?: string, code?: string}
     */
    public static function authenticate(string $email, string $password): array
    {
        $email = strtolower(trim($email));
        $user = User::findByEmail($email);

        if (!$user) {
            password_verify($password, '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG');
            return ['ok' => false, 'error' => 'The email or password is incorrect.', 'code' => 'invalid_credentials'];
        }

        if ($user['status'] !== 'active') {
            return ['ok' => false, 'error' => 'This account is suspended. Contact info@nsaliheen.com.', 'code' => 'suspended'];
        }

        if (empty($user['password_hash'])) {
            $provider = ucfirst((string) $user['auth_provider']);
            return [
                'ok' => false,
                'error' => 'This account uses ' . $provider . ' sign-in. Continue with ' . $provider . ' instead.',
                'code' => 'provider_only',
            ];
        }

        if (!password_verify($password, (string) $user['password_hash'])) {
            return ['ok' => false, 'error' => 'The email or password is incorrect.', 'code' => 'invalid_credentials'];
        }

        if (empty($user['email_verified_at'])) {
            return [
                'ok' => false,
                'error' => 'Verify your email address before signing in. Use the link in your verification email or resend it.',
                'code' => 'unverified',
            ];
        }

        if (password_needs_rehash((string) $user['password_hash'], PASSWORD_DEFAULT)) {
            User::updatePasswordHashById((int) $user['id'], password_hash($password, PASSWORD_DEFAULT));
        }

        self::login($user);
        AuditLog::record('auth.login', 'user', (int) $user['id']);
        return ['ok' => true, 'user' => User::find((int) $user['id']) ?? $user];
    }

    /**
     * Consumes a verification token, marks the email verified and activates the
     * service reserved during registration.
     */
    public static function verifyEmail(string $token): ?array
    {
        $userId = EmailToken::consume($token, 'verify');
        if ($userId === null) {
            return null;
        }
        User::markEmailVerified($userId);
        $user = User::find($userId);
        if (!$user) {
            return null;
        }
        if (!empty($user['company_id'])) {
            $changed = ClientService::activateInitial($userId, (int) $user['company_id']);
            if ($changed > 0) {
                AuditLog::record('service.activated_after_verification', 'user', $userId, ['company_id' => (int) $user['company_id']]);
            }
        }
        AuditLog::record('account.email_verified', 'user', $userId);
        return $user;
    }

    public static function resetPassword(string $token, string $password): ?array
    {
        $userId = EmailToken::consume($token, 'reset');
        if ($userId === null) {
            return null;
        }
        User::updatePasswordHash($userId, password_hash($password, PASSWORD_DEFAULT));
        $user = User::find($userId);
        if ($user) {
            AuditLog::record('auth.password_reset', 'user', $userId);
        }
        return $user;
    }
}
