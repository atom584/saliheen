<?php
declare(strict_types=1);

namespace App\Controllers\Api\Auth;

use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Models\AuditLog;
use App\Models\EmailToken;
use App\Models\User;
use App\Services\AccountService;
use App\Services\AuthService;
use App\Services\MailService;
use App\Services\ServiceAssignmentService;
use App\Services\ServiceCatalogService;

final class AuthApiController
{
    public static function login(): void
    {
        self::requirePost();
        CsrfMiddleware::require();

        $input = json_input();
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = is_string($input['password'] ?? null) ? (string) $input['password'] : '';
        $scope = (string) ($input['scope'] ?? 'client');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            json_response(['ok' => false, 'error' => 'Enter your business email and password.'], 422);
        }

        if (!RateLimitMiddleware::allow('login.ip', client_ip(), 20, 900)) {
            json_response(['ok' => false, 'error' => 'Too many sign-in attempts. Please wait 15 minutes and try again.'], 429);
        }
        if (!RateLimitMiddleware::allow('login.email', $email, 8, 900)) {
            json_response(['ok' => false, 'error' => 'Too many sign-in attempts for this account. Please wait 15 minutes.'], 429);
        }

        $result = AuthService::authenticate($email, $password);
        if (!$result['ok']) {
            AuditLog::record('auth.login_failed', 'user', null, ['email' => $email, 'code' => $result['code'] ?? '']);
            json_response([
                'ok' => false,
                'error' => $result['error'],
                'code' => $result['code'] ?? 'invalid_credentials',
            ], 401);
        }

        $user = $result['user'];

        if ($scope === 'admin' && $user['role'] !== 'admin') {
            AuthService::logout();
            AuditLog::record('auth.admin_scope_rejected', 'user', (int) $user['id']);
            json_response(['ok' => false, 'error' => 'This account does not have administrator access.'], 403);
        }

        $attachment = ['applied' => false];
        if ($user['role'] === 'client') {
            $attachment = ServiceAssignmentService::attachSelectedAfterAuth($user, false);
        }

        RateLimitMiddleware::clear('login.email', $email);

        $redirect = $user['role'] === 'admin' ? url('/admin/dashboard/') : url('/client/dashboard/');
        $message = 'Signed in successfully.';
        if ($attachment['applied']) {
            $message = (string) ($attachment['message'] ?? $message);
        }

