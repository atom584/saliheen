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

        View::page('public/explore-services', array_merge(compact(
            'user',
            'isClient',
            'selected',
            'services',
            'activeId',
            'jsonFlags'
        ), [
            'title' => 'Explore Our Legal Services | Saliheen & Associates',
            'description' => 'Explore legal, corporate governance, compliance, cyber, labour, board, disciplinary and advisory services from Saliheen & Associates and select the service you need.',
            'bodyClass' => 'explore-page',
            'canonical' => '/explore-services',
            'themeColor' => '#702632',
            'styles' => ['/css/styles.css', '/portal/services.css'],
            'scripts' => ['/js/site.js', '/portal/explore.js'],
            'footerVariant' => 'explore',
            'headerCta' => 'DISCUSS YOUR MATTER',
        ]), 'public');
    }
}
