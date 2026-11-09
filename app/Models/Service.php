<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class Service
{
    public static function all(bool $onlyActive = true): array
    {
        $sql = 'SELECT * FROM services';
        if ($onlyActive) {
            $sql .= " WHERE status = 'active'";
        }
        $sql .= ' ORDER BY sort_order, name';
        $services = Database::connection()->query($sql)->fetchAll();
        if ($services === []) {
            return [];
        }

        $ids = array_map(static fn (array $service): int => (int) $service['id'], $services);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Database::connection()->prepare(
            "SELECT service_id, feature FROM service_features WHERE service_id IN ($placeholders) ORDER BY service_id, sort_order, id"
        );
        $stmt->execute($ids);

        $features = [];
        foreach ($stmt->fetchAll() as $row) {
            $features[(int) $row['service_id']][] = $row['feature'];
        }
        foreach ($services as &$service) {
            $service['features'] = $features[(int) $service['id']] ?? [];
        }
        unset($service);

        return $services;
    }

    public static function bySlug(string $slug, bool $onlyActive = true): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM services WHERE slug = :slug' . ($onlyActive ? " AND status = 'active'" : '') . ' LIMIT 1'
        );
        $stmt->execute([':slug' => $slug]);
        $service = $stmt->fetch();
        if (!$service) {
            return null;
        }
        $service['features'] = self::features((int) $service['id']);
        return $service;
    }

    public static function byId(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM services WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $service = $stmt->fetch();
        if (!$service) {
            return null;
        }
        $service['features'] = self::features((int) $service['id']);
        return $service;
    }

    /**
     * @return array<int, string>
     */
    public static function features(int $serviceId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT feature FROM service_features WHERE service_id = :id ORDER BY sort_order, id'
        );
        $stmt->execute([':id' => $serviceId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
