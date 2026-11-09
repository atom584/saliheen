<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class ServiceRequest
{
    public static function create(int $userId, int $companyId, int $serviceId, string $description): int
    {
        $stmt = Database::connection()->prepare(
            "INSERT INTO service_requests (user_id, company_id, service_id, request_description, status)
             VALUES (:user, :company, :service, :description, 'pending')"
        );
        $stmt->execute([
            ':user' => $userId,
            ':company' => $companyId,
            ':service' => $serviceId,
            ':description' => $description !== '' ? $description : null,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    /**
     * @return array<int, array>
     */
    public static function forCompany(int $companyId, int $limit = 20, int $offset = 0): array
    {
        $limit = max(1, min(100, $limit));
        $offset = max(0, $offset);
        $stmt = Database::connection()->prepare(
            'SELECT sr.*, s.name AS service_name, s.slug AS service_slug, s.icon AS service_icon
             FROM service_requests sr
             JOIN services s ON s.id = sr.service_id
             WHERE sr.company_id = :company
             ORDER BY sr.created_at DESC, sr.id DESC
             LIMIT ' . $limit . ' OFFSET ' . $offset
        );
        $stmt->execute([':company' => $companyId]);
        return $stmt->fetchAll();
    }

    public static function countForCompany(int $companyId): int
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM service_requests WHERE company_id = :company');
        $stmt->execute([':company' => $companyId]);
        return (int) $stmt->fetchColumn();
    }

    public static function recent(int $limit = 8): array
    {
        $limit = max(1, min(50, $limit));
        $sql = 'SELECT sr.*, s.name AS service_name, s.slug AS service_slug,
                       u.full_name AS client_name, u.email AS client_email,
                       c.name AS company_name, cs.assigned_staff, cs.status AS assignment_status
                FROM service_requests sr
                JOIN services s ON s.id = sr.service_id
                JOIN users u ON u.id = sr.user_id
                JOIN companies c ON c.id = sr.company_id
                LEFT JOIN client_services cs ON cs.id = (
                    SELECT id FROM client_services
                    WHERE company_id = sr.company_id AND service_id = sr.service_id
                    ORDER BY id DESC LIMIT 1
                )
                ORDER BY sr.created_at DESC, sr.id DESC
                LIMIT ' . $limit;
        return Database::connection()->query($sql)->fetchAll();
    }

    /**
     * @param array{service?:string,status?:string,q?:string,from?:string,to?:string} $filters
     * @return array<int, array>
     */
    public static function adminList(array $filters = [], int $limit = 200, int $offset = 0): array
    {
        [$where, $params] = self::adminFilters($filters);

        $sql = 'SELECT sr.*, s.name AS service_name, s.slug AS service_slug,
                       u.full_name AS client_name, u.email AS client_email,
                       c.name AS company_name, c.business_type, c.country AS company_country,
                       cs.id AS assignment_id, cs.assigned_staff, cs.activation_date,
                       cs.progress, cs.admin_notes, cs.status AS assignment_status
                FROM service_requests sr
                JOIN services s ON s.id = sr.service_id
                JOIN users u ON u.id = sr.user_id
                JOIN companies c ON c.id = sr.company_id
                LEFT JOIN client_services cs ON cs.id = (
                    SELECT id FROM client_services
                    WHERE company_id = sr.company_id AND service_id = sr.service_id
                    ORDER BY id DESC LIMIT 1
                )'
            . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
            . ' ORDER BY sr.created_at DESC, sr.id DESC LIMIT ' . max(1, min(200, $limit))
            . ' OFFSET ' . max(0, $offset);

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * @param array{service?:string,status?:string,q?:string,from?:string,to?:string} $filters
     */
    public static function adminCount(array $filters = []): int
    {
        [$where, $params] = self::adminFilters($filters);

        $sql = 'SELECT COUNT(*)
                FROM service_requests sr
                JOIN services s ON s.id = sr.service_id
                JOIN users u ON u.id = sr.user_id
                JOIN companies c ON c.id = sr.company_id'
            . ($where ? ' WHERE ' . implode(' AND ', $where) : '');

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * @param array{service?:string,status?:string,q?:string,from?:string,to?:string} $filters
     * @return array{0: array<int, string>, 1: array<string, string>}
     */
    private static function adminFilters(array $filters): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['service'])) {
            $where[] = 's.slug = :service';
            $params[':service'] = $filters['service'];
        }
        if (!empty($filters['status']) && in_array($filters['status'], ['pending', 'under_review', 'approved', 'rejected', 'cancelled'], true)) {
            $where[] = 'sr.status = :status';
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['q'])) {
            $where[] = '(u.full_name LIKE :q1 OR u.email LIKE :q2 OR c.name LIKE :q3)';
            $params[':q1'] = '%' . $filters['q'] . '%';
            $params[':q2'] = '%' . $filters['q'] . '%';
            $params[':q3'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['from'])) {
            $where[] = 'sr.created_at >= :from';
            $params[':from'] = $filters['from'] . ' 00:00:00';
        }
        if (!empty($filters['to'])) {
            $where[] = 'sr.created_at <= :to';
            $params[':to'] = $filters['to'] . ' 23:59:59';
        }

        return [$where, $params];
    }

    public static function findForUpdate(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT sr.*, s.name AS service_name, c.name AS company_name, u.full_name AS client_name
             FROM service_requests sr
             JOIN services s ON s.id = sr.service_id
             JOIN companies c ON c.id = sr.company_id
             JOIN users u ON u.id = sr.user_id
             WHERE sr.id = :id FOR UPDATE'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function markReviewed(int $id, string $status, int $adminId, ?string $notes): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE service_requests
             SET status = :status, reviewed_by = :admin, reviewed_at = NOW(),
                 admin_notes = COALESCE(:notes, admin_notes)
             WHERE id = :id'
        );
        $stmt->execute([':status' => $status, ':admin' => $adminId, ':notes' => $notes, ':id' => $id]);
    }
}
