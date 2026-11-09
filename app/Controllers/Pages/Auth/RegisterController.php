<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Auth;

use App\Core\View;
use App\Services\OAuthService;
use App\Services\ServiceCatalogService;

final class RegisterController
{
    public static function handle(): void
    {
        $selected = ServiceCatalogService::adoptFromRequest();
        $selectionToken = ServiceCatalogService::currentToken();
        $loginUrl = url('/login') . ($selectionToken !== '' ? '?sel=' . urlencode($selectionToken) : '');
        $exploreUrl = url('/explore-services');
        $googleReady = OAuthService::configured('google');
        $appleReady = OAuthService::configured('apple');
        $flashHtml = render_flash_alerts();

        View::render('auth/register', compact(
            'selected',
            'selectionToken',
            'loginUrl',
            'exploreUrl',
            'googleReady',
            'appleReady',
            'flashHtml'
        ));
    }
}
