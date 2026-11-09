<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

final class User
{
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT u.*, c.name AS company_name, c.business_type, c.country AS company_country,
                    c.contact_email AS company_email, c.contact_phone AS company_phone
             FROM users u
             LEFT JOIN companies c ON c.id = u.company_id
             WHERE u.id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => strtolower(trim($email))]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users
               (company_id, full_name, email, password_hash, auth_provider, role, company_role,
                designation, country, phone, whatsapp, email_verified_at, status)
             VALUES
               (:company, :name, :email, :password, :provider, :role, :company_role,
                :designation, :country, :phone, :whatsapp, :verified, :status)'
        );
        $stmt->execute([
            ':company' => $data['company_id'] ?? null,
            ':name' => $data['full_name'],
            ':email' => strtolower(trim($data['email'])),
            ':password' => $data['password_hash'] ?? null,
            ':provider' => $data['auth_provider'] ?? 'email',
            ':role' => $data['role'] ?? 'client',
            ':company_role' => $data['company_role'] ?? 'admin',
            ':designation' => $data['designation'] ?? null,
            ':country' => $data['country'] ?? null,
            ':phone' => $data['phone'] ?? null,
            ':whatsapp' => $data['whatsapp'] ?? null,
            ':verified' => $data['email_verified_at'] ?? null,
            ':status' => $data['status'] ?? 'active',
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function markEmailVerified(int $userId): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users SET email_verified_at = NOW() WHERE id = :id AND email_verified_at IS NULL'
        );
        $stmt->execute([':id' => $userId]);
    }

    public static function updatePasswordHash(int $userId, string $hash): void
    {
        $stmt = Database::connection()->prepare(
            "UPDATE users SET password_hash = :hash WHERE id = :id AND status = 'active'"
        );
        $stmt->execute([':hash' => $hash, ':id' => $userId]);
    }

    public static function updateAvatar(int $userId, ?string $path): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET avatar_path = :path WHERE id = :id');
        $stmt->execute([':path' => $path, ':id' => $userId]);
    }

    public static function updatePasswordHashById(int $userId, string $hash): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
        $stmt->execute([':hash' => $hash, ':id' => $userId]);
    }
}
