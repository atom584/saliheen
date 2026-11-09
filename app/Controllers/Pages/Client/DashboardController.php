<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Client;

use App\Core\View;
use App\Middleware\AuthMiddleware;
use App\Services\PortalDataService;
use App\Services\ServiceCatalogService;

final class DashboardController
{
    public static function handle(): void
    {
        $user = AuthMiddleware::requireClient();
        $data = PortalDataService::clientPortalData((int) $user['company_id']);
        $company = $data['company'] ?? [];
        $services = $data['services'];
        $requests = $data['requests'];
        $counts = $data['counts'];
        $assignedIds = array_map(static fn (array $service): int => (int) $service['service_id'], $services);
        $availableServices = array_values(array_filter(
            ServiceCatalogService::all(),
            static fn (array $service): bool => !in_array((int) $service['id'], $assignedIds, true)
        ));
        $initials = initials_from_name((string) $user['full_name']);
        $avatarUrl = avatar_url($user['avatar_path'] ?? null);
        $pendingTotal = $counts['pending'] + $counts['review'];
        $flashHtml = render_flash_alerts();

        View::render('client/dashboard', compact(
            'user',
            'data',
            'company',
            'services',
            'requests',
            'counts',
            'assignedIds',
            'availableServices',
            'initials',
            'avatarUrl',
            'pendingTotal',
            'flashHtml'
        ));
    }
}
