<?php
declare(strict_types=1);

namespace App\Controllers\Pages\PublicSite;

use App\Core\View;
use App\Services\AuthService;
use App\Services\ServiceCatalogService;

final class ExploreServicesController
{
    public static function handle(): void
    {
        $user = AuthService::currentUser();
        $isClient = $user !== null && $user['role'] === 'client' && !empty($user['company_id']);
        $selected = ServiceCatalogService::adoptFromRequest();
        $services = ServiceCatalogService::all();
        $activeId = (int) ($selected['id'] ?? 0);
        $jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        View::render('public/explore-services', compact(
            'user',
            'isClient',
            'selected',
            'services',
            'activeId',
            'jsonFlags'
        ));
    }
}
