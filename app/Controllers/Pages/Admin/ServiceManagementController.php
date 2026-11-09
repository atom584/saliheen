<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Admin;

use App\Core\View;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Models\Service;
use App\Services\AdminService;
use App\Services\PortalDataService;

final class ServiceManagementController
{
    public static function handle(): void
    {
        $admin = AuthMiddleware::requireAdmin();

        $filters = [
            'service' => isset($_GET['service']) && is_string($_GET['service']) ? trim($_GET['service']) : '',
            'status' => isset($_GET['status']) && is_string($_GET['status']) ? trim($_GET['status']) : '',
            'q' => isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '',
            'from' => isset($_GET['from']) && is_string($_GET['from']) ? trim($_GET['from']) : '',
            'to' => isset($_GET['to']) && is_string($_GET['to']) ? trim($_GET['to']) : '',
        ];
        foreach (['from', 'to'] as $dateKey) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters[$dateKey])) {
                $filters[$dateKey] = '';
            }
        }
        $filterQuery = array_filter($filters, static fn (string $value): bool => $value !== '');

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            CsrfMiddleware::require();
            $action = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : '';
            if (in_array($action, ['review', 'approve', 'reject', 'activate', 'deactivate', 'update'], true)) {
                $result = AdminService::action($action, $_POST, $admin);
                flash($result['ok'] ? 'success' : 'error', $result['message']);
            } else {
                flash('error', 'Unknown service action.');
            }
            $target = '/admin/services/';
            if ($filterQuery) {
                $target .= '?' . http_build_query($filterQuery);
            }
            $target .= '#service-requests';
            redirect($target);
        }

        $allServices = Service::all(false);
        $requests = PortalDataService::adminServiceRequests($filters);
        $assignments = PortalDataService::adminServiceAssignments();
        $analytics = PortalDataService::adminServiceAnalytics();
        $stats = PortalDataService::adminPortalStats();
        $flashHtml = render_flash_alerts();
        $maxTrend = 1;
        foreach ($analytics['trend'] as $month) {
            $maxTrend = max($maxTrend, (int) $month['registrations'], (int) $month['requests']);
        }
        $initials = initials_from_name((string) $admin['full_name']);

        View::render('admin/services', compact(
            'admin',
            'filters',
            'filterQuery',
            'allServices',
            'requests',
            'assignments',
            'analytics',
            'stats',
            'flashHtml',
            'maxTrend',
            'initials'
        ));
    }
}
