<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Admin;

use App\Core\View;
use App\Middleware\AuthMiddleware;
use App\Services\PortalDataService;

final class DashboardController
{
    public static function handle(): void
    {
        $admin = AuthMiddleware::requireAdmin();
        $stats = PortalDataService::adminPortalStats();
        $analytics = PortalDataService::adminServiceAnalytics();
        $recentRequests = PortalDataService::adminRecentRequests(8);
        $pending = array_values(array_filter(
            PortalDataService::adminRecentRequests(50),
            static fn (array $row): bool => in_array($row['status'], ['pending', 'under_review'], true)
        ));
        $maxRegistrations = 1;
        foreach ($analytics['trend'] as $month) {
            $maxRegistrations = max($maxRegistrations, (int) $month['registrations']);
        }
        $initials = initials_from_name((string) $admin['full_name']);
        $flashHtml = render_flash_alerts();

        View::render('admin/dashboard', compact(
            'admin',
            'stats',
            'analytics',
            'recentRequests',
            'pending',
            'maxRegistrations',
            'initials',
            'flashHtml'
        ));
    }
}
