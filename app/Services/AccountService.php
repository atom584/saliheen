<?php
declare(strict_types=1);

namespace App\Services;

use App\Database\Database;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\User;
use PDOException;
use Throwable;

final class AccountService
{
    public static function cleanText(mixed $value, int $maxLength): string
    {
        $text = is_string($value) ? $value : '';
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
        return mb_substr($text, 0, $maxLength);
    }

    public static function cleanPhone(mixed $value): string
    {
        $phone = is_string($value) ? trim($value) : '';
        $phone = preg_replace('/[^0-9+()\-\s]/', '', $phone) ?? '';
        return mb_substr(trim(preg_replace('/\s+/', ' ', $phone) ?? $phone), 0, 40);
    }

    public static function registrationInput(array $input): array
    {
        return [
            'full_name' => self::cleanText($input['full_name'] ?? $input['name'] ?? '', 160),
            'email' => strtolower(self::cleanText($input['email'] ?? '', 190)),
            'password' => is_string($input['password'] ?? null) ? (string) $input['password'] : '',
            'password_confirm' => is_string($input['password_confirm'] ?? $input['confirm'] ?? null)
                ? (string) ($input['password_confirm'] ?? $input['confirm'])
                : '',
            'company_name' => self::cleanText($input['company_name'] ?? $input['company'] ?? '', 190),
            'designation' => self::cleanText($input['designation'] ?? '', 120),
            'country' => self::cleanText($input['country'] ?? '', 100),
            'phone' => self::cleanPhone($input['phone'] ?? ''),
            'whatsapp' => self::cleanPhone($input['whatsapp'] ?? ''),
            'business_type' => self::cleanText($input['business_type'] ?? $input['businessType'] ?? '', 80),
            'terms' => !empty($input['terms']),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function validateInput(array $data, bool $requirePassword = true): array
    {
        $errors = [];

        $nameLength = mb_strlen($data['full_name']);
        if ($nameLength < 2 || $nameLength > 160) {
            $errors['full_name'] = 'Enter your full name.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 190) {
            $errors['email'] = 'Enter a valid business email address.';
        }

        if ($requirePassword) {
            $password = $data['password'];
            if (strlen($password) < 8) {
                $errors['password'] = 'Use at least 8 characters.';
            } elseif (strlen($password) > 72) {
                $errors['password'] = 'Use no more than 72 characters.';
            } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                $errors['password'] = 'Include at least one letter and one number.';
            }
            if ($data['password_confirm'] !== '' && $data['password'] !== $data['password_confirm']) {
                $errors['password_confirm'] = 'Passwords do not match.';
            }
        }

        $companyLength = mb_strlen($data['company_name']);
        if ($companyLength < 2 || $companyLength > 190) {
            $errors['company_name'] = 'Enter the registered company name.';
        }

        if (mb_strlen($data['designation']) < 2) {
            $errors['designation'] = 'Enter your designation.';
        }

        if ($data['country'] === '') {
            $errors['country'] = 'Select your country.';
        }

        if (!preg_match('/^[+0-9][0-9+()\-\s]{5,39}$/', $data['phone'])) {
            $errors['phone'] = 'Enter a valid phone number.';
        }

        if ($data['whatsapp'] !== '' && !preg_match('/^[+0-9][0-9+()\-\s]{5,39}$/', $data['whatsapp'])) {
            $errors['whatsapp'] = 'Enter a valid WhatsApp number or leave it blank.';
        }

        if ($data['business_type'] === '') {
            $errors['business_type'] = 'Select your business type.';
        }

        if (!$data['terms']) {
            $errors['terms'] = 'Accept the Terms of Service and Privacy Policy to continue.';
        }

        return $errors;
    }

    /**
     * Creates the company, user and initial service reservation in a single
     * transaction. Returns a user-facing error instead of throwing.
     *
     * @return array{ok: bool, user_id?: int, company_id?: int, errors?: array, error?: string}
     */
    public static function createClientAccount(
        array $data,
        ?array $service = null,
        string $provider = 'email',
        ?string $providerUserId = null,
        bool $emailVerified = false
    ): array {
        $pdo = Database::connection();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1 FOR UPDATE');
            $stmt->execute([':email' => $data['email']]);
            if ($stmt->fetchColumn() !== false) {
                $pdo->rollBack();
                return [
                    'ok' => false,
                    'errors' => ['email' => 'An account with this email already exists. Sign in instead.'],
                ];
            }

            $stmt = $pdo->prepare('SELECT id FROM companies WHERE name = :name LIMIT 1 FOR UPDATE');
            $stmt->execute([':name' => $data['company_name']]);
            if ($stmt->fetchColumn() !== false) {
                $pdo->rollBack();
                return [
                    'ok' => false,
                    'errors' => [
                        'company_name' => 'This company is already registered. Ask your company administrator for an invitation or contact info@nsaliheen.com.',
                    ],
                ];
            }

            $companyId = Company::create([
                'company_name' => $data['company_name'],
                'business_type' => $data['business_type'],
                'country' => $data['country'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);

            $userId = User::create([
                'company_id' => $companyId,
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'password_hash' => $provider === 'email' ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
                'auth_provider' => $provider,
                'role' => 'client',
                'company_role' => 'admin',
                'designation' => $data['designation'],
                'country' => $data['country'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp'] !== '' ? $data['whatsapp'] : null,
                'email_verified_at' => $emailVerified ? date('Y-m-d H:i:s') : null,
                'status' => 'active',
            ]);

            if ($service) {
                if ($emailVerified) {
                    ServiceAssignmentService::assignInitial($userId, $companyId, (int) $service['id']);
                } else {
                    ServiceAssignmentService::reserveInitial($userId, $companyId, (int) $service['id']);
                }
            }

            if ($providerUserId !== null) {
                \App\Models\OAuthAccount::link($userId, $provider, $providerUserId);
            }

            $pdo->commit();
        } catch (PDOException $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Client account creation failed: ' . $error->getMessage());
            if ((int) ($error->errorInfo[1] ?? 0) === 1062) {
                return [
                    'ok' => false,
                    'errors' => ['email' => 'An account with this email or company already exists.'],
                ];
            }
            return ['ok' => false, 'error' => 'Your account could not be created. Please try again.'];
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Client account creation failed: ' . $error->getMessage());
            return ['ok' => false, 'error' => 'Your account could not be created. Please try again.'];
        }

        AuditLog::record('account.created', 'user', $userId, [
            'company_id' => $companyId,
            'provider' => $provider,
            'service_id' => $service['id'] ?? null,
        ]);

        return ['ok' => true, 'user_id' => $userId, 'company_id' => $companyId];
    }
}
