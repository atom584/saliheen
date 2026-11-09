<?php
declare(strict_types=1);

namespace App\Controllers\Api\OAuth;

use App\Models\AuditLog;
use App\Models\OAuthAccount;
use App\Models\User;
use App\Services\AuthService;
use App\Services\OAuthService;
use App\Services\ServiceAssignmentService;
use App\Services\ServiceCatalogService;
use Throwable;

final class OAuthController
{
    public static function start(string $provider): never
    {
        if (!in_array($provider, ['google', 'apple'], true)) {
            http_response_code(404);
            exit('Not found');
        }

        if (!OAuthService::configured($provider)) {
            flash('error', ucfirst($provider) . ' sign-in is not configured yet. Register with your business email instead.');
            redirect('/register');
        }

        ServiceCatalogService::adoptFromRequest();
        $pkce = OAuthService::begin($provider);
        redirect(OAuthService::authorizeUrl($provider, $pkce, $pkce['state']));
    }

    public static function callback(string $provider): never
    {
        if (!in_array($provider, ['google', 'apple'], true)) {
            http_response_code(404);
            exit('Not found');
        }

        $input = $provider === 'apple' ? ($_POST !== [] ? $_POST : $_GET) : $_GET;
        $error = (string) ($input['error'] ?? '');
        $state = (string) ($input['state'] ?? '');
        $code = (string) ($input['code'] ?? '');
        $label = ucfirst($provider);

        if ($error !== '') {
            flash('error', $label . ' sign-in was cancelled.');
            redirect('/register');
        }
        if ($code === '') {
            flash('error', $label . ' did not return an authorization code.');
            redirect('/register');
        }

        $pending = OAuthService::takeState($provider, $state);
        if (!$pending) {
            flash('error', 'The ' . $label . ' sign-in session expired or was invalid. Please try again.');
            redirect('/register');
        }

        try {
            $exchange = OAuthService::exchangeCode($provider, $code, $pending['verifier']);
        } catch (Throwable $exchangeError) {
            error_log($label . ' token exchange failed: ' . $exchangeError->getMessage());
            flash('error', $label . ' sign-in could not be completed. Please try again.');
            redirect('/register');
        }
        if (!$exchange['ok'] || empty($exchange['id_token'])) {
            flash('error', $label . ' sign-in could not be verified. Please try again.');
            redirect('/register');
        }

        $verified = OAuthService::verifyIdToken($provider, (string) $exchange['id_token'], $pending['nonce']);
        if (!$verified['ok']) {
            error_log($label . ' ID token rejected: ' . ($verified['error'] ?? 'unknown'));
            flash('error', $label . ' sign-in could not be verified. Please try again.');
            redirect('/register');
        }

        $claims = $verified['claims'];
        $sub = (string) ($claims['sub'] ?? '');
        $email = strtolower((string) ($claims['email'] ?? ''));
        $emailVerified = $claims['email_verified'] ?? false;
        $emailVerified = $emailVerified === true || $emailVerified === 'true';

        if ($sub === '') {
            flash('error', $label . ' did not provide a valid account identifier.');
            redirect('/register');
        }

        if ($provider === 'google') {
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Google did not provide a verified email address.');
                redirect('/register');
            }
            if (!$emailVerified) {
                flash('error', 'Your Google email address is not verified. Verify it with Google and try again.');
                redirect('/register');
            }
        }

        $user = OAuthAccount::find($provider, $sub);
        if (!$user && $email !== '') {
            $user = User::findByEmail($email);
        }

        if ($user) {
            if ($user['status'] !== 'active') {
                flash('error', 'This account is suspended. Contact info@nsaliheen.com.');
                redirect('/login');
            }
            OAuthAccount::link((int) $user['id'], $provider, $sub);
            if ($provider === 'google' || $emailVerified) {
                User::markEmailVerified((int) $user['id']);
            }
            $user = User::find((int) $user['id']) ?? $user;
            AuditLog::record('auth.oauth_login', 'user', (int) $user['id'], ['provider' => $provider]);
            self::finishSession($user, (int) $pending['service_id'], $label);
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', $label . ' did not share an email address for this account. Register with your business email instead.');
            redirect('/register');
        }
        if (!$emailVerified) {
            flash('error', 'Your ' . $label . ' email address is not verified. Verify it with ' . $label . ' and try again.');
            redirect('/register');
        }

        $name = trim((string) ($claims['name'] ?? ''));
        if ($provider === 'google' && $name === '') {
            $name = trim(($claims['given_name'] ?? '') . ' ' . ($claims['family_name'] ?? ''));
        }
        if ($provider === 'apple' && !empty($input['user']) && is_string($input['user'])) {
            $userInfo = json_decode($input['user'], true);
            if (is_array($userInfo)) {
                $name = trim((string) ($userInfo['name']['firstName'] ?? '') . ' ' . (string) ($userInfo['name']['lastName'] ?? ''));
            }
        }

        $_SESSION['_oauth_pending'] = [
            'provider' => $provider,
            'sub' => $sub,
            'email' => $email,
            'name' => $name,
            'service_id' => (int) $pending['service_id'],
            'created' => time(),
        ];

        redirect('/register/complete');
    }

    private static function finishSession(array $user, int $serviceId, string $label): never
    {
        if ($user['role'] === 'admin') {
            AuthService::login($user);
            ServiceCatalogService::clearSelected();
            redirect('/admin/dashboard');
        }

        AuthService::login($user);
        $message = 'Signed in with ' . $label . '.';
        if ($serviceId > 0) {
            $service = ServiceCatalogService::byId($serviceId);
            if ($service && $service['status'] === 'active') {
                ServiceCatalogService::setSelected($service);
                $attachment = ServiceAssignmentService::attachSelectedAfterAuth($user, false);
                if ($attachment['applied']) {
                    $message = (string) ($attachment['message'] ?? $message);
                }
            }
        }
        flash('success', $message);
        redirect('/client/dashboard');
    }
}
