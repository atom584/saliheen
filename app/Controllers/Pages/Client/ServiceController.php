<?php
declare(strict_types=1);

namespace App\Controllers\Pages\Client;

use App\Core\View;
use App\Middleware\AuthMiddleware;
use App\Models\ClientService;

final class ServiceController
{
    public static function handle(): void
    {
        $user = AuthMiddleware::requireClient();
        $slug = isset($_GET['slug']) && is_string($_GET['slug']) ? strtolower(trim($_GET['slug'])) : '';
        if (!preg_match('/^[a-z0-9-]{1,80}$/', $slug)) {
            http_response_code(404);
            exit('Service not found.');
        }

        $service = ClientService::activeForCompany((int) $user['company_id'], $slug);
        $assigned = null;
        foreach (ClientService::forCompany((int) $user['company_id']) as $row) {
            if ($row['slug'] === $slug) {
                $assigned = $row;
                break;
            }
        }

        if ($service === null) {
            http_response_code(403);
            $blocked = $assigned;
            $statusLabel = $blocked ? service_status_label($blocked['status']) : 'Not assigned';
            $title = 'Service access restricted';
        } else {
            $blocked = null;
            $statusLabel = 'Active';
            $title = $service['name'];
        }

        $initials = initials_from_name((string) $user['full_name']);
        $avatarUrl = avatar_url($user['avatar_path'] ?? null);

        View::render('client/service', compact(
            'user',
            'slug',
            'service',
            'assigned',
            'blocked',
            'statusLabel',
            'title',
            'initials',
            'avatarUrl'
        ));
    }
}
