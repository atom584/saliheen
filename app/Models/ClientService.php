<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class ClientService
{
    /**
     * @return array<int, array>
     */
    public static function forCompany(int $companyId): array
    {
        $stmt = Database::connection()->prepare(
            "SELECT cs.*, s.slug, s.name, s.description, s.icon
             FROM client_services cs
             JOIN services s ON s.id = cs.service_id
             WHERE cs.company_id = :company
             ORDER BY FIELD(cs.status, 'active', 'under_review', 'pending_approval', 'inactive'), s.sort_order"
        );
        $stmt->execute([':company' => $companyId]);
        $rows = $stmt->fetchAll();
        if ($rows === []) {
            return [];
        }

        $ids = array_map(static fn (array $row): int => (int) $row['service_id'], $rows);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $featureStmt = Database::connection()->prepare(
            "SELECT service_id, feature FROM service_features WHERE service_id IN ($placeholders) ORDER BY service_id, sort_order, id"
        );
        $featureStmt->execute($ids);
        $features = [];
        foreach ($featureStmt->fetchAll() as $feature) {
            $features[(int) $feature['service_id']][] = $feature['feature'];
        }
        foreach ($rows as &$row) {
            $row['features'] = $features[(int) $row['service_id']] ?? [];
        }
        unset($row);

        return $rows;
    }

    public static function activeForCompany(int $companyId, string $slug): ?array
    {
        $stmt = Database::connection()->prepare(
            "SELECT cs.*, s.slug, s.name, s.description, s.icon, s.id AS service_id
             FROM client_services cs
             JOIN services s ON s.id = cs.service_id
             WHERE cs.company_id = :company AND s.slug = :slug AND cs.status = 'active' AND s.status = 'active'
             LIMIT 1"
        );
        $stmt->execute([':company' => $companyId, ':slug' => $slug]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $stmt = Database::connection()->prepare(
            'SELECT feature FROM service_features WHERE service_id = :id ORDER BY sort_order, id'
        );
        $stmt->execute([':id' => $row['service_id']]);
        $row['features'] = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        return $row;
    }

    public static function findForCompanyAndService(int $companyId, int $serviceId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM client_services WHERE company_id = :company AND service_id = :service FOR UPDATE'
        );
        $stmt->execute([':company' => $companyId, ':service' => $serviceId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function assignInitial(int $userId, int $companyId, int $serviceId): void
    {
        $stmt = Database::connection()->prepare(
            "INSERT INTO client_services (user_id, company_id, service_id, request_date, activation_date, status)
             VALUES (:user, :company, :service, NOW(), NOW(), 'active')
             ON DUPLICATE KEY UPDATE
               user_id = VALUES(user_id),
               request_id = NULL,
               activation_date = NOW(),
               status = 'active'"
        );
        $stmt->execute([':user' => $userId, ':company' => $companyId, ':service' => $serviceId]);
    }

    public static function reserveInitial(int $userId, int $companyId, int $serviceId): void
    {
        $stmt = Database::connection()->prepare(
            "INSERT INTO client_services (user_id, company_id, service_id, request_date, status)
             VALUES (:user, :company, :service, NOW(), 'inactive')
             ON DUPLICATE KEY UPDATE
               user_id = VALUES(user_id),
               request_id = NULL,
               status = IF(status = 'active', 'active', 'inactive')"
        );
        $stmt->execute([':user' => $userId, ':company' => $companyId, ':service' => $serviceId]);
    }

    public static function activateInitial(int $userId, int $companyId): int
    {
        $stmt = Database::connection()->prepare(
            "UPDATE client_services
             SET status = 'active', activation_date = NOW()
             WHERE user_id = :user
               AND company_id = :company
               AND status = 'inactive'
               AND request_id IS NULL"
        );
        $stmt->execute([':user' => $userId, ':company' => $companyId]);
        return $stmt->rowCount();
    }

    public static function markRequested(int $assignmentId, int $requestId, int $userId): void
    {
        $stmt = Database::connection()->prepare(
            "UPDATE client_services
             SET request_id = :request, user_id = :user, request_date = NOW(), status = 'pending_approval'
             WHERE id = :id"
        );
        $stmt->execute([':request' => $requestId, ':user' => $userId, ':id' => $assignmentId]);
    }

    public static function createRequested(int $userId, int $companyId, int $serviceId, int $requestId): void
    {
        $stmt = Database::connection()->prepare(
            "INSERT INTO client_services (user_id, company_id, service_id, request_id, request_date, status)
             VALUES (:user, :company, :service, :request, NOW(), 'pending_approval')"
        );
        $stmt->execute([
            ':user' => $userId,
            ':company' => $companyId,
            ':service' => $serviceId,
            ':request' => $requestId,
        ]);
    }

    public static function activateForRequest(int $userId, int $companyId, int $serviceId, int $requestId, ?string $startDate, ?string $staff, ?string $notes): void
    {
        $stmt = Database::connection()->prepare(
            "INSERT INTO client_services
                (user_id, company_id, service_id, request_id, request_date, activation_date, assigned_staff, admin_notes, status)
             VALUES (:user, :company, :service, :request, NOW(), COALESCE(:start, NOW()), :staff, :notes, 'active')
             ON DUPLICATE KEY UPDATE
                user_id = VALUES(user_id),
                request_id = VALUES(request_id),
                activation_date = VALUES(activation_date),
                assigned_staff = COALESCE(VALUES(assigned_staff), assigned_staff),
                admin_notes = COALESCE(VALUES(admin_notes), admin_notes),
                status = 'active'"
        );
        $stmt->execute([
            ':user' => $userId,
            ':company' => $companyId,
            ':service' => $serviceId,
            ':request' => $requestId,
            ':start' => $startDate,
            ':staff' => $staff,
            ':notes' => $notes,
        ]);
    }

    public static function setStatusForRequest(int $requestId, string $status): void
    {
        $stmt = Database::connection()->prepare(
            "UPDATE client_services SET status = :status WHERE request_id = :request AND status <> 'active'"
        );
        $stmt->execute([':status' => $status, ':request' => $requestId]);
    }

    public static function findForUpdate(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT cs.*, s.name AS service_name, c.name AS company_name
             FROM client_services cs
             JOIN services s ON s.id = cs.service_id
             JOIN companies c ON c.id = cs.company_id
             WHERE cs.id = :id FOR UPDATE'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function updateFromAdmin(int $id, ?string $staff, ?string $startDate, ?string $notes, int $progress): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE client_services
             SET assigned_staff = :staff, activation_date = COALESCE(:start, activation_date),
                 admin_notes = :notes, progress = :progress
             WHERE id = :id'
        );
        $stmt->execute([
            ':staff' => $staff,
            ':start' => $startDate,
            ':notes' => $notes,
            ':progress' => $progress,
            ':id' => $id,
        ]);
    }

    public static function setStatusFromAdmin(int $id, string $status, ?string $startDate, ?string $staff, ?string $notes, int $progress): void
    {
        $stmt = Database::connection()->prepare(
            "UPDATE client_services
             SET status = :status,
                 activation_date = CASE WHEN :status2 = 'active' THEN COALESCE(:start, activation_date, NOW()) ELSE activation_date END,
                 assigned_staff = COALESCE(:staff, assigned_staff),
                 admin_notes = COALESCE(:notes, admin_notes),
                 progress = :progress
             WHERE id = :id"
        );
        $stmt->execute([
            ':status' => $status,
            ':status2' => $status,
            ':start' => $startDate,
            ':staff' => $staff,
            ':notes' => $notes,
            ':progress' => $progress,
            ':id' => $id,
        ]);
    }

    /**
     * @return array<int, array>
     */
    public static function assignmentsForAdmin(): array
    {
        return Database::connection()->query(
            "SELECT cs.*, s.name AS service_name, s.slug AS service_slug, s.icon AS service_icon,
                    u.full_name AS client_name, u.email AS client_email, c.name AS company_name
             FROM client_services cs
             JOIN services s ON s.id = cs.service_id
             JOIN users u ON u.id = cs.user_id
             JOIN companies c ON c.id = cs.company_id
             ORDER BY FIELD(cs.status, 'active', 'under_review', 'pending_approval', 'inactive'), cs.updated_at DESC
             LIMIT 300"
        )->fetchAll();
    }
}
