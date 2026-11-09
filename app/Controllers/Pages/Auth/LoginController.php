<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;
use App\Middleware\GuestMiddleware;
use App\Services\OAuthService;
use App\Services\ServiceCatalogService;

final class LoginController
{
    public static function handle(): void
    {
        $selection = ServiceCatalogService::adoptFromRequest();
        $selectionToken = ServiceCatalogService::currentToken();
        $suffix = $selectionToken !== '' ? '?sel=' . urlencode($selectionToken) : '';
        $registerUrl = url('/register/') . $suffix;

        GuestMiddleware::redirectIfSignedIn();

        $googleReady = OAuthService::configured('google');
        $appleReady = OAuthService::configured('apple');
        $flashHtml = render_flash_alerts();
        $next = isset($_GET['next']) && is_string($_GET['next']) ? $_GET['next'] : '';
        $next = preg_match('#^/client/#', $next) ? $next : '';

        View::render('auth/login', compact(
            'selection',
            'selectionToken',
            'suffix',
            'registerUrl',
            'googleReady',
            'appleReady',
            'flashHtml',
            'next'
        ));
    }
}