        json_response([
            'ok' => true,
            'message' => $message,
            'role' => $user['role'],
            'service_requested' => (bool) ($attachment['applied'] && ($attachment['mode'] ?? '') === 'requested'),
            'redirect' => $redirect,
        ]);
    }

    public static function register(): void
    {
        self::requirePost();
        CsrfMiddleware::require();

        if (AuthService::currentUser() !== null) {
            json_response(['ok' => false, 'error' => 'You are already signed in. Sign out first to create another account.'], 409);
        }

        $input = json_input();
        $data = AccountService::registrationInput($input);
        $errors = AccountService::validateInput($data, true);

        if ($errors) {
            json_response(['ok' => false, 'error' => 'Please correct the highlighted fields.', 'errors' => $errors], 422);
        }

        if (!RateLimitMiddleware::allow('register.ip', client_ip(), 8, 3600)) {
            json_response(['ok' => false, 'error' => 'Too many registration attempts. Please wait a while and try again.'], 429);
        }
        if (!RateLimitMiddleware::allow('register.email', $data['email'], 5, 3600)) {
            json_response(['ok' => false, 'error' => 'Too many attempts for this email address. Please wait a while and try again.'], 429);
        }

        $selectionToken = ServiceCatalogService::tokenFromRequest();
        $service = ServiceCatalogService::adoptFromRequest();

        $result = AccountService::createClientAccount($data, $service, 'email', null, false);
        if (!$result['ok']) {
            json_response([
                'ok' => false,
                'error' => $result['error'] ?? 'Please correct the highlighted fields.',
                'errors' => $result['errors'] ?? [],
            ], 409);
        }

        $userId = (int) $result['user_id'];
        $user = User::find($userId);
        $token = EmailToken::create($userId, 'verify', 86400);

        $mail = ['ok' => false];
        if ($user) {
            $mail = MailService::verification($user, $token);
        }

        if ($selectionToken !== '') {
            ServiceCatalogService::consumeToken($selectionToken);
        }
        ServiceCatalogService::clearSelected();
        ServiceCatalogService::clearCookie();

        RateLimitMiddleware::clear('register.email', $data['email']);
        AuditLog::record('account.registered', 'user', $userId, [
            'provider' => 'email',
            'service_selected' => $service['id'] ?? null,
            'mail_sent' => $mail['ok'],
        ]);

        json_response([
            'ok' => true,
            'message' => $mail['ok']
                ? 'Account created. Check your business email for the verification link.'
                : 'Account created, but the verification email could not be sent. Use "Resend verification" on the next page.',
            'mail_sent' => $mail['ok'],
            'redirect' => url('/verify/?email=' . urlencode($data['email']) . '&sent=' . ($mail['ok'] ? '1' : '0')),
        ]);
    }

    public static function logout(): void
    {
        self::requirePost();
        CsrfMiddleware::require();

        if (!empty($_SESSION['user_id'])) {
            AuditLog::record('auth.logout', 'user', (int) $_SESSION['user_id']);
        }
        AuthService::logout();
        flash('success', 'You have been signed out.');

        if (wants_json()) {
            json_response(['ok' => true, 'redirect' => url('/login/')]);
        }

        redirect('/login/');
    }

    public static function forgotPassword(): void
    {
        self::requirePost();
        CsrfMiddleware::require();

        $input = json_input();
        $email = strtolower(trim((string) ($input['email'] ?? '')));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            json_response(['ok' => false, 'error' => 'Enter a valid email address.'], 422);
        }

        if (!RateLimitMiddleware::allow('forgot.ip', client_ip(), 10, 3600) || !RateLimitMiddleware::allow('forgot.email', $email, 3, 3600)) {
            json_response(['ok' => false, 'error' => 'Too many reset requests. Please wait before trying again.'], 429);
        }

        $user = User::findByEmail($email);
        if ($user && $user['status'] === 'active' && !empty($user['email_verified_at'])) {
            $token = EmailToken::create((int) $user['id'], 'reset', 3600);
            MailService::passwordReset($user, $token);
            AuditLog::record('auth.password_reset_requested', 'user', (int) $user['id']);
        }

        json_response([
            'ok' => true,
            'message' => 'If a verified account exists for that address, a password reset link has been sent. The link expires in 60 minutes.',
        ]);
    }

    public static function resetPassword(): void
    {
        self::requirePost();
        CsrfMiddleware::require();

        if (!RateLimitMiddleware::allow('reset.ip', client_ip(), 20, 3600)) {
            json_response(['ok' => false, 'error' => 'Too many attempts. Please wait before trying again.'], 429);
        }

        $input = json_input();
        $token = is_string($input['token'] ?? null) ? trim((string) $input['token']) : '';
        $password = is_string($input['password'] ?? null) ? (string) $input['password'] : '';
        $confirm = is_string($input['password_confirm'] ?? $input['confirm'] ?? null)
            ? (string) ($input['password_confirm'] ?? $input['confirm'])
            : '';

        if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
            json_response(['ok' => false, 'error' => 'This password reset link is incomplete.'], 422);
        }

        $errors = [];
        if (strlen($password) < 8) {
            $errors['password'] = 'Use at least 8 characters.';
        } elseif (strlen($password) > 72) {
            $errors['password'] = 'Use no more than 72 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Include at least one letter and one number.';
        }
        if ($password !== $confirm) {
            $errors['password_confirm'] = 'Passwords do not match.';
        }
        if ($errors) {
            json_response(['ok' => false, 'error' => 'Please correct the highlighted fields.', 'errors' => $errors], 422);
        }

        $user = AuthService::resetPassword($token, $password);
        if ($user === null) {
            json_response([
                'ok' => false,
                'error' => 'This reset link is invalid, already used or expired. Request a new password reset link.',
            ], 422);
        }

        flash('success', 'Your password has been updated. Sign in with your new password.');
        json_response([
            'ok' => true,
            'message' => 'Your password has been updated.',
            'redirect' => url('/login/'),
        ]);
    }

    public static function verify(): void
    {
        self::requirePost();

        if (!RateLimitMiddleware::allow('verify.ip', client_ip(), 30, 3600)) {
            json_response(['ok' => false, 'error' => 'Too many verification attempts. Please wait and try again.'], 429);
        }

        $input = json_input();
        $token = is_string($input['token'] ?? null) ? trim((string) $input['token']) : '';

        if ($token === '') {
            json_response(['ok' => false, 'error' => 'This verification link is incomplete.'], 422);
        }

        $user = AuthService::verifyEmail($token);
        if ($user === null) {
            json_response([
                'ok' => false,
                'error' => 'This verification link is invalid, already used or expired. Sign in to request a new link.',
            ], 422);
        }

        AuthService::login($user);
        ServiceCatalogService::clearSelected();
        ServiceCatalogService::clearCookie();
        MailService::welcome($user);

        $redirect = $user['role'] === 'admin' ? url('/admin/dashboard/') : url('/client/dashboard/');
        json_response([
            'ok' => true,
            'message' => 'Your email is verified. Welcome to your client dashboard.',
            'redirect' => $redirect,
        ]);
    }

    public static function resendVerification(): void
    {
        self::requirePost();
        CsrfMiddleware::require();

        $input = json_input();
        $email = strtolower(trim((string) ($input['email'] ?? '')));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            json_response(['ok' => false, 'error' => 'Enter a valid email address.'], 422);
        }

        if (!RateLimitMiddleware::allow('resend.ip', client_ip(), 10, 3600) || !RateLimitMiddleware::allow('resend.email', $email, 3, 900)) {
            json_response(['ok' => false, 'error' => 'Too many requests. Please wait a few minutes before trying again.'], 429);
        }

        $user = User::findByEmail($email);
        if ($user && empty($user['email_verified_at']) && $user['status'] === 'active') {
            $token = EmailToken::create((int) $user['id'], 'verify', 86400);
            MailService::verification($user, $token);
            AuditLog::record('account.verification_resent', 'user', (int) $user['id']);
        }

        json_response([
            'ok' => true,
            'message' => 'If an unverified account exists for that address, a new verification email has been sent.',
        ]);
    }

    public static function completeOAuth(): void
    {
        self::requirePost();
        CsrfMiddleware::require();

        $pending = $_SESSION['_oauth_pending'] ?? null;
        if (!is_array($pending) || empty($pending['provider']) || empty($pending['sub']) || empty($pending['email'])) {
            json_response(['ok' => false, 'error' => 'Your sign-in session has expired. Start again from the registration page.', 'code' => 'oauth_expired'], 409);
        }
        if ((int) ($pending['created'] ?? 0) < time() - 1800) {
            unset($_SESSION['_oauth_pending']);
            json_response(['ok' => false, 'error' => 'Your sign-in session has expired. Start again from the registration page.', 'code' => 'oauth_expired'], 409);
        }

        $input = json_input();
        $input['email'] = $pending['email'];
        if (empty($input['full_name'])) {
            $input['full_name'] = $pending['name'] ?? '';
        }
        $data = AccountService::registrationInput($input);
        $errors = AccountService::validateInput($data, false);

        if ($errors) {
            json_response(['ok' => false, 'error' => 'Please correct the highlighted fields.', 'errors' => $errors], 422);
        }

        if (strtolower($data['email']) !== strtolower((string) $pending['email'])) {
            json_response(['ok' => false, 'error' => 'The email address does not match the verified provider account.'], 422);
        }

        $service = null;
        if ((int) ($pending['service_id'] ?? 0) > 0) {
            $candidate = ServiceCatalogService::byId((int) $pending['service_id']);
            if ($candidate && $candidate['status'] === 'active') {
                $service = $candidate;
            }
        }

        $result = AccountService::createClientAccount(
            $data,
            $service,
            (string) $pending['provider'],
            (string) $pending['sub'],
            true
        );

        if (!$result['ok']) {
            json_response([
                'ok' => false,
                'error' => $result['error'] ?? 'Please correct the highlighted fields.',
                'errors' => $result['errors'] ?? [],
            ], 409);
        }

        unset($_SESSION['_oauth_pending']);
        $selectionToken = ServiceCatalogService::tokenFromRequest();
        if ($selectionToken !== '') {
            ServiceCatalogService::consumeToken($selectionToken);
        }
        ServiceCatalogService::clearSelected();
        ServiceCatalogService::clearCookie();

        $user = User::find((int) $result['user_id']);
        if ($user) {
            AuthService::login($user);
            MailService::welcome($user, $service);
            AuditLog::record('account.oauth_completed', 'user', (int) $user['id'], [
                'provider' => $pending['provider'],
                'service_id' => $service['id'] ?? null,
            ]);
        }

        json_response([
            'ok' => true,
            'message' => 'Your client account is ready.',
            'redirect' => url('/client/dashboard/'),
        ]);
    }

    private static function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            json_response(['ok' => false, 'error' => 'Method not allowed.'], 405);
        }
    }
}
