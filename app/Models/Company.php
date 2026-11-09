<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class Company
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM companies WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $company = $stmt->fetch();
        return $company ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO companies (name, business_type, country, contact_email, contact_phone)
             VALUES (:name, :business_type, :country, :email, :phone)'
        );
        $stmt->execute([
            ':name' => $data['company_name'],
            ':business_type' => $data['business_type'],
            ':country' => $data['country'],
            ':email' => $data['email'],
            ':phone' => $data['phone'] ?: null,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function nameExists(string $name, ?int $ignoreId = null): bool
    {
        $sql = 'SELECT id FROM companies WHERE name = :name';
        $params = [':name' => $name];
        if ($ignoreId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $ignoreId;
        }
        $stmt = Database::connection()->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        return $stmt->fetchColumn() !== false;
    }
}
