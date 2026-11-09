<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\Database;
use App\Models\AuditLog;
use App\Models\ClientService;
use App\Models\ServiceRequest;
use DateTime;
use Throwable;

final class AdminService
{
    public static function staffValue(mixed $value): ?string
    {
        $staff = AccountService::cleanText($value, 160);
        return $staff === '' ? null : $staff;
    }

    public static function notesValue(mixed $value): ?string
    {
        $notes = is_string($value) ? trim($value) : '';
        $notes = mb_substr($notes, 0, 2000);
        return $notes === '' ? null : $notes;
    }

    public static function startDate(mixed $value): ?string
    {
        $date = is_string($value) ? trim($value) : '';
        if ($date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return null;
        }
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        return $parsed && $parsed->format('Y-m-d') === $date ? $date . ' 00:00:00' : null;
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public static function action(string $action, array $input, array $admin): array
    {
        $pdo = Database::connection();
        $adminId = (int) $admin['id'];

        try {
            $pdo->beginTransaction();

            if (in_array($action, ['review', 'approve', 'reject'], true)) {
                $requestId = (int) ($input['request_id'] ?? 0);
                $request = ServiceRequest::findForUpdate($requestId);
                if (!$request) {
                    $pdo->rollBack();
                    return ['ok' => false, 'message' => 'That service request no longer exists.'];
                }

                $notes = self::notesValue($input['admin_notes'] ?? '');
                $staff = self::staffValue($input['assigned_staff'] ?? '');
                $startDate = self::startDate($input['start_date'] ?? '');

                if ($action === 'review') {
                    ServiceRequest::markReviewed($requestId, 'under_review', $adminId, $notes);
                    ClientService::setStatusForRequest($requestId, 'under_review');
                    $message = 'Request marked as under review.';
                } elseif ($action === 'approve') {
                    ServiceRequest::markReviewed($requestId, 'approved', $adminId, $notes);
                    ClientService::activateForRequest(
                        (int) $request['user_id'],
                        (int) $request['company_id'],
                        (int) $request['service_id'],
                        $requestId,
                        $startDate,
                        $staff,
                        $notes
                    );
                    $message = 'Request approved and service activated.';
                } else {
                    ServiceRequest::markReviewed($requestId, 'rejected', $adminId, $notes);
                    ClientService::setStatusForRequest($requestId, 'inactive');
                    $message = 'Request rejected.';
                }

                $audit = [
                    'request_id' => $requestId,
                    'service_id' => (int) $request['service_id'],
                    'company_id' => (int) $request['company_id'],
                ];
                $pdo->commit();
                AuditLog::record('service_request.' . $action, 'service_request', $requestId, $audit);
                return ['ok' => true, 'message' => $message . ' ' . $request['client_name'] . ' — ' . $request['service_name'] . '.'];
            }

            if (in_array($action, ['activate', 'deactivate', 'update'], true)) {
                $assignmentId = (int) ($input['assignment_id'] ?? 0);
                $assignment = ClientService::findForUpdate($assignmentId);
                if (!$assignment) {
                    $pdo->rollBack();
                    return ['ok' => false, 'message' => 'That service assignment no longer exists.'];
                }

                $staff = self::staffValue($input['assigned_staff'] ?? '');
                $notes = self::notesValue($input['admin_notes'] ?? '');
                $startDate = self::startDate($input['start_date'] ?? '');
                $progress = isset($input['progress']) && $input['progress'] !== ''
                    ? max(0, min(100, (int) $input['progress']))
                    : (int) $assignment['progress'];

                if ($action === 'update') {
                    ClientService::updateFromAdmin(
                        $assignmentId,
                        $staff !== null ? $staff : $assignment['assigned_staff'],
                        $startDate,
                        $notes,
                        $progress
                    );
                    $message = 'Service assignment updated.';
                } else {
                    $status = $action === 'activate' ? 'active' : 'inactive';
                    ClientService::setStatusFromAdmin($assignmentId, $status, $startDate, $staff, $notes, $progress);
                    $message = $action === 'activate' ? 'Service activated.' : 'Service deactivated.';
                }

                $audit = [
                    'assignment_id' => $assignmentId,
                    'service_id' => (int) $assignment['service_id'],
                    'company_id' => (int) $assignment['company_id'],
                    'status' => $action === 'deactivate' ? 'inactive' : ($action === 'activate' ? 'active' : $assignment['status']),
                ];
                $pdo->commit();
                AuditLog::record('service_assignment.' . $action, 'client_service', $assignmentId, $audit);
                return ['ok' => true, 'message' => $message . ' ' . $assignment['company_name'] . ' — ' . $assignment['service_name'] . '.'];
            }

            $pdo->rollBack();
            return ['ok' => false, 'message' => 'Unknown service action.'];
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Admin service action failed: ' . $error->getMessage());
            return ['ok' => false, 'message' => 'The action could not be completed. Please try again.'];
        }
    }
}
