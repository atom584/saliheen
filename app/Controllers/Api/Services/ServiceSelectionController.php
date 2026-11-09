<?php
declare(strict_types=1);

namespace App\Controllers\Api\Services;

use App\Middleware\CsrfMiddleware;
use App\Middleware\RateLimitMiddleware;
use App\Models\AuditLog;
use App\Services\AuthService;
use App\Services\ServiceAssignmentService;
use App\Services\ServiceCatalogService;

final class ServiceSelectionController
{
    public static function handle(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            json_response(['ok' => false, 'error' => 'Method not allowed.'], 405);
        }

        CsrfMiddleware::require();

        if (!RateLimitMiddleware::allow('service.select', client_ip(), 60, 3600)) {
            json_response(['ok' => false, 'error' => 'Too many selections. Please wait a few minutes and try again.'], 429);
        }

        $input = json_input();
        $serviceId = (int) ($input['service_id'] ?? 0);
        $intent = (string) ($input['intent'] ?? 'register');
        if (!in_array($intent, ['register', 'login', 'request'], true)) {
            $intent = 'register';
        }

        $service = ServiceCatalogService::byId($serviceId);
        if (!$service || $service['status'] !== 'active') {
            json_response(['ok' => false, 'error' => 'That service is not available. Refresh the page and choose a service from the list.'], 422);
        }

        ServiceCatalogService::setSelected($service);
        $user = AuthService::currentUser();

        if ($user !== null && $user['role'] === 'admin') {
            json_response(['ok' => false, 'error' => 'Administrator accounts cannot request client services.'], 403);
        }

        if ($user !== null && $user['role'] === 'client') {
            if (empty($user['company_id'])) {
                json_response(['ok' => false, 'error' => 'Complete your company profile before requesting a service.', 'code' => 'profile_incomplete'], 409);
            }
            $result = ServiceAssignmentService::requestAdditional($user, (int) $service['id'], 'Requested from the Explore Services page.');
            if (!$result['ok']) {
                json_response(['ok' => false, 'error' => $result['message']], 409);
            }
            ServiceCatalogService::clearSelected();
            ServiceCatalogService::clearCookie();
            json_response([
                'ok' => true,
                'mode' => 'requested',
                'message' => $result['message'],
                'request_id' => $result['request_id'] ?? null,
                'service' => ['id' => (int) $service['id'], 'name' => $service['name']],
            ]);
        }

        $token = ServiceCatalogService::createToken((int) $service['id']);
        ServiceCatalogService::rememberCookie($token);
        AuditLog::record('service.selected', 'service', (int) $service['id'], ['intent' => $intent]);

        $redirect = $intent === 'login'
            ? url('/login/') . '?sel=' . urlencode($token)
            : url('/register/') . '?sel=' . urlencode($token);

        json_response([
            'ok' => true,
            'mode' => 'selected',
            'redirect' => $redirect,
            'service' => ['id' => (int) $service['id'], 'name' => $service['name'], 'slug' => $service['slug']],
        ]);
    }
}
