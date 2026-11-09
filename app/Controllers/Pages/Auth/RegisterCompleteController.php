<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;
use App\Services\ServiceCatalogService;

final class RegisterCompleteController
{
    public static function handle(): void
    {
        $pending = $_SESSION['_oauth_pending'] ?? null;
        if (!is_array($pending) || empty($pending['provider']) || empty($pending['email']) || (int) ($pending['created'] ?? 0) < time() - 1800) {
            unset($_SESSION['_oauth_pending']);
            flash('error', 'Your provider sign-in session expired. Start again from the registration page.');
            redirect('/register/');
        }

        $provider = ucfirst((string) $pending['provider']);
        $email = (string) $pending['email'];
        $name = (string) ($pending['name'] ?? '');
        $service = (int) ($pending['service_id'] ?? 0) > 0 ? ServiceCatalogService::byId((int) $pending['service_id']) : null;
        if ($service && $service['status'] !== 'active') {
            $service = null;
        }

        View::render('auth/complete-registration', compact('pending', 'provider', 'email', 'name', 'service'));
    }
}
