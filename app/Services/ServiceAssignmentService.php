<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\Database;
use App\Models\AuditLog;
use App\Models\ClientService;
use App\Models\ServiceRequest;
use Throwable;

final class ServiceAssignmentService
{
    public static function assignInitial(int $userId, int $companyId, int $serviceId): void
    {
        ClientService::assignInitial($userId, $companyId, $serviceId);
        AuditLog::record('service.assigned_initial', 'service', $serviceId, ['company_id' => $companyId]);
    }

    /**
     * Reserves the service chosen during registration. The assignment stays
     * inactive until the email address is verified, and is tied to the user so it
     * cannot be lost if verification happens on another device.
     */
    public static function reserveInitial(int $userId, int $companyId, int $serviceId): void
    {
        ClientService::reserveInitial($userId, $companyId, $serviceId);
        AuditLog::record('service.reserved_initial', 'service', $serviceId, ['company_id' => $companyId]);
    }

    public static function activateInitial(int $userId, int $companyId): void
    {
        $changed = ClientService::activateInitial($userId, $companyId);
        if ($changed > 0) {
            AuditLog::record('service.activated_after_verification', 'user', $userId, ['company_id' => $companyId]);
        }
    }

    /**
     * @return array{ok: bool, message: string, request_id?: int, status?: string}
     */
    public static function requestAdditional(array $user, int $serviceId, string $description = ''): array
    {
        $service = ServiceCatalogService::byId($serviceId);
        if (!$service || $service['status'] !== 'active') {
            return ['ok' => false, 'message' => 'That service is not available.'];
        }
        if ((int) $user['company_id'] <= 0) {
            return ['ok' => false, 'message' => 'Complete your company profile before requesting a service.'];
        }

        $companyId = (int) $user['company_id'];
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $existing = ClientService::findForCompanyAndService($companyId, $serviceId);

            if ($existing && $existing['status'] === 'active') {
                $pdo->rollBack();
                return ['ok' => false, 'message' => 'This service is already active for your company.'];
            }
            if ($existing && in_array($existing['status'], ['pending_approval', 'under_review'], true)) {
                $pdo->rollBack();
                return ['ok' => false, 'message' => 'A request for this service is already awaiting review.'];
            }

            $requestId = ServiceRequest::create((int) $user['id'], $companyId, $serviceId, $description);

            if ($existing) {
                ClientService::markRequested((int) $existing['id'], $requestId, (int) $user['id']);
            } else {
                ClientService::createRequested((int) $user['id'], $companyId, $serviceId, $requestId);
            }

            $pdo->commit();
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Service request failed: ' . $error->getMessage());
            return ['ok' => false, 'message' => 'The request could not be saved. Please try again.'];
        }

        AuditLog::record('service.requested', 'service_request', $requestId, [
            'service_id' => $serviceId,
            'company_id' => $companyId,
        ]);
        MailService::notifyAdminServiceRequest($user, $service, $requestId);

        return [
            'ok' => true,
            'message' => 'Your request has been submitted. Saliheen & Associates will review it before activation.',
            'request_id' => $requestId,
            'status' => 'pending_approval',
        ];
    }

    /**
     * Applies a service chosen before authentication after login or OAuth.
     * New accounts receive the selected service immediately; existing accounts
     * submit an additional-service request for admin approval.
     *
     * @return array{applied: bool, mode?: string, message?: string}
     */
    public static function attachSelectedAfterAuth(array $user, bool $newAccount): array
    {
        $token = ServiceCatalogService::currentToken();
        $service = ServiceCatalogService::adoptFromRequest();
        if (!$service || (int) ($user['company_id'] ?? 0) <= 0 || $user['role'] !== 'client') {
            ServiceCatalogService::clearSelected();
            return ['applied' => false];
        }

        $companyId = (int) $user['company_id'];
        $mode = $newAccount ? 'assigned' : 'requested';
        $message = $newAccount
            ? 'The selected service has been added to your dashboard.'
            : 'Your additional service request has been submitted for firm review.';

        if ($newAccount) {
            self::assignInitial((int) $user['id'], $companyId, (int) $service['id']);
        } else {
            $result = self::requestAdditional($user, (int) $service['id'], 'Selected during sign-in.');
            $message = $result['message'];
        }
        if ($token !== '') {
            ServiceCatalogService::consumeToken($token);
        }
        ServiceCatalogService::clearSelected();
        ServiceCatalogService::clearCookie();

        return ['applied' => true, 'mode' => $mode, 'message' => $message];
    }
}
