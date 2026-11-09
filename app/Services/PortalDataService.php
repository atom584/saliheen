<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\Database;
use App\Models\ClientService;
use App\Models\Company;
use App\Models\ServiceRequest;

final class PortalDataService
{
    public static function clientCompany(int $companyId): ?array
    {
        return Company::find($companyId);
    }

    /**
     * @return array{
     *   services: array, requests: array, requests_total: int, company: array|null,
     *   counts: array{active:int, pending:int, review:int, inactive:int},
     *   available:int
     * }
     */
    public static function clientPortalData(int $companyId, int $requestLimit = 20, int $requestOffset = 0): array
    {
        $services = ClientService::forCompany($companyId);
        $counts = ['active' => 0, 'pending' => 0, 'review' => 0, 'inactive' => 0];
        foreach ($services as $service) {
            match ($service['status']) {
                'active' => $counts['active']++,
                'pending_approval' => $counts['pending']++,
                'under_review' => $counts['review']++,
                default => $counts['inactive']++,
            };
        }

        $available = (int) Database::connection()
            ->query("SELECT COUNT(*) FROM services WHERE status = 'active'")
            ->fetchColumn();

        return [
            'services' => $services,
            'requests' => ServiceRequest::forCompany($companyId, $requestLimit, $requestOffset),
            'requests_total' => ServiceRequest::countForCompany($companyId),
            'company' => self::clientCompany($companyId),
            'counts' => $counts,
            'available' => $available,
        ];
    }

    /**
     * @return array{clients:int, companies:int, active_services:int, pending_requests:int, recent_clients:int}
     */
    public static function adminPortalStats(): array
    {
        $pdo = Database::connection();
        return [
            'clients' => (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'")->fetchColumn(),
            'companies' => (int) $pdo->query('SELECT COUNT(*) FROM companies')->fetchColumn(),
            'active_services' => (int) $pdo->query("SELECT COUNT(*) FROM client_services WHERE status = 'active'")->fetchColumn(),
            'pending_requests' => (int) $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status IN ('pending','under_review')")->fetchColumn(),
            'recent_clients' => (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client' AND created_at >= (NOW() - INTERVAL 30 DAY)")->fetchColumn(),
        ];
    }

    public static function adminRecentRequests(int $limit = 8): array
    {
        return ServiceRequest::recent($limit);
    }

    /**
     * @param array{service?:string,status?:string,q?:string,from?:string,to?:string} $filters
     */
    public static function adminServiceRequests(array $filters = [], int $limit = 200, int $offset = 0): array
    {
        return ServiceRequest::adminList($filters, $limit, $offset);
    }

    public static function adminServiceAssignments(): array
    {
        return ClientService::assignmentsForAdmin();
    }

    /**
     * @return array{services: array, trend: array, recent: array}
     */
    public static function adminServiceAnalytics(): array
    {
        $pdo = Database::connection();
        $services = $pdo->query(
            "SELECT s.id, s.slug, s.name, s.icon, s.status,
                    COUNT(cs.id) AS total_clients,
                    SUM(cs.status = 'active') AS active_clients,
                    SUM(cs.status IN ('pending_approval','under_review')) AS pending_clients
             FROM services s
             LEFT JOIN client_services cs ON cs.service_id = s.id
             GROUP BY s.id
             ORDER BY s.sort_order, s.name"
        )->fetchAll();

        $trendRows = $pdo->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total
             FROM users
             WHERE role = 'client' AND created_at >= (DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 5 MONTH))
             GROUP BY month ORDER BY month"
        )->fetchAll();

        $requestTrend = $pdo->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total
             FROM service_requests
             WHERE created_at >= (DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 5 MONTH))
             GROUP BY month ORDER BY month"
        )->fetchAll();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = date('Y-m', strtotime('-' . $i . ' month'));
            $months[$key] = ['month' => $key, 'registrations' => 0, 'requests' => 0];
        }
        foreach ($trendRows as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['registrations'] = (int) $row['total'];
            }
        }
        foreach ($requestTrend as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['requests'] = (int) $row['total'];
            }
        }

        return [
            'services' => $services,
            'trend' => array_values($months),
            'recent' => $pdo->query(
                "SELECT u.full_name, u.email, u.created_at, c.name AS company_name,
                        GROUP_CONCAT(s.name ORDER BY s.sort_order SEPARATOR ', ') AS services
                 FROM users u
                 JOIN companies c ON c.id = u.company_id
                 LEFT JOIN client_services cs ON cs.user_id = u.id
                 LEFT JOIN services s ON s.id = cs.service_id
                 WHERE u.role = 'client'
                 GROUP BY u.id
                 ORDER BY u.created_at DESC
                 LIMIT 10"
            )->fetchAll(),
        ];
    }
}
